<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Poem;
use Illuminate\Http\Request;

class LikeController extends Controller
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
    public function store(Request $request, Poem $poem)
    {
        $userId = auth()->id();
        $poemId = $poem->id;

        $userLikedPoem = Like::userLikedPoem($userId, $poemId);

        if ($userLikedPoem){
            $like = Like::where('user_id', $userId)->where('poem_id', $poemId)->first();
            $this->destroy($like);
            return  response()->json([
                'message' => "unliked"
                ]);
        } else {
            $like = new Like();
            $like->user_id = $userId; // Assuming only logged-in users can like
            $like->poem_id = $poem->id;
            $like->save();
            return  response()->json([
                'message' => "liked"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(Like $like)
    {
        $like->delete();
        return redirect()->back();
    }
}
