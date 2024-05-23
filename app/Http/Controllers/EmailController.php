<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail; // Include the Mail facade

class EmailController extends Controller // Make sure the class is not abstract
{
    public function sendTestEmail()
    {
        Mail::raw('This is a test email', function ($message) {
            $message->from('mesvakc@gmail.com', 'Example App');
            $message->to('ligamero@gmail.com');
            $message->subject('Test Email from Laravel');
        });

        return "Email sent!";
    }
}
