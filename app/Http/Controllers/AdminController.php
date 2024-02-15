<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Title;
use App\Models\Content;
use App\Models\DocumentationImage;
use App\Models\Subtitle;
use App\Models\SupportCategory;
use App\Models\SupportSubCategory;
use App\Models\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketImage;

class AdminController extends Controller
{

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

                    // dd($ticketsByCategory);

        return view('admin.adminDashboard', compact('ticketStatuses', 'ticketCounts', 'currentYear', 'ticketsByStatus', 'ticketsByCategory'));
    }

    public function helpdesk(Request $request)
    {
        $tickets = Ticket::with('supportCategories', 'ticketImages')->get();
        $categories = SupportCategory::all();

        return view('admin.helpdesk', compact('tickets','categories'));
    }

    public function getTicket(Request $request)
    {
        $operator = $request->input('operator');
        $category_id = $request->input('category_id');
        $priority = $request->input('priority');
        $date = $request->input('filter_date');
        $searchTerm = $request->input('searchTerm');

        // Get the page and per_page values from the request, default to 1 and 10 if not provided
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = DB::table('tickets')
        ->join('support_categories', 'support_categories.id', 'tickets.category_id')
        ->join('ticket_statuses', 'tickets.status_id', 'ticket_statuses.id')
        ->select('tickets.*', 'support_categories.*', 'ticket_statuses.*','tickets.id as ticket_id', 'tickets.created_at as t_created_at')
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
                    ->orWhere('tickets.pic_id', 'LIKE', "%$searchTerm%");
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

    public function viewTicketImage($id)
    {
        $ticketImages = TicketImage::where('ticket_id', $id)->get();
        $tickets = Ticket::find($id);

        return view('admin.viewTicketImage', compact('ticketImages', 'tickets'));
    }

    public function viewContent(Title $title)
    {
        $title->load([
            'subtitles.contents' => function ($query) {
                $query->orderBy('c_sequence');
            }
        ]);

        return view('admin.viewContent', compact('title'));
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
            'title_name.required' => 'Title is required.',
            'title_name.max' => 'Title should not exceed 255 characters.',
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
            't_sequence.required' => 'Sequence is required.',
            'title_name.required' => 'Title is required.',
            'title_name.max' => 'Title should not exceed 255 characters.',
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
            'subtitle_name.required' => 'Subtitle is required.',
            'subtitle_name.max' => 'Subtitle should not exceed 255 characters.',
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
            's_sequence.required' => 'Sequence is required.',
            'subtitle_name.required' => 'Subtitle is required.',
            'subtitle_name.max' => 'Subtitle should not exceed 255 characters.',
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
            'content_name.required' => 'Content is required.',
            'content_name.max' => 'Content should not exceed 5000 characters.',
            'd_image.image' => 'Must be an image format.',
            'd_image.max' => 'Image should not exceed 2 GB.',
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
            'c_sequence.required' => 'Sequence is required.',
            'subtitle_id.required' => 'Subtitle is required.',
            'content_name.required' => 'Content is required.',
            'content_name.max' => 'Content should not exceed 5000 characters.',
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
            'category_name.required' => 'Category is required.',
            'category_name.max' => 'Category should not exceed 255 characters.',
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
            'category_name.required' => 'Category is required.',
            'category_name.max' => 'Category should not exceed 255 characters.',
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
            'sub_name.required' => 'Name is required.',
            'sub_name.max' => 'Name should not exceed 255 characters.',
            'sub_description.required' => 'Description is required.',
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
            'sub_name.required' => 'Name is required.',
            'sub_name.max' => 'Name should not exceed 255 characters.',
            'sub_description.required' => 'Description is required.',
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

    public function updateTicketStatus(Request $request)
    {
        $data = $request->input('data');

        // Get the list of IDs in the request
        $requestStatusIds = collect($data)->pluck('id')->toArray();

        $statusesToDelete = TicketStatus::whereNotIn('id', $requestStatusIds)->get();

        foreach ($statusesToDelete as $statusToDelete) {
            $statusToDelete->delete();
        }

        foreach ($data as $row) {
            // Validate each row of data
            // $validator = Validator::make($row, [
            //     'category_name' => 'required|max:255',
            // ], [
            //     'category_name.max' => 'Category name should not exceed 255 characters.',
            // ]);

            // // Check if validation fails
            // if ($validator->fails()) {
            //     return response()->json(['error' => $validator->errors()], 400);
            // }

            $ticket_status_id = $row['id'];
            $status = $row['status'];

            // Check if the record exists
            $existingTicketStatus = TicketStatus::find($ticket_status_id);

            if ($existingTicketStatus) {
                // Update the existing record
                $existingTicketStatus->update([
                    'status' => $status,
                ]);
            } else {

                // Create a new record
                $newTicketStatus = TicketStatus::create([
                    'status' => $status,
                ]);
            }
        }

        return response()->json(['message' => 'Ticket status updated successfully']);
    }

    public function ticketSumm(TicketStatus $status)
    {
        $status = TicketStatus::with(['tickets' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'tickets.supportCategories'])->find($status->id);

        $allStatus = TicketStatus::all();

        $tickets = $status->tickets;

        return view('admin.ticketSumm', compact('status', 'tickets', 'allStatus'));
    }

    public function viewTicket($id)
    {
        $ticket = Ticket::find($id);
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();
        $ticketImages = TicketImage::where('ticket_id', $id)
                                ->get();

        return view('admin.viewTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages'));
    }

    public function createTicket()
    {
        $supportCategories = SupportCategory::all();
        $ticketStatuses = TicketStatus::all();

        return view('admin.createTicket', compact('supportCategories', 'ticketStatuses'));
    }

    public function addTicket(Request $request)
    {
        $rules = [
            'sender_name' => 'required',
            'sender_email' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'category_id' => 'required',
            'priority'=> 'required',
            't_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];

        $messages = [
            'sender_name.required' => 'Name is required.',
            'sender_email.required' => 'Email is required.',
            'subject.required' => 'Subject is required.',
            'message.required' => 'Message is required.',
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

        $ticket = Ticket::create([
            'sender_name' => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
            'status_id' => $request->input('status_id')
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

        return view('admin.editTicket', compact('ticket', 'supportCategories', 'ticketStatuses', 'ticketImages'));
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
            'ticket_no.required' => 'Ticket No. is required.',
            'sender_name.required' => 'Sender Name is required.',
            'sender_name.max' => 'Sender Name should not exceed 255 characters.',
            'sender_email.required' => 'Sender Email is required.',
            'sender_email.max' => 'Sender Email should not exceed 255 characters.',
            'subject.required' => 'Subject is required.',
            'subject.max' => 'Subject should not exceed 255 characters.',
            'message.required' => 'Message is required.',
            'message.max' => 'Message should not exceed 5000 characters.',
            'category_id.required' => 'Category is required.',
            'priority.required' => 'Priority is required.',
            'status_id.required' => 'Status is required.',
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

    public function categorySumm(SupportCategory $supportCategory)
    {
        $supportCategory = SupportCategory::with(['tickets' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'tickets.ticketStatus'])->find($supportCategory->id);

        $tickets = $supportCategory->tickets;

        return view('admin.categorySumm', compact('supportCategory', 'tickets'));
    }

}
