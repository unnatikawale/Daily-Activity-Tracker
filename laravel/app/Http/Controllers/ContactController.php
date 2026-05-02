<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Create contact record in database
        $contact = Contact::create($validated);

        // Send email to admin
        try {
            Mail::to('unnatikawale43@gmail.com')->send(new ContactFormMail($contact));
        } catch (\Exception $e) {
            // Log error but continue with success response
            \Log::error('Contact email failed: ' . $e->getMessage());
        }

        return redirect()->route('contact.show')->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
