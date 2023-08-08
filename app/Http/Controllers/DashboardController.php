<?php

namespace App\Http\Controllers;

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
            ->select('id', 'title', 'slug')
            ->with('tags:name')
            ->get();

        $topPoems = $topPoems->map(function ($item) use ($poems) {
            $poem = $poems->where('id', $item->poem_id)->first();
            $item->title = $poem->title;
            $item->slug = $poem->slug;
            $item->tags = $poem->tags;
            return $item;
        });

        return Inertia::render('Dashboard', [
            'startDate' => $startDate->format('m/d'),
            'endDate' => $endDate->format('m/d'),
            'topPoems' => $topPoems,
        ]);

    }

}
