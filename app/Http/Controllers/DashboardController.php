<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;
use App\Models\Poem;
use App\Models\ViewCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): Response
    {

        /*
        |--------------------------------------------------------------------------
        | Top Poems
        |--------------------------------------------------------------------------
        */
        $today = Carbon::now();
        $startDate = $today->startOfWeek()->subWeek(); // Start date is 1 week ago
        $endDate = $startDate->copy()->endOfWeek(); // End date is current time

        $topPoems = ViewCount::select('poem_id', DB::raw('COUNT(*) as views'))
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('poem_id')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        $poemIds = $topPoems->pluck('poem_id');

        $poems = Poem::whereIn('id', $poemIds)
            ->select('id', 'title', 'content', 'slug', 'user_id', 'created_at')
            ->with('user:id,name')
//            ->with('tags:name')
            ->get();

        $topPoems = $topPoems->map(function ($item) use ($poems) {
            $poem = $poems->where('id', $item->poem_id)->first();
            $item->content = explode("\r\n\r\n", $poem->content)[0];
            $item->title = $poem->title;
            $item->slug = $poem->slug;
//            $item->tags = $poem->tags;
            $item->user = $poem->user;
            $item->date = date('M d', strtotime($poem->created_at));
            return $item;
        });

        /*
        |--------------------------------------------------------------------------
        | New Poems
        |--------------------------------------------------------------------------
        */
        $newPoems = Poem::select('title','user_id', 'slug', 'content', 'created_at')
            ->where('status', 1)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Break the content text when there are two consecutive line breaks
        $newPoems = $newPoems->map(function ($poem) {
            $poem->content = explode("\r\n\r\n", $poem->content)[0];
            $poem->date = date('M d', strtotime($poem->created_at));
            return $poem;
        });

        /*
        |--------------------------------------------------------------------------
        | New Comments
        |--------------------------------------------------------------------------
        */
        $newComments = Comment::select('user_id', 'poem_id', 'body', 'created_at')
            ->with('user:id,name,username')
            ->with('poem:id,slug,title')
            ->whereIn('id', function ($query) {
                // Subquery to get the latest comment for each poem
                $query->select('id')
                    ->from(DB::raw('(SELECT poem_id, MAX(created_at) as latest_date FROM comments GROUP BY poem_id) latest_comments'))
                    ->join('comments', function ($join) {
                        $join->on('comments.poem_id', '=', 'latest_comments.poem_id');
                        $join->on('comments.created_at', '=', 'latest_comments.latest_date');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

//        dd($newComments);

        return Inertia::render('Dashboard', [
            'startDate' => $startDate->format('m/d'),
            'endDate' => $endDate->format('m/d'),
            'topPoems' => $topPoems,
            'newPoems' => $newPoems,
            'newComments' => $newComments
        ]);

    }

}
