<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendNote;
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
use App\Models\Note;
use App\Models\EmailSignature;
use App\Models\Project;
use App\Models\TicketLog;
use App\Models\Enhancement;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Version;

class AdminController extends Controller
{

    public function profile()
    {
        $user = User::where('id', '=', Auth::user()->id)->first();
        $supportCategories = SupportCategory::all();

        $profile_picture = $user->profile_picture;

        return view('admin.profile', compact('user', 'supportCategories', 'profile_picture'));
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

        $full_name_with_underscores = str_replace(' ', '_', $request->input('name'));

        $photoPath = 'storage/profilePicture/';

        // Check if a new profile picture has been uploaded
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_profile_picture.' . $extension; // Modify the file name

            // Delete all previous files with the same full name
            $filesToDelete = glob($photoPath . $full_name_with_underscores . '_profile_picture.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }

            // Upload the new profile picture
            $file->move($photoPath, $filename);
        } else {
            // If no new file uploaded, retain the old profile picture filename
            $filename = $updateAdmin->profile_picture;
        }

        $updateAdmin->profile_picture = $filename;

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

    public function deleteProfilePicture(Request $request)
    {
        $imageName = $request->input('imageUrl');

        $photoPath = 'storage/profilePicture/';

        // Delete all previous files with the same full name
        $filesToDelete = glob($photoPath . $imageName);
        foreach ($filesToDelete as $fileToDelete) {
            if (File::exists($fileToDelete)) {
                File::delete($fileToDelete);
            }
        }

        $user = User::where('profile_picture', $imageName)->first();
        if ($user) {
            $user->profile_picture = null; // Set the profile picture column to null
            $user->save();
        }

        return response()->json(['message' => 'Profile picture deleted successfully']);
    }

    public function emailSignature()
    {
        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();
        $profile_picture = $user->profile_picture;

        return view('admin.emailSignature', compact('user', 'emailSignature', 'profile_picture'));

        // return view('admin.emailSignature');
    }

    public function getEmailSignature()
    {
        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();

        $response = [
            'user' => $user,
            'emailSignature' => $emailSignature,
        ];

        return response()->json($response);
    }

    public function updateEmailSignature(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        $updateEmailData = [];

        $updateUserData = [];

        if ($request->has('sign_off')) {
            $updateEmailData['sign_off'] = $request->input('sign_off');
        }

        if ($request->has('font_family')) {
            $updateEmailData['font_family'] = $request->input('font_family');
        }

        if ($request->has('font_size')) {
            $updateEmailData['font_size'] = $request->input('font_size');
        }

        if ($request->has('font_color')) {
            $updateEmailData['font_color'] = $request->input('font_color');
        }

        if ($request->has('name')) {
            $updateUserData['name'] = $request->input('name');
        }

        if ($request->has('phone_number')) {
            $updateUserData['phone_number'] = $request->input('phone_number');
        }

        if ($request->has('position')) {
            $updateUserData['position'] = $request->input('position');
        }

        if ($request->has('email')) {
            $updateUserData['email'] = $request->input('email');
        }

        if ($request->has('whatsapp_me')) {
            $updateUserData['whatsapp_me'] = $request->input('whatsapp_me');
        }

        if ($request->has('telegram_username')) {
            $updateUserData['telegram_username'] = $request->input('telegram_username');
        }

        $updateEmail = EmailSignature::where('user_id', $userId)->update($updateEmailData);

        $updateUser = User::where('id', $userId)->update($updateUserData);

        return response()->json(['message' => 'Email Signature updated successfully']);
    }

    public function adminDashboard(Request $request)
    {
        $ticketStatuses = TicketStatus::where('status', '!=', 'Solved')
                                        ->get();

        // $ticketStatuses = TicketStatus::all();

        $ticketCounts = [];
        $currentYear = now()->year;
        $authUser = User::where('id', '=', Auth::user()->id)->first();

        foreach ($ticketStatuses as $status) {
            // Get ticket counts for each priority within the status

            if ($authUser->role_id == 1) {
                $tickets = Ticket::where('status_id', $status->id)
                ->whereYear('created_at', $currentYear)
                ->whereNull('deleted_at')
                ->get();
            } else if ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
                $tickets = Ticket::where('status_id', $status->id)
                ->where('category_id', $authUser->category_id)
                ->whereYear('created_at', $currentYear)
                ->whereNull('deleted_at')
                ->get();
            } else if ($authUser->role_id !== 1 && $authUser->manage_own_ticket == 1) {
                $tickets = Ticket::where('status_id', $status->id)
                ->where('pic_id', $authUser->id)
                ->whereYear('created_at', $currentYear)
                ->whereNull('deleted_at')
                ->get();
            }


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

        if ($authUser->role_id == 1) {
            $unassignedTickets = Ticket::whereYear('created_at', $currentYear)
                                        ->whereNull('pic_id')
                                        ->orderByDesc('tickets.id')
                                        ->get();
        } else if ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
            $unassignedTickets = Ticket::whereYear('created_at', $currentYear)
                                        ->whereNull('pic_id')
                                        ->where('category_id', $authUser->category_id)
                                        ->orderByDesc('tickets.id')
                                        ->get();
        } else if ($authUser->role_id !== 1 && $authUser->manage_own_ticket == 1) {
            $unassignedTickets = Ticket::whereYear('created_at', $currentYear)
                                        ->whereNull('pic_id')
                                        ->where('category_id', $authUser->category_id)
                                        ->orderByDesc('tickets.id')
                                        ->get();
        }

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

        if ($request->has('year_project')) {
            $selectedYearProject = $request->year_project;
        } else {
            $selectedYearProject = $currentYear;
        }

        // $ticketsByStatus = Ticket::select(
        //                 DB::raw('MONTH(tickets.created_at) AS month'),
        //                 'ticket_statuses.status',
        //                 DB::raw('COUNT(*) AS ticket_count')
        //             )
        //             ->join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id')
        //             ->join(DB::raw('(SELECT DISTINCT status_id FROM tickets) AS distinct_statuses'), function ($join) {
        //                 $join->on('tickets.status_id', '=', 'distinct_statuses.status_id');
        //             })
        //             ->whereYear('tickets.created_at', $selectedYearStatus)
        //             ->groupBy('month', 'ticket_statuses.status')
        //             ->orderBy('month')
        //             ->orderBy('ticket_statuses.status')
        //             ->get();

        $ticketsByStatus = Ticket::select(
                        DB::raw('MONTH(tickets.created_at) AS month'),
                        'projects.project_name',
                        DB::raw('COUNT(*) AS ticket_count')
                    )
                    ->join('projects', 'tickets.project_id', '=', 'projects.id')
                    ->join(DB::raw('(SELECT DISTINCT project_id FROM tickets) AS distinct_projects'), function ($join) {
                        $join->on('tickets.project_id', '=', 'distinct_projects.project_id');
                    })
                    ->whereYear('tickets.created_at', $selectedYearStatus)
                    ->groupBy('month', 'projects.project_name')
                    ->orderBy('month')
                    ->orderBy('projects.project_name')
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

        $salesByProject = Order::select(
                        DB::raw('MONTH(orders.created_at) AS month'),
                        'projects.project_name',
                        DB::raw('SUM(grand_total) AS sales')
                    )
                    ->join('projects', 'orders.project_id', '=', 'projects.id')
                    ->join(DB::raw('(SELECT DISTINCT id AS project_id FROM projects) AS distinct_projects'), function ($join) {
                        $join->on('orders.project_id', '=', 'distinct_projects.project_id');
                    })
                    ->whereYear('orders.created_at', $selectedYearProject)
                    ->groupBy('month', 'projects.project_name')
                    ->orderBy('month')
                    ->orderBy('projects.project_name')
                    ->get();


        return view('admin.adminDashboard', compact('ticketStatuses', 'ticketCounts', 'currentYear', 'ticketsByStatus', 'ticketsByCategory', 'salesByProject','unassignedTickets','totalUnassigned', 'unassignedHigh', 'unassignedMedium', 'unassignedLow', 'authUser'));
    }

    public function helpdesk(Request $request)
    {
        $tickets = Ticket::with('supportCategories', 'ticketImages')->get();
        $categories = SupportCategory::all();
        $projects = Project::all();

        return view('admin.helpdesk', compact('tickets','categories', 'projects'));
    }

    public function getTicket(Request $request)
    {
        $category_id = $request->input('category_id');
        $priority = $request->input('priority');
        $date = $request->input('filter_date');
        $project_id = $request->input('project_id');
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
        ->leftJoin('projects', 'projects.id', 'tickets.project_id')
        ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*', 'users.*','users.id as pic_id','tickets.id as ticket_id', 'tickets.created_at as t_created_at', 'tickets.category_id', 'projects.*', 'projects.id as project_id')
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

        if (isset($project_id) && !empty($project_id)) {
            $query->where('tickets.project_id', $project_id);
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
                    ->orWhere('ticket_statuses.status', 'LIKE', "%$searchTerm%")
                    ->orWhere('tickets.pic_id', 'LIKE', "%$searchTerm%")
                    ->orWhere('users.name', 'LIKE', "%$searchTerm%")
                    ->orWhere('projects.project_name', 'LIKE', "%$searchTerm%");
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
        ->leftJoin('projects', 'tickets.project_id', 'projects.id')
        ->select('tickets.*', 'support_categories.category_name', 'users.name', 'ticket_statuses.status', 'projects.project_name')
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

        // Check if a record exists in the TicketLog table for the given ticketId
        $ticketLog = TicketLog::where('ticket_id', $ticketId)->first();

        if (!$ticketLog) {
            // If no record exists, create a new one
            $ticketLog = new TicketLog();
            $ticketLog->ticket_id = $ticketId;
            $ticketLog->ticket_no = $ticket->ticket_no;
            $ticketLog->save();
        } else {
            // If a record exists, update the created_at timestamp
            $ticketLog->touch();
        }

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

    public function projectTicket(Project $project)
    {
        $tickets = Ticket::with('ticketStatus', 'users')
            ->where('project_id', $project->id)
            ->get();

        return view('admin.projectTicket', compact('project', 'tickets'));
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

    public function unassignedTicket()
    {
        $unassignedTickets = Ticket::whereNull('pic_id')
                                ->orderByDesc('tickets.id')
                                ->get();

        $authUser = User::where('id', '=', Auth::user()->id)->first();

        if ($authUser->role_id == 1) {
            $unassignedTickets = Ticket::whereNull('pic_id')
            ->orderByDesc('tickets.id')
            ->get();
        } else if ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
            $unassignedTickets = Ticket::whereNull('pic_id')
            ->where('category_id', $authUser->category_id)
            ->orderByDesc('tickets.id')
            ->get();
        } else if ($authUser->role_id !== 1 && $authUser->manage_own_ticket == 1) {
            $unassignedTickets = Ticket::whereNull('pic_id')
            ->where('category_id', $authUser->category_id)
            ->orderByDesc('tickets.id')
            ->get();
        }

        return view('admin.unassignedTicket', compact('unassignedTickets'));
    }

    public function viewTicket($id)
    {
        $ticket = Ticket::find($id);
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();
        $ticketImages = TicketImage::where('ticket_id', $id)
                                ->get();

        $projects = Project::all();

        $users = User::select('users.id', 'users.name', 'support_categories.category_name')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('support_categories', 'users.category_id', '=', 'support_categories.id')
                    ->where('roles.role_name', '!=', 'Super Admin')
                    ->get();

        $notes = Note::where('ticket_id', $id)
                    ->orderByDesc('created_at')
                    ->get();

        return view('admin.viewTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages', 'users', 'notes', 'projects'));
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
        $projects = Project::all();

        return view('admin.createTicket', compact('supportCategories', 'ticketStatuses', 'users', 'projects'));
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
            'status_id' => 2,
            'project_id' => $request->input('project_id')
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
        $projects = Project::all();
        $users = User::select('users.id', 'users.name', 'support_categories.category_name')
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->join('support_categories', 'users.category_id', '=', 'support_categories.id')
                    ->where('roles.role_name', '!=', 'Super Admin')
                    ->get();

        $notes = Note::where('ticket_id', $id)
                    ->orderByDesc('created_at')
                    ->get();

        return view('admin.editTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages', 'users', 'notes', 'projects'));
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

        // Update ticket details
        $updateData = [
            'ticket_no' => $request->input('ticket_no'),
            'sender_name' => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
            'status_id' => $status_id, // Update status_id with input value
            'pic_id' => $picId,
            'project_id' => $request->input('project_id')
        ];

        // If status_id is 1, update it to 2
        if ($status_id == 1) {
            $updateData['status_id'] = 2;
        }

        $updateTicket->update($updateData);

        // return redirect()->route('ticketSumm', ['status' => $status_id])->with('success', 'Ticket updated successfully.');
        return redirect()->route('helpdesk')->with('success', 'Ticket updated successfully.');
    }

    public function deleteTicket($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();

        return redirect()->back()->with('success', 'Ticket deleted successfully.');
    }

    public function addNote(Request $request)
    {
        $rules = [
            'note_title' => 'required|max:255',
            'note_description' => 'required|max:5000',
        ];

        $messages = [
            'note_title.required' => 'The Title field is required.',
            'note_description.required' => 'The Description field is required.',
            'note_title.max' => 'The Title should not exceed 255 characters.',
            'note_description.max' => 'The Description should not exceed 5000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticketId = $request->input('ticket_id');

        // Find the ticket
        $ticket = Ticket::find($ticketId);

        // Get the ticket number
        $ticketNo = $ticket->ticket_no;

        // Get sender email
        $senderEmail = $ticket->sender_email;

         // Get sender name
         $ticketSender = $ticket->sender_name;

        // Check if there are existing notes for this ticket
        $existingNotesCount = Note::where('ticket_id', $ticketId)->count();

        // Determine the note number
        if ($existingNotesCount == 0) {
            // If no existing notes, set the note number to ticket_no-00001
            $noteNo = $ticketNo . '-00001';
        } else {
            // If there are existing notes, find the latest note number and increment it
            $latestNoteNo = Note::where('ticket_id', $ticketId)->latest()->value('note_no');
            $latestNoteNoNumber = intval(substr($latestNoteNo, -5)); // Extract the numeric part
            $nextNoteNoNumber = $latestNoteNoNumber + 1;
            $noteNo = $ticketNo . '-' . str_pad($nextNoteNoNumber, 5, '0', STR_PAD_LEFT);
        }

        $noteTitle = $request->input('note_title');
        $noteDescription = $request->input('note_description');

        $note = Note::create([
            'note_no' => $noteNo,
            'note_title' => $noteTitle,
            'note_description' => $noteDescription,
            'ticket_id' => $ticketId,
            'sent' => 0
        ]);

        $emailSubject = $ticketNo . '-' . $ticketSender . '[' . $noteTitle . ']';

        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();

        if ($request->input('action') === 'save_and_send_email') {
            // Send email
            Mail::send(new SendNote($note, $noteTitle, $senderEmail, $emailSubject, $user, $emailSignature));

            $note->update(['sent' => 1]);
        }

        return redirect()->route('viewTicket', ['id' => $ticketId])->with('success', 'Note created successfully.');
    }

    public function editNote($id)
    {
        $note = Note::find($id);

        $response = [
            'note' => $note
        ];

        return response()->json($response);
    }

    public function updateNote(Request $request, $id)
    {

        $noteId = $request->input('note_id');

        $rules = [
            'note_title' => 'required|max:255',
            'note_description' => 'required|max:5000',
        ];

        $messages = [
            'note_title.required' => 'The Title field is required.',
            'note_description.required' => 'The Description field is required.',
            'note_title.max' => 'The Title should not exceed 255 characters.',
            'note_description.max' => 'The Description should not exceed 5000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $ticketId = $request->input('ticket_id');

        // Find the ticket
        $ticket = Ticket::find($ticketId);


        // Get sender email
        $senderEmail = $ticket->sender_email;

        // Get ticket no
        $ticketNo = $ticket->ticket_no;

        // Get sender name
        $ticketSender = $ticket->sender_name;

        $noteTitle = $request->input('note_title');
        $noteDescription = $request->input('note_description');

        $note = Note::find($noteId);

        $note->note_title = $noteTitle;
        $note->note_description = $noteDescription;
        $note->save();

        $emailSubject = $ticketNo . '-' . $ticketSender . '[' . $noteTitle . ']';

        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();

        if ($request->input('action') === 'save_and_send_email') {
            // Send email
            $note->sent = 1;
            $note->save();

            Mail::send(new SendNote($note, $noteTitle, $senderEmail, $emailSubject, $user, $emailSignature));
        }

        return redirect()->route('editTicket', ['id' => $ticketId])->with('success', 'Note updated successfully.');
    }

    public function deleteNote($id)
    {
        $note = Note::find($id);
        $note->delete();

        return response()->json(['message' => 'Note deleted successfully.']);
    }

    public function performance()
    {
        // $authUser = User::where('id', '=', Auth::user()->id)->first();

        // if ($authUser->role_id == 1) {

        //     $supportCategories = SupportCategory::all();

        //     foreach ($supportCategories as $supportCategory) {
        //         // Retrieve users associated with the current support category
        //         $users = $supportCategory->users()->withCount('tickets')->get();

        //         // Assign the users back to the support category
        //         $supportCategory->users = $users;
        //     }

        // } elseif ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
        //     $supportCategories = SupportCategory::where('support_categories.id', $authUser->category_id)
        //                                         ->get();

        //     foreach ($supportCategories as $supportCategory) {
        //         // Retrieve users associated with the current support category
        //         $users = $supportCategory->users()->withCount('tickets')->get();

        //         // Assign the users back to the support category
        //         $supportCategory->users = $users;
        //     }
        // }

        // $ticketStatuses = TicketStatus::all();

        // // Loop through each support category
        // foreach ($supportCategories as $supportCategory) {
        //     // Loop through each user in the support category
        //     foreach ($supportCategory->users as $user) {
        //         // Initialize a temporary array to hold ticket counts
        //         $ticketCounts = [];

        //         // Fetch ticket counts for each ticket status for the current user
        //         foreach ($ticketStatuses as $status) {
        //             $ticketCounts[$status->status] = $user->tickets()->where('status_id', $status->id)->count();
        //         }

        //         // Assign the temporary array to a different property of the $user object
        //         $user->ticketCounts = $ticketCounts;
        //     }
        // }
        // return view('admin.performance', compact('supportCategories', 'ticketStatuses'));
        return view('admin.performance');
    }

    public function getPerformance(Request $request)
    {
        $authUser = User::where('id', '=', Auth::user()->id)->first();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $year = $request->input('filter_year');
        $month = $request->input('filter_month');
        $monthNumber = date('m', strtotime($month));

        if ($authUser->role_id == 1) {
            // Fetch all support categories
            $supportCategories = SupportCategory::all();
        } else if ($authUser->role_id !== 1 && $authUser->manage_ticket_in_category == 1) {
            // Fetch all support categories
            $supportCategories = SupportCategory::where('id', $authUser->category_id)->get();
        }

        // Fetch all ticket statuses
        $ticketStatuses = TicketStatus::all();

        // Loop through each support category
        foreach ($supportCategories as $supportCategory) {
            // Retrieve users associated with the current support category along with their tickets
            $users = $supportCategory->users()->with('tickets.ticketStatus')->get();

            // Loop through each user in the support category
            foreach ($users as $user) {
                // Initialize an array to hold ticket counts for each status
                $ticketCounts = [];

                if ($request->has('filter_month') && $request->has('filter_year')) {
                    // Loop through each ticket status
                    foreach ($ticketStatuses as $status) {
                        // Count tickets with the current status for the current user
                        $ticketCounts[$status->status] = $user->tickets()
                                                    ->where('status_id', $status->id)
                                                    ->whereMonth('created_at', $monthNumber)
                                                    ->whereYear('created_at', $year)
                                                    ->count();
                    }

                    // Calculate the total ticket count for the user
                    $totalTickets = $user->tickets()
                                    ->whereMonth('created_at', $monthNumber)
                                    ->whereYear('created_at', $year)
                                    ->count();

                } else {
                    // Loop through each ticket status
                    foreach ($ticketStatuses as $status) {
                        // Count tickets with the current status for the current user
                        $ticketCounts[$status->status] = $user->tickets()
                                                    ->where('status_id', $status->id)
                                                    ->whereMonth('created_at', $currentMonth)
                                                    ->whereYear('created_at', $currentYear)
                                                    ->count();
                    }

                    // Calculate the total ticket count for the user
                    $totalTickets = $user->tickets()
                                    ->whereMonth('created_at', $currentMonth)
                                    ->whereYear('created_at', $currentYear)
                                    ->count();
                }

                // Assign the ticket counts and total ticket count to the user object
                $user->ticketCounts = $ticketCounts;
                $user->totalTickets = $totalTickets;
            }

            // Assign the users back to the support category
            $supportCategory->users = $users;
        }


        // Define the colors array
        $colors = ['#bed3fe', '#e3e6f0', '#b8f4db', '#bde6fa', '#ffebc1', '#99a1b7', '#b2bfc2'];

        $response = [
            'supportCategories' => $supportCategories,
            'ticketStatuses' => $ticketStatuses,
            'colors' => $colors,
        ];

        return response()->json($response);
    }

    public function viewPerformance($id)
    {
        $tickets = Ticket::with('projects')->where('pic_id', $id)->get();
        $users = User::find($id);


        return view('admin.viewPerformance', compact('tickets', 'users'));
    }

    public function projectSumm()
    {
        $projects = Project::all();

        return view('admin.projectSumm', compact('projects'));
    }


    public function addProject(Request $request)
    {
        $rules = [
            'project_name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'project_owner' => 'nullable|max:255',
            'project_telno' => 'nullable|max:255',
            'project_address' => 'nullable|max:255',
        ];

        $messages = [
            'project_name.required' => 'The Project Name field is required.',
            'project_name.max' => 'The Project Name should not exceed 255 characters.',
            // 'description.required' => 'The Description field is required.',
            'description.max' => 'The Description should not exceed 255 characters.',
            'project_owner.max' => 'The Project Owner should not exceed 255 characters.',
            'project_telno.max' => 'The Project Phone Number should not exceed 255 characters.',
            'project_address.max' => 'The Project Address should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::create([
            'project_name' => $request->input('project_name'),
            'description' => $request->input('description'),
            'show' => $request->input('show'),
            'project_owner' => $request->input('project_owner'),
            'project_telno' => $request->input('project_telno'),
            'project_address' => $request->input('project_address')
        ]);

        return redirect()->route('projectSumm')->with('success', 'New project created successfully.');
    }

    public function editProject($id)
    {
        $project = Project::find($id);

        // dd($project);
        // return view('admin.editProject', compact('project'));

        $response = [
            'project' => $project,
        ];

        return response()->json($response);
    }

    public function updateProject(Request $request, $id)
    {
        $rules = [
            'project_name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'project_owner' => 'nullable|max:255',
            'project_telno' => 'nullable|max:255',
            'project_address' => 'nullable|max:255',
        ];

        $messages = [
            'project_name.required' => 'The Project Name field is required.',
            'project_name.max' => 'The Project Name should not exceed 255 characters.',
            // 'description.required' => 'The Description field is required.',
            'description.max' => 'The Description should not exceed 255 characters.',
            'project_owner.max' => 'The Project Owner should not exceed 255 characters.',
            'project_telno.max' => 'The Project Phone Number should not exceed 255 characters.',
            'project_address.max' => 'The Project Address should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateProject = Project::find($request->input('id'));

        $updateProject->update([
            'project_name' => $request->input('project_name'),
            'description' => $request->input('description'),
            'show' => $request->input('show'),
            'project_owner' => $request->input('project_owner'),
            'project_telno' => $request->input('project_telno'),
            'project_address' => $request->input('project_address')
        ]);

        return redirect()->route('projectSumm')->with('success', 'Project updated successfully.');
    }

    public function deleteProject($id)
    {
        $deleteProject = Project::find($id);

        $deleteProject->delete();

        return redirect()->back()->with('success', 'Project deleted successfully.');
    }

    public function titleSumm(Project $project)
    {
        $titles = Title::where('project_id', $project->id)->orderBy('t_sequence')->get();

        return view('admin.titleSumm', compact('titles', 'project'));
    }

    public function addTitle(Request $request, Project $project)
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

        $number = Title::where('project_id', $project->id)->orderBy('t_sequence', 'desc')->first();

        $newTsequence = $number ? $number->t_sequence + 1 : 1;

        $title = Title::create([
            'title_name' => $request->input('title_name'),
            't_sequence' => $newTsequence,
            'project_id' => $project->id
        ]);

        return redirect()->route('titleSumm', ['project' => $project->id])->with('success', 'New title created successfully.');
    }

    public function editTitle($id)
    {
        $title = Title::find($id);

        $project = Project::where('id', $title->project_id)->first();

        // return view('admin.editTitle', compact('title', 'project'));

        $response = [
            'project' => $project,
            'title' => $title,
        ];

        return response()->json($response);
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

        return redirect()->route('titleSumm', ['project' => $updateTitle->project_id])->with('success', 'Title updated successfully.');
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

    public function viewMoreSubtitle($id)
    {
        $subtitles = Subtitle::where('title_id', $id)->orderBy('s_sequence')->get();

        $title = Title::find($id);

        $project = Project::where('id', $title->project_id)->first();

        return view('admin.viewMoreSubtitle', compact('subtitles', 'title', 'project'));
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

        return redirect()->back()->with('success', 'New subtitle created successfully.');
    }

    public function editSubtitle($id)
    {
        $subtitle = Subtitle::where('id', $id)->first();

        $title = Title::where('id', $subtitle->title_id)->first();

        $project = Project::where('id', $title->project_id)->first();

        // return view('admin.editSubtitle', compact('subtitle', 'title', 'project'));

        $response = [
            'project' => $project,
            'title' => $title,
            'subtitle' => $subtitle
        ];

        return response()->json($response);
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

        return redirect()->back()->with('success', 'Subtitle updated successfully.');
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

    public function viewMoreContent($id)
    {
        $contents = Content::where('subtitle_id', $id)->orderBy('c_sequence')->get();

        $subtitles = Subtitle::join('titles', 'titles.id', 'subtitles.title_id')
                            ->where('subtitles.id', $id)
                            ->first();

        $title = Title::where('id', $subtitles->id)->first();

        $project = Project::where('id', $title->project_id)->first();

        return view('admin.viewMoreContent', compact('contents', 'subtitles', 'title', 'project'));
    }

    public function createContent()
    {
        $projects = Project::all();
        $titles = Title::with('projects')->get();
        $subtitles = Subtitle::with('title')->get();

        return view('admin.createContent', compact('projects', 'titles', 'subtitles'));
    }

    public function addContent(Request $request)
    {

        $rules = [
            'content_name' => 'required',
            'd_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'subtitle_type' => 'required',
        ];

        $messages = [
            'content_name.required' => 'The Content field is required.',
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
            'content_name' => 'required',
        ];

        $messages = [
            'c_sequence.required' => 'The Sequence field is required.',
            'subtitle_id.required' => 'The Subtitle field is required.',
            'content_name.required' => 'The Content field is required.',
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

    public function supportTool()
    {
        $projects = Project::all();

        return view('admin.supportTool', compact('projects'));
    }

    public function supportCategory()
    {
        $supportCategories = SupportCategory::all();

        return view('admin.category', compact('supportCategories'));
    }

    public function supportCategorySumm(Project $project)
    {
        // Fetch the support subcategories associated with the project
        $supportSubCategories = SupportSubCategory::where('project_id', $project->id)->get();

        // Extract the unique category IDs from the support subcategories
        $categoryIds = $supportSubCategories->pluck('category_id')->unique();

        // Fetch the support categories based on the extracted category IDs
        $supportCategories = SupportCategory::whereIn('id', $categoryIds)->get();

        // Initialize an array to store support subcategories for each category
        $supportSubCategoriesByCategory = [];

        // Retrieve support subcategories for each category
        foreach ($supportCategories as $category) {
            $sub = SupportSubCategory::where('project_id', $project->id)
                                    ->where('category_id', $category->id)
                                    ->get();

            // Store support subcategories for the current category
            $supportSubCategoriesByCategory[$category->id] = $sub;
        }

        return view('admin.supportCategorySumm', compact('supportCategories', 'project', 'supportSubCategoriesByCategory'));
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

        return redirect()->route('supportCategory',)->with('success', 'New category created successfully.');
    }

    public function editCategory($id)
    {
        $category = SupportCategory::find($id);

        // return view('admin.editCategory', compact('category'));

        $response = [
            'category' => $category,
        ];

        return response()->json($response);
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

        return redirect()->route('supportCategory')->with('success', 'Category updated successfully.');
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

    public function supportSubSumm(SupportCategory $supportCategory, Project $project)
    {
       $supportSubCategories = SupportSubCategory::with('supportCategories')
                                                    ->with('projects')
                                                    ->where('category_id', $supportCategory->id)
                                                    ->where('project_id', $project->id)
                                                    ->get();

        return view('admin.supportSubSumm', compact('supportSubCategories', 'project'));
    }

    public function createSub(SupportCategory $supportCategory, Project $project)
    {
        // $contents = Content::with('subtitle.title')->get();

        $contents = Content::join('subtitles', 'contents.subtitle_id', 'subtitles.id')
                            ->join('titles', 'subtitles.title_id', 'titles.id')
                            ->get();


        $supportCategories = SupportCategory::all();

        $projects = Project::all();

        return view('admin.createSub', compact('supportCategory', 'contents', 'supportCategories', 'projects', 'project'));
    }

    public function addSub(Request $request, Project $project)
    {
        $rules = [
            'sub_name' => 'required|max:255',
            'sub_description' => 'required|max:255',
            'project_id' => 'required',
            'category_id' => 'required'
        ];

        $messages = [
            'sub_name.required' => 'The Subcategory Name field is required.',
            'sub_name.max' => 'The Subcategory Name field should not exceed 255 characters.',
            'sub_description.required' => 'The Description field is required.',
            'sub_description.max' => 'The Description should not exceed 255 characters.',
            'project_id.required' => 'Related project is required.',
            'category_id.required' => 'Category Name is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $projectId = $request->input('project_id');
        $categoryId = $request->input('category_id');

        $createSub = SupportSubCategory::create([
            'category_id' => $categoryId,
            'sub_name' => $request->input('sub_name'),
            'sub_description' => $request->input('sub_description'),
            'project_id' => $projectId
        ]);

        return redirect()->route('supportCategorySumm', ['project' => $projectId])->with('success', 'New subcategory created successfully.');
    }

    public function editSub($id)
    {
        $supportSubCategories = SupportSubCategory::find($id);
        // $contents = Content::join('subtitles', 'contents.subtitle_id', 'subtitles.id')
        //                     ->join('titles', 'subtitles.title_id', 'titles.id')
        //                     ->get();
        $supportCategories = SupportCategory::all();

        $projects = Project::all();

        return view('admin.editSub', compact('supportSubCategories', 'supportCategories', 'projects'));
    }

    public function updateSub(Request $request, $id)
    {
        $rules = [
            'sub_name' => 'required|max:255',
            'sub_description' => 'required|max:255',
            'project_id' => 'required',
            'category_id' => 'required'
        ];

        $messages = [
            'sub_name.required' => 'The Subcategory Name field is required.',
            'sub_name.max' => 'The Subcategory Name should not exceed 255 characters.',
            'sub_description.required' => 'The Description field is required.',
            'sub_description.max' => 'Description should not exceed 255 characters.',
            'project_id.required' => 'Related Project is required.',
            'category_id.required' => 'Category Name is required.',
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

        $projectId = $request->input('project_id');

        $updateSubCat->update([
            'sub_name' => $request->input('sub_name'),
            'sub_description' => $request->input('sub_description'),
            'project_id' => $projectId
        ]);

        return redirect()->route('supportSubSumm', ['supportCategory' => $categoryId, 'project' => $projectId]);
    }

    public function deleteSub($id)
    {
        $deleteSub = SupportSubCategory::find($id);
        $deleteSub->delete();

        return redirect()->back()->with('success', 'Data deleted successfully.');
    }

    public function ticketStatus()
    {
        $ticketStatuses = TicketStatus::all();

        return view('admin.ticketStatus', compact('ticketStatuses'));
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

        // return view('admin.editTicketStatus', compact('ticketStatus'));

        $response = [
            'ticketStatus' => $ticketStatus,
        ];

        return response()->json($response);
    }

    public function updateTicketStatus(Request $request)
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

        $updateTicketStatus = TicketStatus::find($request->id);

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

        $full_name_with_underscores = str_replace(' ', '_', $request->input('name'));

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_profile_picture.' . $extension; // Modify the file name
            $file->move('storage/profilePicture/', $filename);
        }

        $admin = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'position' => $request->input('position'),
            'whatsapp_me' => $request->input('whatsapp_me'),
            'telegram_username' => $request->input('telegram_username'),
            'profile_picture' => $filename,
            'role_id' => $request->input('role_id'),
            'password' => $hashedPassword,
            'category_id' => $request->input('category_id'),
            'manage_ticket_in_category' => $request->has('manage_ticket_in_category') ? 1 : 0,
            'manage_own_ticket' => $request->has('manage_own_ticket') ? 1 : 0,
            'manage_documentation' => $request->has('manage_documentation') ? 1 : 0,
            'manage_support_tool' => $request->has('manage_support_tool') ? 1 : 0,
        ]);

        $newUserId = $admin->id;

        $emailSignature = EmailSignature::create([
            'user_id' => $newUserId,
            'sign_off' => 'Best regards,',
            'font_family' => 'Allura',
            'font_size' => '25',
            'font_color' => '#000000'
        ]);

        return redirect()->route('adminSumm')->with('success', 'New admin created successfully');
    }

    public function editAdmin($id)
    {
        $user = User::find($id);
        $roles = Role::all();
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

        $full_name_with_underscores = str_replace(' ', '_', $request->input('name'));

        $photoPath = 'storage/profilePicture/';

        // Check if a new profile picture has been uploaded
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $full_name_with_underscores . '_profile_picture.' . $extension; // Modify the file name

            // Delete all previous files with the same full name
            $filesToDelete = glob($photoPath . $full_name_with_underscores . '_profile_picture.*');
            foreach ($filesToDelete as $fileToDelete) {
                if (File::exists($fileToDelete)) {
                    File::delete($fileToDelete);
                }
            }

            // Upload the new profile picture
            $file->move($photoPath, $filename);
        } else {
            // If no new file uploaded, retain the old profile picture filename
            $filename = $updateAdmin->profile_picture;
        }

        $updateAdmin->profile_picture = $filename;


        $updateAdmin->name = $request->input('name');
        $updateAdmin->username = $request->input('username');
        $updateAdmin->email = $request->input('email');
        $updateAdmin->phone_number = $request->input('phone_number');
        $updateAdmin->position = $request->input('position');
        $updateAdmin->whatsapp_me = $request->input('whatsapp_me');
        $updateAdmin->telegram_username = $request->input('telegram_username');
        $updateAdmin->role_id = $request->input('role_id');
        $updateAdmin->category_id = $request->input('category_id');
        $updateAdmin->manage_ticket_in_category = $request->has('manage_ticket_in_category') ? 1 : 0;
        $updateAdmin->manage_own_ticket = $request->has('manage_own_ticket') ? 1 : 0;
        $updateAdmin->manage_documentation = $request->has('manage_documentation') ? 1 : 0;
        $updateAdmin->manage_support_tool = $request->has('manage_support_tool') ? 1 : 0;

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

    public function enhancement()
    {
        $projects = Project::all();

        return view('admin.enhancement', compact('projects'));
    }

    public function enhancementSumm(Project $project)
    {
        $enhancements = Enhancement::with('versions')->where('project_id', $project->id)->get();

        foreach ($enhancements as $enhancement) {
            $versionId = $enhancement->version_id;

            $version = Version::where('id', $versionId)->first();

            $month = $version->month;

            $year = $version->year;

            $majorUpdate = $version->major_update;

            $tableMigrate = $version->table_migrate;

            $minorUpdate = $version->minor_update;

            $versionNumber = $month . $year . '.' . $majorUpdate . '.' . $tableMigrate . '.' . $minorUpdate;
        }

        return view('admin.enhancementSumm', compact('enhancements', 'project', 'versionNumber'));
    }

    public function createEnhancement()
    {
        return view('admin.createEnhancement');
    }

    public function addEnhancement(Request $request, Project $project)
    {
        $rules = [
            'enhancement_title' => 'required|max:255',
            'enhancement_description' => 'required|max:255',
        ];

        $messages = [
            'enhancement_title.required' => 'The Name field is required.',
            'enhancement_description.required' => 'The Username field is required.',
            'enhancement_title.max' => 'The Name should not exceed 255 characters.',
            'enhancement_description.max' => 'The Username should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        // Get the latest version
        $latestVersion = Version::where('project_id', $project->id)->latest()->first();
        $latestEnhancement = Enhancement::where('project_id', $project->id)->latest()->first();

        // Extract the components of the latest version number or set initial values
        if ($latestVersion) {
            $month = date('m');
            $year = date('y');
            $majorUpdate = $latestVersion->major_update;
            $tableMigrate = $latestVersion->table_migrate;
            $minorUpdate = $latestVersion->minor_update;

        } else {
            // If no existing enhancements, set initial version components
            $month = date('m');
            $year = date('y');
            $majorUpdate = '01';
            $tableMigrate = '001';
            $minorUpdate = '001';
        }

        // Update version components based on checkbox values
        if ($request->has('major_update')) {
            $majorUpdate = str_pad(intval($majorUpdate) + 1, 2, '0', STR_PAD_LEFT);
        }

        if ($request->has('table_migrate')) {
            $tableMigrate = str_pad(intval($tableMigrate) + 1, 3, '0', STR_PAD_LEFT);
        }

        if ($request->has('minor_update')) {
            $minorUpdate = str_pad(intval($minorUpdate) + 1, 3, '0', STR_PAD_LEFT);
        }

        // If no checkbox was ticked, reuse the latest version
        if (!$request->hasAny(['major_update', 'table_migrate', 'minor_update']) && $latestEnhancement) {
            $version = $latestEnhancement->version_id;
        } else {
            // Construct the new version number
            $version = $month . $year . '.' . $majorUpdate . '.' . $tableMigrate . '.' . $minorUpdate;
        }


        // if ($latestVersion) {
        //     $updateVersion = Version::where('project_id', $project->id)->first();

        //     $newVersion = $updateVersion->update([
        //         'major_update' => $majorUpdate,
        //         'table_migrate' => $tableMigrate,
        //         'minor_update' => $minorUpdate,
        //         'project_id' => $project->id
        //     ]);

        //     $versionId = $updateVersion->id;

        // } else {

        // }

        $newVersion = Version::create([
            'month' => $month,
            'year' => $year,
            'major_update' => $majorUpdate,
            'table_migrate' => $tableMigrate,
            'minor_update' => $minorUpdate,
            'project_id' => $project->id
        ]);

        $versionId = $newVersion->id;

        // Create the new enhancement
        $enhancement = Enhancement::create([
            'enhancement_title' => $request->input('enhancement_title'),
            'enhancement_description' => $request->input('enhancement_description'),
            'version_id' => $versionId,
            'project_id' => $project->id
        ]);

        return redirect()->back()->with('success', 'New enhancement created successfully');
    }

    public function editEnhancement($id)
    {
        $enhancement = Enhancement::find($id);

        // return view('admin.editTicketStatus', compact('ticketStatus'));

        $response = [
            'enhancement' => $enhancement,
        ];

        return response()->json($response);
    }

    public function updateEnhancement(Request $request, $id)
    {
        $rules = [
            'enhancement_title' => 'required|max:255',
            'enhancement_description' => 'required|max:255',
        ];

        $messages = [
            'enhancement_title.required' => 'The Name field is required.',
            'enhancement_description.required' => 'The Username field is required.',
            'enhancement_title.max' => 'The Name should not exceed 255 characters.',
            'enhancement_description.max' => 'The Username should not exceed 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $updateEnhancement = Enhancement::find($request->id);

        $updateEnhancement->update([
            'enhancement_title' => $request->input('enhancement_title'),
            'enhancement_description' => $request->input('enhancement_description'),
        ]);

        return redirect()->route('enhancementSumm')->with('success', 'Ticket Status updated successfully.');
    }

    public function deleteEnhancement($id)
    {
        $enhancement = Enhancement::find($id);

        $enhancement->delete();

        return redirect()->back()->with('success', 'Data deleted successfully.');
    }

    public function invoice()
    {
        $projects = Project::all();

        return view('admin.invoice', compact('projects'));
    }

    public function orderItemSumm(Project $project)
    {
        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();
        $orderItems = OrderItem::with('orders')->where('project_id', $project->id)->get();
        $projectId = $project->id;

        // Get the current month and year
        $currentMonthYear = date('my');

        // Get the project name and remove spaces
        $projectName = str_replace(' ', '', $project->project_name);

        // Generate the base invoice number format
        $baseInvoiceNumber = $currentMonthYear . '-' . $projectName . '-';

        // Check if there are existing orders for the project
        $existingOrders = Order::where('project_id', $projectId)->count();

        // If there are no existing orders, start with 00001
        if ($existingOrders == 0) {
            $invoiceNumber = $baseInvoiceNumber . '00001';
        } else {
            // Get the highest invoice number for the project
            $latestOrder = Order::where('project_id', $projectId)->latest('order_no')->first();

            // Extract the last five digits from the highest invoice number
            $lastDigits = intval(substr($latestOrder->order_no, -5));

            // Increment the last five digits by 1
            $nextNumber = $lastDigits + 1;

            // Format the next number with leading zeros
            $nextNumberFormatted = sprintf('%05d', $nextNumber);

            // Combine with the base invoice number to get the new invoice number
            $invoiceNumber = $baseInvoiceNumber . $nextNumberFormatted;
        }

        return view('admin.orderItemSumm', compact('orderItems', 'project', 'user', 'emailSignature', 'projectId', 'invoiceNumber'));
    }

    public function createOrderItem(Project $project)
    {
        return view('admin.createOrderItem', compact('project'));
    }

    public function addOrderItem(Request $request, Project $project)
    {
        // Define validation rules
        $rules = [
            'car.*.order_item' => 'required|string|max:255',
            'car.*.order_quantity' => 'required|numeric|min:1', // Quantity must be numeric and at least 1
            'car.*.order_description' => 'required|string|max:255', // Description must be a string and max 255 characters
            'car.*.unit_price' => 'required|min:1', // Unit price must be numeric and at least 0
        ];

        // Define custom validation messages
        $messages = [
            'car.*.order_item.required' => 'The Item field is required.',
            'car.*.order_item.string' => 'The Item must be a string.',
            'car.*.order_item.max' => 'The Item may not be greater than :max characters.',
            'car.*.order_quantity.required' => 'The Quantity field is required.',
            'car.*.order_quantity.numeric' => 'The Quantity must be a number.',
            'car.*.order_quantity.min' => 'The Quantity must be at least 1.',
            'car.*.order_description.required' => 'The Description field is required.',
            'car.*.order_description.string' => 'The Description must be a string.',
            'car.*.order_description.max' => 'The Description may not be greater than :max characters.',
            'car.*.unit_price.required' => 'The Unit price field is required.',
            // 'car.*.unit_price.numeric' => 'The Unit price must be a number.',
            'car.*.unit_price.min' => 'The Unit price must be at least 1.',
        ];


        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails, redirect back with errors and input data
        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $carInputs = $request->input('car', []);

        foreach ($carInputs as $carInput) {

            $newOrderItem = new OrderItem();
            $newOrderItem->order_item = $carInput['order_item'];
            $newOrderItem->order_description = $carInput['order_description'];
            $newOrderItem->order_quantity = $carInput['order_quantity'];
            $newOrderItem->unit_price = $carInput['unit_price'];
            $newOrderItem->total_price = $carInput['total_price'];
            $newOrderItem->project_id = $project->id;
            $newOrderItem->save();
        }

        return redirect()->route('orderItemSumm', ['project' => $project->id])->with('success', 'New order created successfully');
    }

    public function editOrderItem($id)
    {
        $orderItem = OrderItem::find($id);

        $response = [
            'orderItem' => $orderItem,
        ];

        return response()->json($response);
    }

    public function updateOrderItem(Request $request)
    {
        $rules = [
            'order_item' => 'required|string|max:255',
            'order_quantity' => 'required|numeric|min:1', // Quantity must be numeric and at least 1
            'order_description' => 'required|string|max:255', // Description must be a string and max 255 characters
            'unit_price' => 'required|min:1', // Unit price must be numeric and at least 0
        ];

        $messages = [
            'order_item.required' => 'The Item field is required.',
            'order_item.string' => 'The Item must be a string.',
            'order_item.max' => 'The Item may not be greater than :max characters.',
            'order_quantity.required' => 'The Quantity field is required.',
            'order_quantity.numeric' => 'The Quantity must be a number.',
            'order_quantity.min' => 'The Quantity must be at least 1.',
            'order_description.required' => 'The Description field is required.',
            'order_description.string' => 'The Description must be a string.',
            'order_description.max' => 'The Description may not be greater than :max characters.',
            'unit_price.required' => 'The Unit price field is required.',
            // 'unit_price.numeric' => 'The Unit price must be a number.',
            'unit_price.min' => 'The Unit price must be at least 1.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $orderItemId = $request->input('id');

        $orderItem = OrderItem::find($orderItemId);

        $unitPrice = $request->input('unit_price');
        $orderQuantity = $request->input('order_quantity');
        $totalPrice = $unitPrice * $orderQuantity;

        $orderItem->update([
            'order_item' => $request->input('order_item'),
            'order_description' => $request->input('order_description'),
            'order_quantity' => $orderQuantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice
        ]);

        // Check if the order_id of the updated orderItem is not null
        if ($orderItem->order_id !== null) {
            // Retrieve the order_id
            $orderId = $orderItem->order_id;

            $orderItems = OrderItem::where('order_id', $orderId)->get();

            // Initialize total price variable
            $totalPriceSum = 0;

            // Iterate over each orderItem to calculate the total price sum
            foreach ($orderItems as $item) {
                $totalPriceSum += $item->total_price;
            }

            $order = Order::find($orderId);

            $discount = $order->discount;

            // Calculate the grand total
            $grandTotal = $totalPriceSum - ($totalPriceSum * ($discount / 100));

            // Update the grand_total field and total bill field of the order
            $order->update([
                'grand_total' => $grandTotal,
                'total_bill' => $totalPriceSum
            ]);
        }

        // return redirect()->route('orderItemSumm', ['project' => $orderItem->project_id])->with('success', 'Order updated successfully.');
        return redirect()->back();
    }

    public function deleteOrderItem($id)
    {
        // Find the order item to be deleted
        $orderItem = OrderItem::find($id);

        // Retrieve the order ID of the order item
        $orderId = $orderItem->order_id;

        // Delete the order item
        $orderItem->delete();

        // Check if the order_id of the deleted orderItem is not null
        if ($orderId !== null) {
            // Retrieve the order based on the order ID
            $order = Order::find($orderId);

            // Retrieve all order items associated with the order
            $orderItems = OrderItem::where('order_id', $orderId)->get();

            // Initialize total price variable
            $totalPriceSum = 0;

            // Iterate over each orderItem to calculate the total price sum
            foreach ($orderItems as $item) {
                $totalPriceSum += $item->total_price;
            }

            // Retrieve the discount from the order
            $discount = $order->discount;

            // Calculate the grand total
            $grandTotal = $totalPriceSum - ($totalPriceSum * ($discount / 100));

            // Update the grand_total field and total bill field of the order
            $order->update([
                'grand_total' => $grandTotal,
                'total_bill' => $totalPriceSum
            ]);
        }

        return redirect()->back()->with('success', 'Data deleted successfully.');
    }

    public function createInvoice(Request $request)
    {
        // Retrieve the order item IDs and project ID from the request
        $orderItemIds = $request->query('orderItemIds');

        // Explode the string of IDs into an array
        $orderItemIdsArray = explode(',', $orderItemIds);

        // Fetch order items based on the IDs
        $orderItems = OrderItem::whereIn('id', $orderItemIdsArray)->get();

        // Get all order item IDs
        $allOrderItemIds = OrderItem::pluck('id')->toArray();

        // Calculate the sum of total prices
        $totalPriceSum = $orderItems->sum('total_price');

        // Find the order item IDs that are not selected
        $unselectedOrderItemIds = array_diff($allOrderItemIds, $orderItemIdsArray);

        // Fetch unselected order items based on the IDs and where order_id is null
        $unselectedOrderItems = OrderItem::whereIn('id', $unselectedOrderItemIds)
                                          ->whereNull('order_id')
                                          ->get();

        $current = now();

        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();

        $projectId = $request->query('projectId');
        $invoiceNumber = $request->query('invoiceNumber');

        // Check if the invoice number already exists in the orders table
        $existingInvoice = Order::where('order_no', $invoiceNumber)->first();

        $discount = null;
        $grandTotal = null;

        if ($existingInvoice) {
            // If the invoice number exists, update the associated data
            $discount = $existingInvoice->discount;
            $grandTotal = $existingInvoice->grand_total;
        }

        $project = Project::find($projectId);

        return view('admin.createInvoice', compact('user', 'emailSignature', 'orderItems', 'unselectedOrderItems', 'current', 'project', 'orderItemIds', 'projectId', 'invoiceNumber', 'totalPriceSum', 'discount', 'grandTotal', 'existingInvoice'));
    }

    public function loadOrderItems(Request $request)
    {
        // Retrieve the order item IDs and project ID from the request
        $orderItemIds = $request->query('orderItemIds');

        // Explode the string of IDs into an array
        $orderItemIdsArray = explode(',', $orderItemIds);

        // Fetch order items based on the IDs
        $orderItems = OrderItem::whereIn('id', $orderItemIdsArray)->get();

        // Calculate the sum of total prices
        $totalPriceSum = $orderItems->sum('total_price');

        $current = now();

        $user = auth()->user();
        $emailSignature = EmailSignature::where('user_id', $user->id)->first();

        $projectId = $request->query('projectId');
        $invoiceNumber = $request->query('invoiceNumber');

        $project = Project::find($projectId);

        return response()->json([
            'orderItems' => $orderItems,
            'totalPriceSum' => $totalPriceSum,
        ]);
    }

    public function addInvoice(Request $request)
    {
        // Retrieve the order items from the request
        $orderItems = $request->input('orderItems');

        // Retrieve projectId directly from the first order item
        $projectId = $request->input('project_id');

        $invoiceNumber = $request->input('invoice_number');

        // Check if the invoice number already exists in the orders table
        $existingInvoice = Order::where('order_no', $invoiceNumber)->first();

        if ($existingInvoice) {
            // If the invoice number exists, update the associated data
            $existingInvoice->update([
                'total_bill' => $request->input('total_bill'),
                'discount' => $request->input('discount'),
                'grand_total' => $request->input('grand_total'),
                'terms' => $request->input('terms'),
                'project_id' => $projectId
            ]);

            $newInvoiceId = $existingInvoice->id;
        } else {
            // If the invoice number doesn't exist, create a new invoice
            $newInvoice = Order::create([
                'order_no' => $invoiceNumber,
                'total_bill' => $request->input('total_bill'),
                'discount' => $request->input('discount'),
                'grand_total' => $request->input('grand_total'),
                'terms' => $request->input('terms'),
                'project_id' => $projectId
            ]);

            $newInvoiceId = $newInvoice->id;
        }

        // Loop through the order items
        foreach ($orderItems as $key => $orderItem) {
            // Check if the array key is "undefined" or if the order item has a valid orderitemid
            if ($key === 'undefined' || !isset($orderItem['orderitemid']) || $orderItem['orderitemid'] === null) {
                // Create a new order item
                $newOrderItem = new OrderItem();

                // Assign values from the new data to the new order item
                $newOrderItem->order_item = $orderItem['item'];
                $newOrderItem->order_description = $orderItem['description'];
                $newOrderItem->unit_price = $orderItem['rate'];
                $newOrderItem->order_quantity = $orderItem['quantity'];

                $newOrderItem->project_id = $projectId;
                // Calculate the total price
                $totalPrice = $newOrderItem->unit_price * $newOrderItem->order_quantity;

                // Assign the total price to the new order item
                $newOrderItem->total_price = $totalPrice;
                $newOrderItem->order_id = $newInvoiceId;

                // Save the new order item to the database
                $newOrderItem->save();
            } else {
                // Retrieve the order item from the database based on its ID
                $order = OrderItem::find($orderItem['orderitemid']);

                // Update the order item with the new data from the request
                $order->order_item = $orderItem['item'];
                $order->order_description = $orderItem['description'];
                $order->unit_price = $orderItem['rate'];
                $order->order_quantity = $orderItem['quantity'];


                // Calculate the total price
                $totalPrice = $order->unit_price * $order->order_quantity;

                // Assign the total price to the order item
                $order->total_price = $totalPrice;

                $order->order_id = $newInvoiceId;

                // Save the updated order item
                $order->save();
            }
        }

        return redirect()->back();
        // return redirect()->route('invoiceSumm', ['project' => $projectId])->with('success', 'Invoice created successfully.');
    }

    public function invoiceSumm(Project $project)
    {
        $orders = Order::where('project_id', $project->id)->get();

        return view('admin.invoiceSumm', compact('project', 'orders'));
    }

    public function viewInvoice($id)
    {
        $orderItems = OrderItem::where('order_id', $id)->get();

        // Extracting IDs from orderItems
        $orderItemIds = $orderItems->pluck('id')->toArray();

        $order = Order::find($id);

        $projectId = $order->project_id;

        $invoiceNumber = $order->order_no;

        return view('admin.viewInvoice', compact('orderItems', 'orderItemIds','order', 'projectId', 'invoiceNumber'));
    }

    public function editInvoice($id)
    {
        $order = Order::find($id);

        $response = [
            'order' => $order,
        ];

        return response()->json($response);
    }

    public function updateInvoice(Request $request)
    {
        $order = Order::find($request->input('id'));

        $unitPrice = $request->input('unit_price');
        $orderQuantity = $request->input('order_quantity');
        $totalPrice = $unitPrice * $orderQuantity;

        $projectId = $order->project_id;

        $order->update([
            'total_bill' => $request->input('total_bill'),
            'discount' => $request->input('discount'),
            'grand_total' => $request->input('grand_total'),
            'terms' => $request->input('terms'),
        ]);

        // dd($orderItem);

        return redirect()->route('invoiceSumm', ['project' => $projectId])->with('success', 'Order updated successfully.');
    }

    public function deleteInvoice($id)
    {
        $orders = Order::find($id);

        foreach ($orders->orderItems as $orderItem) {
            $orderItem->delete();
        }

        $orders->delete();

        return redirect()->back()->with('success', 'Order and associated order items deleted successfully.');
    }
}
