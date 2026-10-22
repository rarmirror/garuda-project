<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function send(Request $request)
    {
        // Maksimal 5 pesan per jam dari satu alamat IP, untuk mencegah spam.
        $key = 'contact:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()
                ->withErrors(['message' => 'Terlalu banyak pesan dalam waktu singkat. Silakan coba lagi nanti.'])
                ->withInput();
        }

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => ['required', Rule::in(ContactMessage::SUBJECTS)],
            'message' => 'required|string|min:10|max:5000',
        ]);

        ContactMessage::create($data);

        RateLimiter::hit($key, 3600);

        return back()->with('success', 'Pesan kamu sudah kami terima! Tim CS kami akan menghubungi dalam 1x24 jam.');
    }
}