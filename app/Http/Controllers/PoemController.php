<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use App\Models\Tag;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $tags = Tag::select('id', 'name')->get();

        return Inertia::render('Poem/Edit', [
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new Poem instance and set its properties from the validated data
        $poem = new Poem();
        $poem->title = $validatedData['title'];
        $poem->content = $validatedData['content'];
        $poem->user_id = Auth::user()->id;
        // Save the poem to the database
        $poem->save();

        // Optionally, you can associate the poem with the authenticated user
//        $user = Auth::user();
//        $user->poems()->save($poem);

        // Redirect the user to a confirmation page or the poem details page
        return redirect()->route('poems.show', $poem->slug)->with('message', 'Poem successfully updated');

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

//            if ($viewCount) {
//                // Update the existing view count record for the user
//                $viewCount->touch();
//            } else {
//                // Create a new view count record for the user
                $poem->views()->create([
                    'user_id' => $user->id,
                ]);
//            }
        } else {
            // Check if the guest user's IP address has already viewed the poem
            $ipAddress = request()->ip();
            $viewCount = $poem->views()->where('ip_address', $ipAddress)->first();

//            if ($viewCount) {
//                // Update the existing view count record for the guest user
//                $viewCount->touch();
//            } else {
                // Create a new view count record for the guest user
                $poem->views()->create([
                    'ip_address' => $ipAddress,
                ]);
//            }
        }

        // Get the total number of views for the poem

        $totalViews = $poem->views()->count();

        // Get the unique number of views for the poem
        $uniqueViews = $poem->uniqueViews();

        $poem->load('user');
        $poem->load('tags');
        $comments = $poem->comments;

//        dd($comments);
        return Inertia::render('Poem/Show', [
            'poem' => $poem,
            'comments' => $comments,
            'totalViews' => $totalViews,
            'uniqueViews' => $uniqueViews
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
