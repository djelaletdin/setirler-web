<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Poem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
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
    public function store(Request $request, Poem $poem): RedirectResponse
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->user()->id; // Assuming only logged in users can comment
        $comment->poem_id = $poem->id;
        $comment->body = $request->body;
        $comment->save();

        return redirect()->back();
    }
    /**
     * Adds vote to a comment
     */
    public function vote(Request $request, Comment $comment): RedirectResponse
    {
        $user = auth()->user();
        $direction = (int) $request->input('direction');

        if ($direction === 0) {
            $user->votes()->where('comment_id', $comment->id)->delete();
        } else {
            $comment->vote(auth()->user(), $direction);
        }

        $comment->votes_count = $comment->votes()->sum('vote');
        $comment->save();

        return redirect()->back();
    }


    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back();
    }
}
