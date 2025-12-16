<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactQuery;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        // 1. Validate form
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        // 2. Prepare data
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => 'BusPH Inquiry',
            'message' => $request->message,
        ];

        // 3. QUEUED email (non-blocking)
        Mail::to('busph.help@gmail.com')->queue(
            new ContactQuery($data)
        );

        return back()->with(
            'success',
            'Thank you! Your message has been sent successfully.'
        );
    }
}
