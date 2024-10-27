<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poem;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $newPoems = Poem::select('title', 'user_id', 'slug', 'content', 'created_at')
            ->where('status', 1)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Format each poem's content and date
        $newPoems = $newPoems->map(function ($poem) {
            // Break the content text at the first two consecutive line breaks
            $poem->content = explode("\r\n\r\n", $poem->content)[0];
            $poem->date = date('M d', strtotime($poem->created_at));
            return $poem;
        });

        return response()->json(
            $newPoems[0],
        );
    }
}
