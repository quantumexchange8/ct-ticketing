<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\SubmitTicket;
use App\Models\Ticket;
use App\Models\Content;
use App\Models\Title;
use App\Models\SupportCategory;
use App\Models\SupportSubCategory;
use App\Models\TicketImage;
use App\Models\Project;
use App\Models\TicketLog;
use App\Models\Enhancement;

class MemberController extends Controller
{

    // public function dashboard()
    // {
    //     // Retrieve the first Title ordered by t_sequence
    //     $title = Title::with(['subtitles.contents' => function ($query) {
    //         $query->orderBy('c_sequence');
    //     }])
    //     ->orderBy('t_sequence')
    //     ->first();

    //     // Export one documentation
    //     $singleTitle = $title;

    //     // Export all documentation
    //     $titles = Title::with('subtitles.contents')->get();

    //     // Pass the title to the documentation view
    //     return view('user.documentation', compact('title', 'titles', 'singleTitle'));
    // }

    public function dashboard()
    {
        $projects = Project::where('show', 1)->get();
        $supportCategories = SupportCategory::all();

        return view('user.dashboard', compact('projects', 'supportCategories'));
    }
    public function selectProject($projectId)
    {
        Session::put('selected_project_id', $projectId);

        $title = Title::where('project_id', $projectId)->first();
        if ($title) {
            return redirect()->route('documentation', ['title' => $title->id]);
        } else {
            // Display a blank page
            return view('user.blank');
        }
    }
    public function documentation(Title $title)
    {
        $projectId = Session::get('selected_project_id');

        // Fetch project by ID
        $project = Project::find($projectId);

        // Eager load relationships
        $project->load([
            'titles' => function ($query) use ($title) {
                $query->where('id', $title->id)
                      ->with('subtitles.contents', function ($query) {
                          $query->orderBy('c_sequence');
                      });
            }
        ]);

        // Export one documentation
        $singleProject = $project;

        // Export all documentation
        $allContents = Title::with('subtitles.contents')->where('titles.project_id', $projectId)->get();

        return view('user.documentation', compact('project', 'title', 'singleProject', 'allContents'));
    }

    public function support(Project $project)
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

        return view('user.support', compact('supportCategories', 'project', 'supportSubCategoriesByCategory'));
    }

    public function openTicket(Project $project)
    {
        $supportCategories = SupportCategory::all();
        $projects = Project::all();

        return view('user.openTicket', compact('supportCategories', 'projects', 'project'));
    }

    public function submitTicket(Request $request)
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
            'sender_name.required' => 'Full Name is required.',
            'sender_name.max' => 'Full Name should not exceed 255 characters.',
            'sender_email.required' => 'Email is required.',
            'sender_email.max' => 'Email should not exceed 255 characters.',
            'subject.required' => 'Subject is required.',
            'subject.max' => 'Subject should not exceed 255 characters.',
            'message.required' => 'Message is required.',
            'message.max' => 'Message should not exceed 5000 characters.',
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

        $senderName = $request->input('sender_name');
        $senderEmail = $request->input('sender_email');
        $subject = $request->input('subject');
        $message = $request->input('message');

        $projectId = null;
        $projectName = null;

        if ($request->has('project_id')) {
            $projectId = $request->input('project_id');
        }

        if ($request->has('p_name')) {
            $projectName = $request->input('p_name');
        }

        $ticket = Ticket::create([
            'sender_name' => $senderName,
            'sender_email' => $senderEmail,
            'subject' => $subject,
            'message' => $message,
            'category_id' => $request->input('category_id'),
            'priority' => $request->input('priority'),
            'status_id' => 1,
            'project_id' => $projectId,
            'p_name' => $projectName
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

        $emailSubject = $ticketNo . '-' . $senderName;

        // Mail::send(new SubmitTicket($ticket, $subject, $senderEmail, $emailSubject));

        return redirect()->back()->with('success', 'Ticket submitted successfully');
    }

    public function releaseNote(Project $project)
    {
        // Retrieve all ticket logs from the database
        // $ticketLogs = TicketLog::with('tickets')->get();

        $ticketLogs = TicketLog::with('tickets')
        ->whereHas('tickets', function ($query) use ($project) {
            $query->where('project_id', $project->id)->orderByDesc('created_at');
        })
        ->get();

        $enhancements = Enhancement::with('projects')->where('project_id', $project->id)->orderByDesc('created_at')->get();

        $latestEnhancement = Enhancement::where('project_id', $project->id)->latest()->first();

        // Group the ticket logs by date
        $groupedTicketLogs = $ticketLogs->groupBy(function ($ticketLog) {
            // Convert the created_at timestamp to a formatted date string
            return $ticketLog->created_at->formatLocalized('%d %B %Y'); // %d: day, %B: full month name, %Y: year
        });

        // Group the enhancement by date
        $groupedEnhancements = $enhancements->groupBy(function ($enhancements) {
            // Convert the created_at timestamp to a formatted date string
            return $enhancements->created_at->formatLocalized('%d %B %Y'); // %d: day, %B: full month name, %Y: year
        });

        // dd($groupedTicketLogs);

        return view('user.releaseNote', compact('groupedTicketLogs', 'groupedEnhancements', 'latestEnhancement'));
    }


}
