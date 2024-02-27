<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\Title;
use App\Models\Content;
use App\Models\DocumentationImage;
use App\Models\Subtitle;
use App\Models\SupportCategory;
use App\Models\SupportSubCategory;
use App\Models\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketImage;
use App\Models\User;
use App\Models\Role;

class AdminController extends Controller
{

    public function profile()
    {
        $user = auth()->user();
        $supportCategories = SupportCategory::all();

        return view('admin.profile', compact('user', 'supportCategories'));
    }

    public function updateProfile(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|max:255',
            'oldpassword' => 'sometimes|required_with:newpassword',
            'newpassword' => 'nullable|required_with:oldpassword|different:oldpassword',
            'retypepassword' => 'nullable|required_with:newpassword|same:newpassword',

        ];

        $messages = [
            'name.required' => 'The Name field is required.',
            'username.required' => 'The Username field is required.',
            'email.required' => 'The Email field is required.',
            'name.max' => 'The Name should not exceed 255 characters.',
            'username.max' => 'The Username should not exceed 255 characters.',
            'email.max' => 'The Email should not exceed 255 characters.',
            'oldpassword.required_with' => 'The Old Password field is required when New Password is present.',
            'newpassword.required_with' => 'The New Password field is required when Old Password is present.',
            'newpassword.different' => 'The New Password must be different from the Old Password.',
            'retypepassword.required_with' => 'The Retype Password field is required when New Password is present.',
            'retypepassword.same' => 'The Retype Password and New Password must match.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $updateAdmin = User::find($id);

        $updateAdmin->name = $request->input('name');
        $updateAdmin->username = $request->input('username');
        $updateAdmin->email = $request->input('email');
        $updateAdmin->save();

        if ($request->filled('oldpassword') && $request->filled('newpassword') && $request->filled('retypepassword')) {
            if (Hash::check($request->input('oldpassword'), $updateAdmin->password)) {

                if ($request->input('newpassword') === $request->input('retypepassword')) {
                    $updateAdmin->password = Hash::make($request->input('newpassword'));
                    $updateAdmin->save();

                    return redirect()->route('adminDashboard')->with('success', 'Profile updated successfully');
                } else {
                    // return redirect()->back()->with('error', 'New and retyped passwords do not match.');
                    $validator->errors()->add('newpassword', 'New and retyped passwords do not match.');
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }
            } else {
                // return redirect()->back()->with('error', 'Wrong old password!');
                $validator->errors()->add('oldpassword', 'Wrong old password!');
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
            }
        } else {
            return redirect()->route('adminDashboard')->with('success', 'Profile updated successfully');
        }
    }

    public function adminDashboard(Request $request)
    {
        $ticketStatuses = TicketStatus::all();
        $ticketCounts = [];
        $currentYear = now()->year;

        foreach ($ticketStatuses as $status) {
            // Get ticket counts for each priority within the status
            $tickets = Ticket::where('status_id', $status->id)
                ->whereYear('created_at', $currentYear)
                ->whereNull('deleted_at')
                ->get();

            // Initialize priority counts for this status
            $highCount = 0;
            $mediumCount = 0;
            $lowCount = 0;

            foreach ($tickets as $ticket) {
                switch ($ticket->priority) {
                    case 'High':
                        $highCount++;
                        break;
                    case 'Medium':
                        $mediumCount++;
                        break;
                    case 'Low':
                        $lowCount++;
                        break;
                }
            }

            // Calculate total ticket count for this status
            $totalTicketCount = $highCount + $mediumCount + $lowCount;

            // Assign counts to ticketCounts array
            $ticketCounts[$status->id] = [
                'total' => $totalTicketCount,
                'High' => $highCount,
                'Medium' => $mediumCount,
                'Low' => $lowCount
            ];
        }

        $unassignedTickets = Ticket::whereYear('created_at', $currentYear)->whereNull('pic_id')->orderByDesc('tickets.id')->get();

        $unassignedHigh = 0;
        $unassignedMedium = 0;
        $unassignedLow = 0;
        $totalUnassigned = 0;

        foreach ($unassignedTickets as $ticket) {
            switch ($ticket->priority) {
                case 'High':
                    $unassignedHigh++;
                    break;
                case 'Medium':
                    $unassignedMedium++;
                    break;
                case 'Low':
                    $unassignedLow++;
                    break;
            }

            $totalUnassigned = $unassignedHigh + $unassignedMedium + $unassignedLow;
        }

        if ($request->has('year_status')) {
            $selectedYearStatus = $request->year_status;
        } else {
            $selectedYearStatus = $currentYear;
        }

        if ($request->has('year_category')) {
            $selectedYearCategory = $request->year_category;
        } else {
            $selectedYearCategory = $currentYear;
        }

        $ticketsByStatus = Ticket::select(
                        DB::raw('MONTH(tickets.created_at) AS month'),
                        'ticket_statuses.status',
                        DB::raw('COUNT(*) AS ticket_count')
                    )
                    ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
                    ->join(DB::raw('(SELECT DISTINCT status_id FROM tickets) AS distinct_statuses'), function ($join) {
                        $join->on('tickets.status_id', '=', 'distinct_statuses.status_id');
                    })
                    ->whereYear('tickets.created_at', $selectedYearStatus)
                    ->groupBy('month', 'ticket_statuses.status')
                    ->orderBy('month')
                    ->orderBy('ticket_statuses.status')
                    ->get();

        $ticketsByCategory = Ticket::select(
                        DB::raw('MONTH(tickets.created_at) AS month'),
                        'support_categories.category_name',
                        DB::raw('COUNT(*) AS ticket_count')
                    )
                    ->join('support_categories', 'tickets.category_id', '=', 'support_categories.id')
                    ->join(DB::raw('(SELECT DISTINCT category_id FROM tickets) AS distinct_categories'), function ($join) {
                        $join->on('tickets.category_id', '=', 'distinct_categories.category_id');
                    })
                    ->whereYear('tickets.created_at', $selectedYearCategory)
                    ->groupBy('month', 'support_categories.category_name')
                    ->orderBy('month')
                    ->orderBy('support_categories.category_name')
                    ->get();

        return view('admin.adminDashboard', compact('ticketStatuses', 'ticketCounts', 'currentYear', 'ticketsByStatus', 'ticketsByCategory', 'unassignedTickets','totalUnassigned', 'unassignedHigh', 'unassignedMedium', 'unassignedLow'));
    }

    public function helpdesk(Request $request)
    {
        $tickets = Ticket::with('supportCategories', 'ticketImages')->get();
        $categories = SupportCategory::all();

        return view('admin.helpdesk', compact('tickets','categories'));
    }

    public function getTicket(Request $request)
    {
        $category_id = $request->input('category_id');
        $priority = $request->input('priority');
        $date = $request->input('filter_date');
        $searchTerm = $request->input('searchTerm');

        // Get the page and per_page values from the request, default to 1 and 10 if not provided
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $authUser = User::where('id', '=', Auth::user()->id)->first();

        $authUserId = $authUser->id;
        $authUserCategoryId = $authUser->category_id;

        // if ($authUser->role_id == 1) {
        //     $query = DB::table('tickets')
        //     ->join('support_categories', 'support_categories.id', 'tickets.category_id')
        //     ->join('ticket_statuses', 'tickets.status_id', 'ticket_statuses.id')
        //     ->leftJoin('users', 'tickets.pic_id', 'users.id')
        //     ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*', 'users.*','users.id as pic_id','tickets.id as ticket_id', 'tickets.created_at as t_created_at')
        //     ->orderByDesc('ticket_id')
        //     ->whereNull('tickets.deleted_at');

        // } elseif ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
        //     $query = DB::table('tickets')
        //     ->join('support_categories', 'support_categories.id', 'tickets.category_id')
        //     ->join('ticket_statuses', 'tickets.status_id', 'ticket_statuses.id')
        //     ->leftJoin('users', 'tickets.pic_id', 'users.id')
        //     ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*', 'users.*','users.id as pic_id','tickets.id as ticket_id', 'tickets.created_at as t_created_at')
        //     ->orderByDesc('ticket_id')
        //     ->where('tickets.category_id', $authUserCategoryId)
        //     ->whereNull('tickets.deleted_at');

        // } elseif ($authUser->role_id !== 1 && $authUser->manage_own_ticket == 1) {
        //     $query = DB::table('tickets')
        //     ->join('support_categories', 'support_categories.id', 'tickets.category_id')
        //     ->join('ticket_statuses', 'tickets.status_id', 'ticket_statuses.id')
        //     ->leftJoin('users', 'tickets.pic_id', 'users.id')
        //     ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*', 'users.*','users.id as pic_id','tickets.id as ticket_id', 'tickets.created_at as t_created_at')
        //     ->orderByDesc('ticket_id')
        //     ->where('tickets.pic_id', $authUserId)
        //     ->whereNull('tickets.deleted_at');
        // }

        $query = Ticket::join('support_categories', 'support_categories.id', 'tickets.category_id')
        ->join('ticket_statuses', 'tickets.status_id', 'ticket_statuses.id')
        ->leftJoin('users', 'tickets.pic_id', 'users.id')
        ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*', 'users.*','users.id as pic_id','tickets.id as ticket_id', 'tickets.created_at as t_created_at', 'tickets.category_id')
        ->orderByDesc('ticket_id')
        ->whereNull('tickets.deleted_at');


        if (isset($date) && !empty($date)) {
            $query->whereDate('tickets.created_at', $date);
        }

        if (isset($category_id) && !empty($category_id)) {
            $query->where('tickets.category_id', $category_id);
        }

        if (isset($priority) && !empty($priority)) {
            $query->where('tickets.priority', $priority);
        }

        if (isset($searchTerm) && !empty($searchTerm)) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('tickets.ticket_no', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.sender_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.sender_email', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.subject', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.message', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.priority', 'LIKE', "%$searchTerm%")
                    ->orWhere('support_categories.category_name', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.remarks', 'LIKE', "%$searchTerm%")
                    ->orWhere('ticket_statuses.status', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.pic_id', 'LIKE', "%$searchTerm%")
                    ->orWhere('users.name', 'LIKE', "%$searchTerm%");
            });
        }

        // Check if perPage is -1 (indicating "All")
        if ($perPage == -1) {
            // Load all tickets without pagination
            $tickets = $query->get();

            // Set the total_pages to 1 since all tickets are loaded in one page
            $totalPages = 1;

             // Count the total number of tickets
            $currentEntries = $tickets->count();

        } else {
            // Perform your database query with pagination
            $tickets = $query->paginate($perPage, ['*'], 'page', $page);

            // Get the total pages from the paginator
            $totalPages = $tickets->lastPage();

            // Count the total number of tickets
            $currentEntries = $tickets->total();
        }

        // Check if $tickets is a paginator instance
        if ($tickets instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            // If $tickets is a paginator instance, return the items
            $ticketsData = $tickets->items();
        } else {
            // If $tickets is a collection, return it directly
            $ticketsData = $tickets;
        }

        // Fetch tickets from the database, excluding soft deleted records
        $totalTickets = Ticket::whereNull('deleted_at')->get();

        // Count the total number of tickets
        $totalEntries = $totalTickets->count();

        $response = [
            'tickets' => $ticketsData,
            'total_pages' => $totalPages,
            'total_entries' => $totalEntries,
            'current_entries' => $currentEntries
        ];

        return response()->json($response);
    }

    public function ticket()
    {
        $statuses = TicketStatus::with('tickets.supportCategories')->get();
        return view('admin.ticket', compact('statuses'));
    }

    public function getTicketByStatus(Request $request)
    {
        $authUser = User::where('id', '=', Auth::user()->id)->first();

        $query = Ticket::join('support_categories', 'tickets.category_id', 'support_categories.id')
        ->join('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
        ->leftJoin('users', 'tickets.pic_id', 'users.id')
        ->select('tickets.*', 'support_categories.category_name', 'users.name', 'ticket_statuses.status')
        ->orderByDesc('tickets.created_at')
        ->whereNull('tickets.deleted_at');

        if ($authUser->role_id == 1) {
            $query->get();
        } else if ($authUser->role_id !== 1) {
            if ($authUser->manage_ticket_in_category == 1) {
                $query->where('tickets.category_id',  $authUser->category_id);
            } elseif ($authUser->manage_own_ticket == 1) {
                $query->where('tickets.pic_id', $authUser->id);
            }
        }

        $searchTerm = $request->input('searchTerm');

        // Apply search term filter
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('tickets.ticket_no', 'like', "%$searchTerm%")
                    ->orWhere('tickets.sender_name', 'like', "%$searchTerm%")
                    ->orWhere('tickets.sender_email', 'like', "%$searchTerm%")
                    ->orWhere('support_categories.category_name', 'like', "%$searchTerm%");
            });
        }

        $ticketsByStatus = $query->get()->groupBy('status_id');

        $statuses = TicketStatus::all();

        // Prepare the response with the correct structure
        $response = [];
        foreach ($statuses as $status) {
            // $ticketCount = $ticketsByStatus[$status->id]->count() ?? 0;
            $ticketCount = $ticketsByStatus->get($status->id, collect())->count() ?? 0;

            $response[] = [
                'status' => $status,
                'ticket_count' => $ticketCount,
                'tickets' => $ticketsByStatus[$status->id] ?? []
            ];
        }

        return response()->json(['statuses' => $response]);
    }

    public function updateTicketKanban(Request $request)
    {
        // Get the ticket ID and new status ID from the request
        $ticketId = $request->input('ticketId');
        $newStatusId = $request->input('newStatus');

        // Update the ticket's status_id in the database
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status_id = $newStatusId;
        $ticket->save();

        // Return a response indicating success
        return response()->json(['message' => 'Ticket status updated successfully'], 200);
    }

    public function categorySumm(SupportCategory $supportCategory)
    {
        $supportCategory = SupportCategory::with(['tickets' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'tickets.ticketStatus', 'tickets.users'])->find($supportCategory->id);

        $tickets = $supportCategory->tickets;

        return view('admin.categorySumm', compact('supportCategory', 'tickets'));
    }

    public function ticketSumm(TicketStatus $status)
    {
        $authUser = User::where('id', '=', Auth::user()->id)->first();

        $authUserId = $authUser->id;
        $authUserCategoryId = $authUser->category_id;

        if ($authUser->role_id == 1) {
            $status = TicketStatus::with(['tickets' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }, 'tickets.supportCategories', 'tickets.users'])->find($status->id);

            $tickets = $status->tickets;

        } elseif ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
            $status = TicketStatus::with(['tickets' => function ($query) use ($authUserCategoryId){
                $query->where('category_id', '=', $authUserCategoryId)->orderBy('created_at', 'desc');
            }, 'tickets.supportCategories', 'tickets.users'])->find($status->id);

            $tickets = $status->tickets()->where('tickets.category_id', $authUserCategoryId)->get();

        } elseif ($authUser->role_id !== 1 && $authUser->manage_own_ticket == 1) {
            $status = TicketStatus::with(['tickets' => function ($query) use ($authUserId){
                $query->where('pic_id', '=', $authUserId)->orderBy('created_at', 'desc');
            }, 'tickets.supportCategories', 'tickets.users'])->find($status->id);

            $tickets = $status->tickets()->where('tickets.pic_id', $authUserId)->get();
        }

        return view('admin.ticketSumm', compact('status', 'tickets'));
    }

    public function viewTicket($id)
    {
        $ticket = Ticket::find($id);
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();
        $ticketImages = TicketImage::where('ticket_id', $id)
                                ->get();
        $users = User::select('users.id', 'users.name', 'support_categories.category_name')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('support_categories', 'users.category_id', '=', 'support_categories.id')
                    ->where('roles.role_name', '!=', 'Super Admin')
                    ->get();

        return view('admin.viewTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages', 'users'));
    }

    public function createTicket()
    {
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();
        $users = User::select('users.id', 'users.name', 'support_categories.category_name')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('support_categories', 'users.category_id', '=', 'support_categories.id')
                    ->where('roles.role_name', '!=', 'Super Admin')
                    ->get();

        return view('admin.createTicket', compact('supportCategories', 'ticketStatuses', 'users'));
    }

    public function addTicket(Request $request)
    {
        $rules = [
            'sender_name' => 'required|max:255',
            'sender_email' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required|max:5000',
            'category_id' => 'required',
            'priority'=> 'required',
            't_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];

        $messages = [
            'sender_name.required' => 'The Sender Name field is required.',
            'sender_name.max' => 'The Sender Name may not be greater than 255 characters.',
            'sender_email.required' => 'The Sender Email field is required.',
            'sender_email.max' => 'The Sender Email may not be greater than 255 characters.',
            'subject.required' => 'The Subject field is required.',
            'subject.max' => 'The Subject may not be greater than 255 characters.',
            'message.required' => 'The Message field is required.',
            'message.max' => 'The Message may not be greater than 5000 characters.',
            'category_id.required' => 'Please select category.',
            'priority.required' => 'Please select priority.',
            't_image.image' => 'Must be an image format.',
            't_image.max' => 'Image should not exceed 2 GB.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $category = SupportCategory::find($request->input('category_id'));
        $categoryName = $category->category_name;

        $abbreviationCategory = strtoupper(substr($categoryName, 0, 4));

        $priority = $request->input('priority');

        // Extract the first alphabet and convert to uppercase
        $abbreviationPriority = strtoupper(substr($priority, 0, 1));

        // Get the current running number for the given category and priority
        $number = Ticket::withTrashed()
                ->where('category_id', $request->input('category_id'))
                ->orderBy('id', 'desc')
                ->first();

        $picId = $request->input('pic_id');

        if ($picId !== null) {
            $user = User::find($picId);

            // Check if the user is found and the category_id matches
            if ($user && $request->input('category_id') !== $user->category_id) {
                $validator->errors()->add('pic_id', 'The selected PIC does not belong to the specified category.');
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $ticket = Ticket::create([
            'sender_name' => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'category_id' => $request->input('category_id'),
            'pic_id' => $picId,
            'priority' => $request->input('priority'),
            'status_id' => 1
        ]);

        $ticketId = $ticket->id;

        $currentRunningNumber = null;

        if ($number) {
            // If $number is not null, get the ticket_no property
            $currentRunningNumber = $number->ticket_no;
        }

        $pattern = '/-(\d+)$/';

        if (empty($currentRunningNumber)) {
            $nextRunningNumber = '0000001';
        } else {
            if (preg_match($pattern, $currentRunningNumber, $matches)) {
                // $matches[1] contains the extracted running number
                $extractedRunningNumber = (int)$matches[1];

                // Increment the running number for the next ticket
                $nextRunningNumber = sprintf('%07d', intval($extractedRunningNumber) + 1);
            }
        }

        // Format the ticket number
        $ticketNo = "T-$abbreviationCategory-$abbreviationPriority-$nextRunningNumber";

        // Update the ticket with the formatted ticket number
        $ticket->update([
            'ticket_no' => $ticketNo
        ]);

        $carInputs = $request->file('car', []);

        foreach ($carInputs as $carInput) {

            // Check if 't_image' is set and is an uploaded file
            if (isset($carInput['t_image'])) {

                $ticketImage = $carInput['t_image'];

                // Get the file extension and generate a unique filename
                $extension = $ticketImage->getClientOriginalExtension();

                $fileName = $ticketNo . '_image_' . time() . '.' . $extension;

                // Move the uploaded file to the desired location
                $ticketImage->move('storage/tickets/', $fileName);

                // Create a new TicketImage record in the database
                $newImage = TicketImage::create([
                    'ticket_id' => $ticketId,
                    't_image' => $fileName
                ]);
            }
        }

        // Mail::send(new SubmitTicket($ticket));

        return redirect()->route('adminDashboard')->with('success', 'Ticket submitted successfully');
    }

    public function editTicket($id)
    {
        $ticket = Ticket::find($id);
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();
        $ticketImages = TicketImage::where('ticket_id', $id)
                                    ->get();

        $users = User::select('users.id', 'users.name', 'support_categories.category_name')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('support_categories', 'users.category_id', '=', 'support_categories.id')
                    ->where('roles.role_name', '!=', 'Super Admin')
                    ->get();

        return view('admin.editTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages', 'users'));
    }

    public function updateTicket(Request $request, $id)
    {
        $rules = [
            'ticket_no' => 'required',
            'sender_name' => 'required|max:255',
            'sender_email' => 'required|max:255',
            'subject' => 'required|max:255',
            'message' => 'required|max:5000',
            'category_id' => 'required',
            'priority' => 'required',
            'status_id' => 'required',
        ];

        $messages = [
            'ticket_no.required' => 'The Ticket No. field is required.',
            'sender_name.required' => 'The Sender Name field is required.',
            'sender_name.max' => 'The Sender Name may not be greater than 255 characters.',
            'sender_email.required' => 'The Sender Email field is required.',
            'sender_email.max' => 'The Sender Email may not be greater than 255 characters.',
            'subject.required' => 'The Subject field is required.',
            'subject.max' => 'The Subject may not be greater than 255 characters.',
            'message.required' => 'The Message field is required.',
            'message.max' => 'The Message may not be greater than 5000 characters.',
            'category_id.required' => 'The Category field is required.',
            'priority.required' => 'The Priority field is required.',
            'status_id.required' => 'The Status field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateTicket = Ticket::find($id);

        $status_id = $request->input('status_id');

        $picId = $request->input('pic_id');

        if ($picId !== null) {
            $user = User::find($picId);

            // Check if the user is found and the category_id matches
            if ($user && $request->input('category_id') !== $user->category_id) {
                $validator->errors()->add('pic_id', 'The selected PIC does not belong to the specified category.');
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $updateTicket->update([
            'ticket_no' => $request->input('ticket_no'),
            'sender_name' => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
            'status_id' => $request->input('status_id'),
            'pic_id' => $request->input('pic_id'),
            'remarks' => $request->input('remarks')
        ]);

        return redirect()->route('ticketSumm', ['status' => $status_id])->with('success', 'Ticket updated successfully.');
    }

    public function deleteTicket($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();

        return redirect()->back()->with('success', 'Ticket deleted successfully.');
    }

    // public function viewTicketImage($id)
    // {
    //     $ticketImages = TicketImage::where('ticket_id', $id)->get();
    //     $tickets = Ticket::find($id);

    //     return view('admin.viewTicketImage', compact('ticketImages', 'tickets'));
    // }

    public function viewContent(Title $title)
    {
        $title->load([
            'subtitles.contents' => function ($query) {
                $query->orderBy('c_sequence');
            }
        ]);

        $titles = Title::with('subtitles.contents')->get();

        return view('admin.viewContent', compact('title', 'titles'));
    }

    public function performance()
    {
        $authUser = User::where('id', '=', Auth::user()->id)->first();

        if ($authUser->role_id == 1) {

            $supportCategories = SupportCategory::all();

            foreach ($supportCategories as $supportCategory) {
                // Retrieve users associated with the current support category
                $users = $supportCategory->users()->withCount('tickets')->get();

                // Assign the users back to the support category
                $supportCategory->users = $users;
            }

        } elseif ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
            $supportCategories = SupportCategory::where('support_categories.id', $authUser->category_id)
                                                ->get();

            foreach ($supportCategories as $supportCategory) {
                // Retrieve users associated with the current support category
                $users = $supportCategory->users()->withCount('tickets')->get();

                // Assign the users back to the support category
                $supportCategory->users = $users;
            }
        }

        $ticketStatuses = TicketStatus::all();

        // Loop through each support category
        foreach ($supportCategories as $supportCategory) {
            // Loop through each user in the support category
            foreach ($supportCategory->users as $user) {
                // Initialize a temporary array to hold ticket counts
                $ticketCounts = [];

                // Fetch ticket counts for each ticket status for the current user
                foreach ($ticketStatuses as $status) {
                    $ticketCounts[$status->status] = $user->tickets()->where('status_id', $status->id)->count();
                }

                // Assign the temporary array to a different property of the $user object
                $user->ticketCounts = $ticketCounts;
            }
        }
        return view('admin.performance', compact('supportCategories', 'ticketStatuses'));
    }

    public function viewPerformance($id)
    {
        $tickets = Ticket::where('pic_id', $id)->get();
        $users = User::find($id);

        return view('admin.viewPerformance', compact('tickets', 'users'));
    }

    public function titleSumm()
    {
        $titles = Title::orderBy('t_sequence')->get();

        return view('admin.titleSumm', compact('titles'));
    }

    public function viewMoreSubtitle($id)
    {
        $subtitles = Subtitle::where('title_id', $id)->orderBy('s_sequence')->get();

        $title = Title::find($id);

        return view('admin.viewMoreSubtitle', compact('subtitles', 'title'));
    }

    public function createTitle()
    {
        return view('admin.createTitle');
    }

    public function addTitle(Request $request)
    {
        $rules = [
            'title_name' => 'required|max:255',
        ];

        $messages = [
            'title_name.required' => 'The Title field is required.',
            'title_name.max' => 'The Title should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $number = Title::orderBy('t_sequence', 'desc')->first();

        $newTsequence = $number ? $number->t_sequence + 1 : 1;

        $title = Title::create([
            'title_name' => $request->input('title_name'),
            't_sequence' => $newTsequence
        ]);

        return redirect()->route('titleSumm')->with('success', 'New title created successfully.');
    }

    public function editTitle($id)
    {
        $title = Title::find($id);

        return view('admin.editTitle', compact('title'));
    }

    public function updateTitle(Request $request, $id)
    {
        $rules = [
            't_sequence' => 'required',
            'title_name' => 'required|max:255',
        ];

        $messages = [
            't_sequence.required' => 'The Sequence field is required.',
            'title_name.required' => 'The Title field is required.',
            'title_name.max' => 'The Title should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateTitle = Title::find($id);

        $updateTitle->update([
            'title_name' => $request->input('title_name'),
            't_sequence' => $request->input('t_sequence')
        ]);

        return redirect()->route('titleSumm')->with('success', 'Title updated successfully.');
    }

    public function deleteTitle($id)
    {
        $title = Title::find($id);

        // Delete all contents associated with each subtitle
        foreach ($title->subtitles as $subtitle) {
            foreach ($subtitle->contents as $content) {
                $content->delete();
            }
            // After deleting all contents, delete the subtitle itself
            $subtitle->delete();
        }

        // Finally, delete the title
        $title->delete();

        return redirect()->back()->with('success', 'Title and associated contents deleted successfully.');
    }

    // public function updateTitle(Request $request)
    // {
    //     $data = $request->input('data');

    //     // Get the list of IDs in the request
    //     $requestTitleIds = collect($data)->pluck('id')->toArray();

    //     // Find titles that are not present in the request
    //     $titlesToDelete = Title::whereNotIn('id', $requestTitleIds)->get();

    //     // Delete titles not present in the request
    //     foreach ($titlesToDelete as $titleToDelete) {
    //         // Delete associated content records
    //         $titleToDelete->contents()->delete();

    //         // Delete the title itself
    //         $titleToDelete->delete();
    //     }

    //     foreach ($data as $row) {
    //         // Validate each row of data
    //         // $validator = Validator::make($row, [
    //         //     'title_name' => 'required|max:255',
    //         // ], [
    //         //     'title_name.max' => 'Title should not exceed 255 characters.',
    //         // ]);

    //         // // Check if validation fails
    //         // if ($validator->fails()) {
    //         //     return response()->json(['error' => $validator->errors()], 400);
    //         // }

    //         $id = $row['id'];
    //         $title = $row['title'];
    //         $t_sequence = $row['tsequence'];

    //         // Check if the record exists
    //         $existingTitle = Title::find($id);

    //         if ($existingTitle) {
    //             // Update the existing record
    //             $existingTitle->update([
    //                 'title_name' => $title,
    //                 't_sequence' => $t_sequence,
    //             ]);
    //         } else {
    //             // Count the number of existing titles
    //             $titleCount = Title::count();

    //             // Create a new record
    //             $newTitle = Title::create([
    //                 'title_name' => $title,
    //                 't_sequence' => $titleCount + 1,
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Title updated successfully']);
    // }


    public function subtitleSumm(Title $title)
    {
        $title->load([
            'subtitles' => function ($query) {
                $query->orderBy('s_sequence');
            }
        ]);

        return view('admin.subtitleSumm', compact('title'));
    }

    public function viewMoreContent($id)
    {
        $contents = Content::where('subtitle_id', $id)->orderBy('c_sequence')->get();

        $subtitle = Subtitle::join('titles', 'titles.id', 'subtitles.title_id')
                            ->where('subtitles.id', $id)
                            ->first();

        return view('admin.viewMoreContent', compact('contents', 'subtitle'));
    }

    public function createSubtitle(Title $title)
    {
        return view('admin.createSubtitle', compact('title'));
    }

    public function addSubtitle(Request $request, Title $title)
    {
        $rules = [
            'subtitle_name' => 'required|max:255',
        ];

        $messages = [
            'subtitle_name.required' => 'The Subtitle field is required.',
            'subtitle_name.max' => 'The Subtitle should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $number = Subtitle::where('title_id', $title->id)
                    ->orderBy('s_sequence', 'desc')->first();

        $newSsequence = $number ? $number->s_sequence + 1 : 1;

        $subtitle = Subtitle::create([
            'subtitle_name' => $request->input('subtitle_name'),
            's_sequence' => $newSsequence,
            'title_id' => $title->id
        ]);

        return redirect()->route('subtitleSumm', ['title' => $title->id])->with('success', 'New subtitle created successfully.');
    }

    public function editSubtitle($id)
    {
        $subtitle = Subtitle::find($id);

        return view('admin.editSubtitle', compact('subtitle'));
    }

    public function updateSubtitle(Request $request, $id)
    {
        $rules = [
            's_sequence' => 'required',
            'subtitle_name' => 'required|max:255',
        ];

        $messages = [
            's_sequence.required' => 'The Sequence field is required.',
            'subtitle_name.required' => 'The Subtitle field is required.',
            'subtitle_name.max' => 'The Subtitle should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateSubtitle = Subtitle::find($id);

        $titleId = $updateSubtitle->title_id;

        $updateSubtitle->update([
            'subtitle_name' => $request->input('subtitle_name'),
            's_sequence' => $request->input('s_sequence')
        ]);

        return redirect()->route('subtitleSumm', ['title' => $titleId])->with('success', 'Subtitle updated successfully.');
    }

    public function deleteSubtitle($id)
    {
        $subtitle = Subtitle::find($id);

        foreach ($subtitle->contents as $content) {
            $content->delete();
        }

        $subtitle->delete();

        return redirect()->back()->with('success', 'Subtitle and associated contents deleted successfully.');
    }

    public function contentSumm(Subtitle $subtitle)
    {
        $subtitle->load([
            'contents' => function ($query) {
                $query->orderBy('c_sequence');
            },
            'contents.documentationImages'
        ]);

        // $content = Content::where('subtitle_id', $subtitle->id)->orderBy('c_sequence')->get();

        return view('admin.contentSumm', compact('subtitle'));
    }

    public function createContent()
    {
        $titles = Title::all();
        $subtitles = Subtitle::with('title')->get();

        return view('admin.createContent', compact('titles', 'subtitles'));
    }

    public function addContent(Request $request)
    {

        $rules = [
            'content_name' => 'required|max:5000',
            'd_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'subtitle_type' => 'required',
        ];

        $messages = [
            'content_name.required' => 'The Content field is required.',
            'content_name.max' => 'The Content should not exceed 5000 characters.',
            'd_image.image' => 'Must be an image format.',
            'd_image.max' => 'The Image should not exceed 2 GB.',
            'subtitle_type' => 'Please select a subtitle type.',
        ];

        $subtitleType = $request->input('subtitle_type');

        if ($subtitleType === 'existing') {
            $rules['subtitle_id'] = 'required';
            $messages['subtitle_id.required'] = 'Please select an existing subtitle.';
        } else if ($subtitleType === 'new') {
            $rules['title_id'] = 'required';
            $rules['subtitle_name'] = 'required';
            $messages['title_id.required'] = 'Please select a title.';
            $messages['subtitle_name.required'] = 'Please enter a new subtitle name.';

        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($subtitleType === 'existing') {
            $subtitleId = $request->input('subtitle_id');
        } else {

            $titleId = $request->input('title_id');
            $subtitleName = $request->input('subtitle_name');

            $subtitleCount = Subtitle::where('title_id', $titleId)->count();
            $s_sequence = $subtitleCount + 1;

            $newSubtitle = Subtitle::create([
                'title_id' => $titleId,
                'subtitle_name' => $subtitleName,
                's_sequence' => $s_sequence
            ]);

            $subtitleId = $newSubtitle->id;

        }

        // Count the number of records with the given title_id
        $count = Content::where('subtitle_id', $subtitleId)->count();

        // Calculate the new sequence value
        $sequence = $count + 1;

        $content = Content::create([
            'subtitle_id' => $subtitleId,
            'content_name' => $request->input('content_name'),
            'c_sequence' => $sequence
        ]);

        if ($request->hasFile('d_image')) {
            $file = $request->file('d_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = 's'. $subtitleId . 'c' . $content->id . '_d_image.' . time() . '.' . $extension;
            $file->move('storage/documentations/', $fileName);

            DocumentationImage::create([
                'content_id' => $content->id,
                'd_image' => $fileName,
            ]);
        }

        return redirect()->route('contentSumm', ['subtitle' => $subtitleId])->with('success', 'New content created successfully.');
    }

    // public function updateContent(Request $request)
    // {
    //     $data = $request->input('data');

    //     $titleId = isset($data[0]['tid']) ? $data[0]['tid'] : null;

    //     // Get the list of IDs in the request
    //     $requestContentIds = collect($data)->pluck('id')->toArray();

    //     // Find titles that are not present in the request
    //     $titlesToDelete = Content::whereNotIn('id', $requestContentIds)
    //                             ->where('title_id', $titleId)
    //                             ->get();

    //     // Delete titles not present in the request
    //     foreach ($titlesToDelete as $titleToDelete) {
    //         $titleToDelete->delete();
    //     }

    //     foreach ($data as $row) {
    //         // Validate each row of data
    //         $validator = Validator::make($row, [
    //             'subtitle' => 'required|max:255',
    //             'content' => 'required|max:255',
    //         ], [
    //             'subtitle.max' => 'Subtitle should not exceed 255 characters.',
    //             'content.max'=> 'Content should not exceed 255 characters.',
    //         ]);

    //         // Check if validation fails
    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }

    //         $c_id = $row['id'];
    //         $t_id = $row['tid'];
    //         $subtitle = $row['subtitle'];
    //         $c_sequence = $row['csequence'];
    //         $content = $row['content'];
    //         // $d_image = $row['d_image'];

    //         // Check if the record exists
    //         $existingContent = Content::find($c_id);

    //         if ($existingContent) {
    //             // Update the existing record
    //             $existingContent->update([
    //                 'subtitle_name' => $subtitle,
    //                 'content_name' => $content,
    //                 'c_sequence' => $c_sequence,
    //             ]);
    //         } else {

    //             $contentCount = Content::where('title_id', $titleId)->count();

    //             $newDocument = Content::create([
    //                 'title_id' => $titleId,
    //                 'subtitle_name' => $subtitle,
    //                 'content_name' => $content,
    //                 'c_sequence' => $contentCount + 1,
    //             ]);
    //         }

    //         // if ($request->hasFile('d_image')) {

    //         //     $file = $request->file('d_image');
    //         //     $extension = $file->getClientOriginalExtension();
    //         //     $fileName = 't'. $t_id . 'd' . $d_id . '_d_image.' . time() . '.' . $extension;
    //         //     $file->move('storage/documentations/', $fileName);

    //         //     DocumentationImage::create([
    //         //         'documentation_id' => $d_id,
    //         //         'image_name' => $fileName,
    //         //     ]);
    //         // }

    //     }

    //     return response()->json(['message' => 'Title updated successfully']);
    // }

    public function editContent($id)
    {
        $content = Content::with('subtitle')
                            ->with('documentationImages')
                            ->find($id);

        $subtitles = Subtitle::with('title')->get();

        return view('admin.editContent', compact('content', 'subtitles'));
    }

    public function updateContent(Request $request, $id)
    {
        $rules = [
            'c_sequence' => 'required|numeric',
            'subtitle_id' => 'required',
            'content_name' => 'required|max:5000',
        ];

        $messages = [
            'c_sequence.required' => 'The Sequence field is required.',
            'subtitle_id.required' => 'The Subtitle field is required.',
            'content_name.required' => 'The Content field is required.',
            'content_name.max' => 'The Content should not exceed 5000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateContent = Content::find($id);

        $subtitle_id = $request->input('subtitle_id');

        $updateContent->update([
            'c_sequence' => $request->input('c_sequence'),
            'subtitle_id' => $request->input('subtitle_id'),
            'content_name' => $request->input('content_name')
        ]);

        if ($request->hasFile('d_image')) {
            $oldDocumentationImage = DocumentationImage::where('content_id', $id)->first();

            $documentationImagePath = 'storage/documentations/';
            $file = $request->file('d_image');
            $extension = $file->getClientOriginalExtension();
            $fileName = 's'. $request->input('subtitle_id') . 'c' . $id . '_d_image.' . time() . '.' . $extension;

            if ($oldDocumentationImage) {
                // Delete old file
                $filesToDelete = glob($documentationImagePath . $oldDocumentationImage->d_image);
                foreach ($filesToDelete as $fileToDelete) {
                    if (File::exists($fileToDelete)) {
                        File::delete($fileToDelete);
                    }
                }

                // Move new file
                $file->move($documentationImagePath, $fileName);

                // Update old documentation image record
                $oldDocumentationImage->update([
                    'd_image' => $fileName,
                ]);
            } else {
                // If no old documentation image exists, simply move the new file and create a new record
                $file->move($documentationImagePath, $fileName);

                DocumentationImage::create([
                    'content_id' => $id,
                    'd_image' => $fileName,
                ]);
            }
        }

        return redirect()->route('contentSumm', ['subtitle' => $subtitle_id])->with('success', 'Content updated successfully.');
    }

    public function deleteContent($id)
    {
        $contents = Content::find($id);

        $contents->delete();

        return redirect()->back()->with('success', 'Content deleted successfully.');
    }

    public function supportCategorySumm()
    {
        $supportCategories = SupportCategory::all();

        return view('admin.supportCategorySumm', compact('supportCategories'));
    }

    public function createCategory()
    {
        return view('admin.createCategory');
    }

    public function addCategory(Request $request)
    {
        $rules = [
            'category_name' => 'required|max:255',
        ];

        $messages = [
            'category_name.required' => 'The Category field is required.',
            'category_name.max' => 'The Category should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category = SupportCategory::create([
            'category_name' => $request->input('category_name')
        ]);

        return redirect()->route('supportCategorySumm')->with('success', 'New category created successfully.');
    }

    public function editCategory($id)
    {
        $category = SupportCategory::find($id);

        return view('admin.editCategory', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $rules = [
            'category_name' => 'required|max:255',
        ];

        $messages = [
            'category_name.required' => 'The Category field is required.',
            'category_name.max' => 'The Category should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateCategory = SupportCategory::find($id);

        $updateCategory->update([
            'category_name' => $request->input('category_name')
        ]);

        return redirect()->route('supportCategorySumm')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = SupportCategory::find($id);

        foreach ($category->supportSubCategories as $subcategory) {
            $subcategory->delete();
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category and associated subcategory deleted successfully.');
    }

    // public function updateCategory(Request $request)
    // {
    //     $data = $request->input('data');

    //     // Get the list of IDs in the request
    //     $requestCategoryIds = collect($data)->pluck('id')->toArray();

    //     $categoriesToDelete = SupportCategory::whereNotIn('id', $requestCategoryIds)->get();

    //     foreach ($categoriesToDelete as $categoryToDelete) {
    //         $categoryToDelete->supportSubCategories()->delete();

    //         $categoryToDelete->delete();
    //     }

    //     foreach ($data as $row) {
    //         // Validate each row of data
    //         // $validator = Validator::make($row, [
    //         //     'category_name' => 'required|max:255',
    //         // ], [
    //         //     'category_name.max' => 'Category name should not exceed 255 characters.',
    //         // ]);

    //         // // Check if validation fails
    //         // if ($validator->fails()) {
    //         //     return response()->json(['error' => $validator->errors()], 400);
    //         // }

    //         $id = $row['id'];
    //         $category_name = $row['categoryname'];

    //         // Check if the record exists
    //         $existingCategory = SupportCategory::find($id);

    //         if ($existingCategory) {
    //             // Update the existing record
    //             $existingCategory->update([
    //                 'category_name' => $category_name,
    //             ]);
    //         } else {

    //             // Create a new record
    //             $newCategory = SupportCategory::create([
    //                 'category_name' => $category_name,
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Category updated successfully']);
    // }

    public function supportSubSumm(SupportCategory $supportCategory)
    {
        $supportCategory->load([
            'supportSubCategories',
            'supportSubCategories.contents',
            'supportSubCategories.contents.subtitle',
            'supportSubCategories.contents.subtitle.title'
        ]);

        return view('admin.supportSubSumm', compact('supportCategory'));
    }

    public function createSub(SupportCategory $supportCategory)
    {
        // $contents = Content::with('subtitle')->get();

        $contents = Content::join('subtitles', 'contents.subtitle_id', 'subtitles.id')
                            ->join('titles', 'subtitles.title_id', 'titles.id')
                            ->get();

        return view('admin.createSub', compact('supportCategory', 'contents'));
    }

    public function addSub(Request $request, SupportCategory $supportCategory)
    {
        $rules = [
            'sub_name' => 'required|max:255',
            'sub_description' => 'required|max:255',
            'content_id' => 'required'
        ];

        $messages = [
            'sub_name.required' => 'The Subcategory Name field is required.',
            'sub_name.max' => 'The Subcategory Name field should not exceed 255 characters.',
            'sub_description.required' => 'The Description field is required.',
            'sub_description.max' => 'The Description should not exceed 255 characters.',
            'content_id.required' => 'Related Topic is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $createSub = SupportSubCategory::create([
            'category_id' => $supportCategory->id,
            'sub_name' => $request->input('sub_name'),
            'sub_description' => $request->input('sub_description'),
            'content_id' => $request->input('content_id')
        ]);

        // return redirect()->route('createSub', ['supportCategory' => $supportCategory]);
        return redirect()->route('supportSubSumm', ['supportCategory' => $supportCategory->id])->with('success', 'New subcategory created successfully.');
    }

    public function editSub($id)
    {
        $supportSubCategories = SupportSubCategory::find($id);
        $contents = Content::join('subtitles', 'contents.subtitle_id', 'subtitles.id')
                            ->join('titles', 'subtitles.title_id', 'titles.id')
                            ->get();

        return view('admin.editSub', compact('supportSubCategories', 'contents'));
    }

    public function updateSub(Request $request, $id)
    {
        $rules = [
            'sub_name' => 'required|max:255',
            'sub_description' => 'required|max:255',
            'content_id' => 'required'
        ];

        $messages = [
            'sub_name.required' => 'The Subcategory Name field is required.',
            'sub_name.max' => 'The Subcategory Name should not exceed 255 characters.',
            'sub_description.required' => 'The Description field is required.',
            'sub_description.max' => 'Description should not exceed 255 characters.',
            'content_id.required' => 'Related Topic is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateSubCat = SupportSubCategory::find($id);

        $categoryId = $updateSubCat->category_id;

        $updateSubCat->update([
            'sub_name' => $request->input('sub_name'),
            'sub_description' => $request->input('sub_description'),
            'content_id' => $request->input('content_id')
        ]);

        return redirect()->route('supportSubSumm', ['supportCategory' => $categoryId]);
    }

    public function deleteSub($id)
    {
        $deleteSub = SupportSubCategory::find($id);

        $deleteSub->delete();

        return redirect()->back()->with('success', 'Support SubCategories deleted successfully.');
    }

    // public function updateSub(Request $request)
    // {
    //     $data = $request->input('data');

    //     $firstCatId = isset($data[0]['catid']) ? $data[0]['catid'] : null;

    //     // Get the list of IDs in the request
    //     $requestSubCategoryIds = collect($data)->pluck('id')->toArray();

    //     $subCategoriesToDelete = SupportSubCategory::whereNotIn('id', $requestSubCategoryIds)->get();

    //     foreach ($subCategoriesToDelete as $subCategoryToDelete) {
    //         $subCategoryToDelete->delete();
    //     }

    //     foreach ($data as $row) {
    //         // Validate each row of data
    //         // $validator = Validator::make($row, [
    //         //     'category_name' => 'required|max:255',
    //         // ], [
    //         //     'category_name.max' => 'Category name should not exceed 255 characters.',
    //         // ]);

    //         // // Check if validation fails
    //         // if ($validator->fails()) {
    //         //     return response()->json(['error' => $validator->errors()], 400);
    //         // }

    //         $catId = $row['catid'];
    //         $subId = $row['subid'];
    //         $subName = $row['subname'];
    //         $subDesc = $row['subdesc'];

    //         // Check if the record exists
    //         $existingSubcategory = SupportSubCategory::find($subId);

    //         if ($existingSubcategory) {
    //             // Update the existing record
    //             $existingSubcategory->update([
    //                 'sub_name' => $subName,
    //                 'sub_description' => $subDesc
    //             ]);
    //         } else {

    //             // Create a new record
    //             $newSubcategory = SupportSubCategory::create([
    //                 'category_id' => $firstCatId,
    //                 'sub_name' => $subName,
    //                 'sub_description' => $subDesc,
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Category updated successfully']);
    // }


    public function ticketStatus()
    {
        $ticketStatuses = TicketStatus::all();

        return view('admin.ticketStatus', compact('ticketStatuses'));
    }

    public function createTicketStatus()
    {
        return view('admin.createTicketStatus');
    }

    public function addTicketStatus(Request $request)
    {
        $rules = [
            'status' => 'required|max:255',
        ];

        $messages = [
            'status.required' => 'Status is required.',
            'status.max' => 'Status should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticketStatus = TicketStatus::create([
            'status' => $request->input('status'),
        ]);

        return redirect()->route('ticketStatus')->with('success', 'New status created successfully');
    }

    public function editTicketStatus($id)
    {
        $ticketStatus = TicketStatus::find($id);

        return view('admin.editTicketStatus', compact('ticketStatus'));
    }

    public function updateTicketStatus(Request $request, $id)
    {
        $rules = [
            'status' => 'required|max:255',
        ];

        $messages = [
            'status.required' => 'Status is required.',
            'status.max' => 'Status should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $updateTicketStatus = TicketStatus::find($id);

        $updateTicketStatus->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->route('ticketStatus')->with('success', 'Ticket Status updated successfully.');
    }

    public function deleteTicketStatus($id)
    {
        $ticketStatus = TicketStatus::find($id);

        $ticketStatus->delete();

        return redirect()->back()->with('success', 'Ticket Status deleted successfully.');
    }

    // public function updateTicketStatus(Request $request)
    // {
    //     $data = $request->input('data');

    //     // Get the list of IDs in the request
    //     $requestStatusIds = collect($data)->pluck('id')->toArray();

    //     $statusesToDelete = TicketStatus::whereNotIn('id', $requestStatusIds)->get();

    //     foreach ($statusesToDelete as $statusToDelete) {
    //         $statusToDelete->delete();
    //     }

    //     foreach ($data as $row) {
    //         // Validate each row of data
    //         // $validator = Validator::make($row, [
    //         //     'category_name' => 'required|max:255',
    //         // ], [
    //         //     'category_name.max' => 'Category name should not exceed 255 characters.',
    //         // ]);

    //         // // Check if validation fails
    //         // if ($validator->fails()) {
    //         //     return response()->json(['error' => $validator->errors()], 400);
    //         // }

    //         $ticket_status_id = $row['id'];
    //         $status = $row['status'];

    //         // Check if the record exists
    //         $existingTicketStatus = TicketStatus::find($ticket_status_id);

    //         if ($existingTicketStatus) {
    //             // Update the existing record
    //             $existingTicketStatus->update([
    //                 'status' => $status,
    //             ]);
    //         } else {

    //             // Create a new record
    //             $newTicketStatus = TicketStatus::create([
    //                 'status' => $status,
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Ticket status updated successfully']);
    // }

    public function adminSumm()
    {
        $users = User::select('users.*', 'roles.*', 'support_categories.*', 'users.id as user_id', 'roles.id as role_id', 'support_categories.id as support_categories_id')
                        ->join('roles', 'roles.id', 'users.role_id')
                        ->leftJoin('support_categories', 'support_categories.id', 'users.category_id')
                        ->get();

        return view('admin.adminSumm', compact('users'));
    }

    public function createAdmin()
    {
        $roles = Role::where('role_name', '!=', 'Super Admin')->get();
        $supportCategories = SupportCategory::all();

        return view('admin.createAdmin', compact('roles', 'supportCategories'));
    }

    public function addAdmin(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required',
            'category_id' => 'required',
            'role_id' => 'required'
        ];

        $messages = [
            'name.required' => 'The Name field is required.',
            'username.required' => 'The Username field is required.',
            'email.required' => 'The Email field is required.',
            'name.max' => 'The Name should not exceed 255 characters.',
            'username.max' => 'The Username should not exceed 255 characters.',
            'email.max' => 'The Email should not exceed 255 characters.',
            'password.required' => 'The Password field is required.',
            'category_id.required' => 'Please select a category.',
            'role_id.required' => 'Role is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $hashedPassword = Hash::make($request->input('password'));

        $admin = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'password' => $hashedPassword,
            'category_id' => $request->input('category_id'),
            'manage_ticket_in_category' => $request->has('manage_ticket_in_category') ? 1 : 0,
            'manage_own_ticket' => $request->has('manage_own_ticket') ? 1 : 0,
            'manage_title' => $request->has('manage_title') ? 1 : 0,
            'manage_subtitle' => $request->has('manage_subtitle') ? 1 : 0,
            'manage_content' => $request->has('manage_content') ? 1 : 0,
            'manage_support_category' => $request->has('manage_support_category') ? 1 : 0,
            'manage_support_subcategory' => $request->has('manage_support_subcategory') ? 1 : 0,
            'manage_status' => $request->has('manage_status') ? 1 : 0,
        ]);

        return redirect()->route('adminSumm')->with('success', 'New admin created successfully');
    }

    public function editAdmin($id)
    {
        $user = User::find($id);
        $roles = Role::where('role_name', '!=', 'Super Admin')->get();
        $supportCategories = SupportCategory::all();

        return view('admin.editAdmin', compact('user', 'roles', 'supportCategories'));
    }

    public function updateAdmin(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|max:255',
            'category_id' => 'required',
            'role_id' => 'required'
        ];

        $messages = [
            'name.required' => 'The Name field is required.',
            'username.required' => 'The Username field is required.',
            'email.required' => 'The Email field is required.',
            'name.max' => 'The Name should not exceed 255 characters.',
            'username.max' => 'The Username should not exceed 255 characters.',
            'email.max' => 'The Email should not exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'role_id.required' => 'Role is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $updateAdmin = User::find($id);

        $updateAdmin->name = $request->input('name');
        $updateAdmin->username = $request->input('username');
        $updateAdmin->email = $request->input('email');
        $updateAdmin->role_id = $request->input('role_id');
        $updateAdmin->category_id = $request->input('category_id');
        $updateAdmin->manage_ticket_in_category = $request->has('manage_ticket_in_category') ? 1 : 0;
        $updateAdmin->manage_own_ticket = $request->has('manage_own_ticket') ? 1 : 0;
        $updateAdmin->manage_title = $request->has('manage_title') ? 1 : 0;
        $updateAdmin->manage_subtitle = $request->has('manage_subtitle') ? 1 : 0;
        $updateAdmin->manage_content = $request->has('manage_content') ? 1 : 0;
        $updateAdmin->manage_support_category = $request->has('manage_support_category') ? 1 : 0;
        $updateAdmin->manage_support_subcategory = $request->has('manage_support_subcategory') ? 1 : 0;
        $updateAdmin->manage_status = $request->has('manage_status') ? 1 : 0;

        // if ($roleId == 1) {
        //     $updateAdmin->category_id = 0;
        // }

        // if ($roleId != 1 &&
        //     $request->has('manage_title') &&
        //     $request->has('manage_subtitle') &&
        //     $request->has('manage_content') &&
        //     $request->has('manage_support_category') &&
        //     $request->has('manage_support_subcategory') &&
        //     $request->has('manage_status')) {

        //     // Set role_id to 1
        //     $updateAdmin->role_id = 1;
        // }

        // if ($roleId !== null) {
        //     $user = User::find($id);

        //     // Check if the user is found and the category_id matches
        //     if ($user && $roleId !== 0) {
        //         $validator->errors()->add('category_id', 'The selected category should not be "All" when role is not Super Admin.');
        //         return redirect()
        //             ->back()
        //             ->withErrors($validator)
        //             ->withInput();
        //     }
        // }

        $updateAdmin->save();

        if ($request->filled('newpassword')) {
            $updateAdmin->password = Hash::make($request->input('newpassword'));
            $updateAdmin->save();

        }

        return redirect()->route('adminSumm')->with('success', 'Admin updated successfully');
    }

    public function deleteAdmin($id)
    {
        $user = User::find($id);

        $user->delete();

        return redirect()->back()->with('success', 'Admin deleted successfully.');
    }

}
