<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Poem;
use App\Models\ViewCount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        return response()->json([
            [
                'title' => 'New Poems',
                'poem' => $newPoems,
            ],
            [
                'title' => 'Top Poems',
                'poem' => $topPoems,
            ],
        ]);
    }
}
