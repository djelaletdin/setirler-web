<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class PoemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Poem $poem): Response
    {
        $user = auth()->user();

        if ($user) {
            // Check if the current user has already viewed the poem
            $viewCount = $poem->views()->where('user_id', $user->id)->first();

            if ($viewCount) {
                // Update the existing view count record for the user
                $viewCount->touch();
            } else {
                // Create a new view count record for the user
                $poem->views()->create([
                    'user_id' => $user->id,
                ]);
            }
        } else {
            // Check if the guest user's IP address has already viewed the poem
            $ipAddress = request()->ip();
            $viewCount = $poem->views()->where('ip_address', $ipAddress)->first();

            if ($viewCount) {
                // Update the existing view count record for the guest user
                $viewCount->touch();
            } else {
                // Create a new view count record for the guest user
                $poem->views()->create([
                    'ip_address' => $ipAddress,
                ]);
            }
        }

        // Get the total number of views for the poem
        $totalViews = $poem->views()->count();

        $poem->load('user');
        return Inertia::render('Poem/Show', [
            'poem' => $poem,
            'totalViews' => $totalViews,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poem $poem): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poem $poem): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poem $poem): RedirectResponse
    {
        //
    }
}
