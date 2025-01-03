<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Poem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LikeController extends Controller
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
        $userId = auth()->id();
        $poemId = $poem->id;

        $userLikedPoem = Like::userLikedPoem($userId, $poemId);

        if ($userLikedPoem){
            $like = Like::where('user_id', $userId)->where('poem_id', $poemId)->first();
            $this->destroy($like);
            return redirect()->back();
        } else {
            $like = new Like();
            $like->user_id = $userId; // Assuming only logged-in users can like
            $like->poem_id = $poem->id;
            $like->save();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like): RedirectResponse
    {
        $like->delete();
        return redirect()->back();
    }
}
