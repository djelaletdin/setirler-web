<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Poem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Poem $poem): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $user = auth()->user();

        $parentCommentId = $request->input('parentCommentId');

        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->poem_id = $poem->id;
        $comment->body = $request->body;

        if ($parentCommentId) {
            $parentComment = Comment::findOrFail($parentCommentId);
            $comment->parent_id = $parentComment->id; // Set the parent comment ID if it exists
        }

        $comment->save();

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'date' => date('M d', strtotime($comment->created_at)),
            'user' => $comment->user->name,
            'replies_count' => $comment->replies_count,
            'userVote' => $user ? $comment->votes->first()?->vote : null,
            ], 201);
    }
}
