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

        $user = auth()->user();

        if (request()->bearerToken() && $user = Auth::guard('sanctum')->user()) {
            Auth::setUser($user);
        }
        // Lmits the columns visible to the users

        // Keep track of viewing for both users and guests


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

        return response()->json([
            'poem' => $poem,
            'comments' => $comments,
            'totalViews' => $totalViews,
            'uniqueViews' => $uniqueViews,
            'userLikedPoem' => $userLikedPoem,
            'commentCount' => $comments->count()
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
