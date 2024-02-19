<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Ticket;
use App\Models\Content;
use App\Models\Title;
use App\Models\SupportCategory;
use App\Models\SupportSubCategory;
use App\Models\TicketImage;
use App\Mail\SubmitTicket;
// use Alert;



class MemberController extends Controller
{

    public function dashboard()
    {
        // Retrieve the first Title ordered by t_sequence
        $title = Title::with(['subtitles.contents' => function ($query) {
            $query->orderBy('c_sequence');
        }])
        ->orderBy('t_sequence')
        ->first();

        $titles = Title::with('subtitles.contents')->get();

        // Pass the title to the documentation view
        return view('user.documentation', compact('title', 'titles'));
    }
    public function documentation(Title $title)
    {
        $title->load([
            'subtitles.contents' => function ($query) {
                $query->orderBy('c_sequence');
            }
        ]);

        $titles = Title::with('subtitles.contents')->get();

        return view('user.documentation', compact('title', 'titles'));
    }

    public function support()
    {
        $supportCategories = SupportCategory::with([
            'supportSubCategories' => function ($query) {
                $query->select(
                    'support_sub_categories.*',
                    'support_categories.*',
                    'contents.*',
                    'subtitles.*',
                    'titles.*',
                    'support_sub_categories.id as sub_category_id'
                )
                ->join('support_categories', 'support_categories.id', '=', 'support_sub_categories.category_id')
                ->join('contents', 'contents.id', '=', 'support_sub_categories.content_id')
                ->join('subtitles', 'subtitles.id', '=', 'contents.subtitle_id')
                ->join('titles', 'titles.id', '=', 'subtitles.title_id');
            },
        ])->get();

        return view('user.support', compact('supportCategories'));
    }

    public function openTicket()
    {
        $supportCategories = SupportCategory::all();

        return view('user.openTicket', compact('supportCategories'));
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

        $ticket = Ticket::create([
            'sender_name' => $request->input('sender_name'),
            'sender_email' => $request->input('sender_email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'category_id' => $request->input('category_id'),
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

        Mail::send(new SubmitTicket($ticket));

        return redirect()->route('dashboard')->with('success', 'Ticket submitted successfully');
    }


    public function content()
    {
        $titles = Title::with('subtitles.contents')->get();
        return view('user.content', compact('titles'));
    }
}
