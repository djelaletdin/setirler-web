<?php

namespace App\Http\Controllers;

use App\Models\Like;
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

        // Redirect the user to a confirmation page or the poem details page
        return redirect()->route('poems.show', $poem->slug)->with('message', 'Poem successfully updated');

    }

    /**
     * Display the specified resource.
     */
    public function show(Poem $poem): Response
    {


        // Keep track of viewing for both users and guests
        $user = auth()->user();

        // Get the last view record for the user or IP address
        $lastView = $user
            ? $poem->views()->where('user_id', $user->id)->latest()->first()
            : $poem->views()->where('ip_address', request()->ip())->latest()->first();

        // Check if there is a last view within a minutes
        if (!$lastView || $lastView->created_at->diffInMinutes(now()) >= 1) {
            if ($user) {
                $poem->views()->create([
                    'user_id' => $user->id,
                ]);
            } else {
                $ipAddress = request()->ip();
                $poem->views()->create([
                    'ip_address' => $ipAddress,
                ]);
            }
        }

        $totalViews = $poem->totalViews();
        $uniqueViews = $poem->uniqueViews();

        $poem->load('user');
        $poem->load('tags');
        $comments = $poem->comments;

        $userLikedPoem = Like::userLikedPoem($user->id, $poem->id);

        return Inertia::render('Poem/Show', [
            'poem' => $poem,
            'comments' => $comments,
            'totalViews' => $totalViews,
            'uniqueViews' => $uniqueViews,
            'userLikedPoem' => $userLikedPoem,
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
