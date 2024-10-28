<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Poem;
use App\Models\Tag;

use App\Models\Vote;
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

        return Inertia::render('Poem/Create', [
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
        // Lmits the columns visible to the users
        $poem->makeHidden('created_at', 'updated_at','status');

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


        $poem->load('user:id,username,name');
        $poem->load('tags:id,name,slug');
        $poem->likeCount = $poem->likeCount();


        $comments = $poem->comments()
            ->withCount('replies')
            ->with(['user:id,name', 'replies.user:id,name', 'votes' => function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id); //get votes where user_id is equal to the authenticated user
                }
            }])
            ->get()
            ->map(function ($comment) use ($user) {
                $comment->date = date('M d', strtotime($comment->created_at));
                $comment->user = $comment->user->name;
                $comment->userVote = $user ? $comment->votes->first()?->vote : null;
                return $comment;

            });

        $userLikedPoem = $user && Like::userLikedPoem($user->id, $poem->id);

        return Inertia::render('Poem/Show', [
            'poem' => $poem,
            'comments' => $comments,
            'totalViews' => $totalViews,
            'uniqueViews' => $uniqueViews,
            'userLikedPoem' => $userLikedPoem,
            'commentCount' => $comments->count()
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
