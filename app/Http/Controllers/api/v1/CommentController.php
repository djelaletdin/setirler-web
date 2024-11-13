<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Poem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $comment->user_id = $user->id;
        $comment->poem_id = $poem->id;
        $comment->body = $request->body;

        if ($parentCommentId) {
            $parentComment = Comment::findOrFail($parentCommentId);
            $comment->parent_id = $parentComment->id;
        }

        $comment->save();

        // Reload the comment with relationships and necessary counts
        $comment = Comment::where('id', $comment->id)
            ->withCount('replies')
            ->with(['user:id,name', 'votes' => function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id); // Filter votes by the authenticated user
                }
            }])
            ->first();

        $response = [
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'poem_id' => $comment->poem_id,
            'body' => $comment->body,
            'parent_id' => $comment->parent_id,
            'created_at' => $comment->created_at->toIso8601String(),
            'updated_at' => $comment->updated_at->toIso8601String(),
            'votes_count' => $comment->votes->count(),
            'replies_count' => $comment->replies_count,
            'date' => date('M d', strtotime($comment->created_at)),
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
            ],
            'userVote' => $user ? $comment->votes->first()?->vote : null,
            'replies' => [], // Empty array for replies, since this is a new comment
            'votes' => [], // Empty array for votes, or populate if needed
        ];


        return response()->json(
            $response
        , 201);
    }



    public function vote(Request $request, Comment $comment): JsonResponse
    {
        $user = auth()->user();

        if (request()->bearerToken() && $user = Auth::guard('sanctum')->user()) {
            Auth::setUser($user);
        }

        $direction = (int) $request->input('direction');

        if ($direction === 0) {
            $user->votes()->where('comment_id', $comment->id)->delete();
        } else {
            $comment->vote(auth()->user(), $direction);
        }

        $comment->votes_count = $comment->votes()->sum('vote');
        $comment->save();

        return response()->json(['message'=>'Voted'], 200);
    }

}
