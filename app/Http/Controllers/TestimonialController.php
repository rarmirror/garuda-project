<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        // Hanya tampilkan yang sudah di-approve admin
        $testimonials = Testimonial::where('status', 'approved')
            ->latest()
            ->get();

        return view('testimonials.index', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'origin_city' => 'nullable|string|max:100',
            'rating'      => 'required|integer|min:1|max:5',
            'message'     => 'required|string|min:10|max:1000',
        ]);

        Testimonial::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'origin_city' => $request->origin_city,
            'rating'      => $request->rating,
            'message'     => $request->message,
            'status'      => 'pending', // admin harus approve dulu
        ]);

        return back()->with('success', 'Terima kasih! Testimoni kamu sedang menunggu persetujuan admin.');
    }
}