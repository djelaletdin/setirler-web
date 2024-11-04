<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Poem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Poem $poem)
    {
        // Hide specific columns
        $poem->makeHidden(['created_at', 'updated_at', 'status']);

        // Get the current user or IP address
        if (request()->bearerToken() && $user = Auth::guard('sanctum')->user()) {
            Auth::setUser($user);
        }

        $ipAddress = request()->ip();

        // Get the last view record for the user or IP address
        $lastView = $user
            ? $poem->views()->where('user_id', $user->id)->latest()->first()
            : $poem->views()->where('ip_address', $ipAddress)->latest()->first();

        // Check if there is a last view within a minute
        if (!$lastView || $lastView->created_at->diffInMinutes(now()) >= 1) {
            $poem->views()->create([
                'user_id' => $user?->id,
                'ip_address' => $user ? null : $ipAddress,
            ]);
        }

        // Get total and unique view counts
        $totalViews = $poem->totalViews();
        $uniqueViews = $poem->uniqueViews();

        // Load the necessary relationships and counts
        $poem->load(['user:id,username,name', 'tags:id,name,slug']);
        $poem->likeCount = $poem->likeCount();

        // Get the comments and their data
        $comments = $poem->comments()
            ->withCount('replies')
            ->with([
                'user:id,name',
                'replies.user:id,name',
                'votes' => function ($query) use ($user) {
                    if ($user) {
                        $query->where('user_id', $user->id);
                    }
                }
            ])
            ->get()
            ->map(function ($comment) use ($user) {
                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'date' => date('M d', strtotime($comment->created_at)),
                    'user' => $comment->user->name,
                    'replies_count' => $comment->replies_count,
                    'userVote' => $user ? $comment->votes->first()?->vote : null,
                ];
            });

        // Check if the user liked the poem
        $userLikedPoem = $user && Like::userLikedPoem($user->id, $poem->id);
        // Prepare the response data
        return response()->json([
            'poem' => [
                'id' => $poem->id,
                'title' => $poem->title,
                'content' => $poem->content,
                'slug' => $poem->slug,
                'likeCount' => $poem->likeCount,
                'user' => [
                    'id' => $poem->user->id,
                    'username' => $poem->user->username,
                    'name' => $poem->user->name,
                ],
                'tags' => $poem->tags->map(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ]),
            ],
            'comments' => $comments,
            'totalViews' => $totalViews,
            'uniqueViews' => $uniqueViews,
            'userLikedPoem' => $userLikedPoem,
            'commentCount' => $comments->count(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
