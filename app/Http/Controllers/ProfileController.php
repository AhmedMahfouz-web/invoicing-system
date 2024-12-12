<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a form for creating a new profile.
     */
    public function create(): View
    {
        return view('profile.create');
    }

    /**
     * Store a newly created profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Logic to store profile data
        return redirect()->route('profile.create')->with('success', 'Profile created successfully.');
    }
}
