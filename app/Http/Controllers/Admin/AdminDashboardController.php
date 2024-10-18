<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

use App\Models\Poem;
use App\Models\Tag;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Int_;

class AdminDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {

        $days = $request->query('d', null);

        // If $days is provided and is valid, set date range
        if ($days && is_numeric($days) && $days > 0) {
            $startDate = now()->subDays($days);
            $endDate = now();

            $visitorCounts = [
                'total' => Visitor::whereBetween('visited_at', [$startDate, $endDate])->count(),
                'unique' => Visitor::whereBetween('visited_at', [$startDate, $endDate])
                    ->distinct('ip_address')
                    ->count()
            ];

            $poemCount = Poem::whereBetween('created_at', [$startDate, $endDate])->count();
        } else {
            // Fetch all data if no valid $days parameter is provided
            $visitorCounts = [
                'total' => Visitor::count(),
                'unique' => Visitor::distinct('ip_address')->count()
            ];

            $poemCount = Poem::count();
        }

        $unpublishedPoems = Poem::where('status', 0)
            ->select('id','title', 'user_id', 'created_at')
            ->with('user:id,name')
            ->get()
            ->map(function ($poem) {
                $poem->date = date('M d', strtotime($poem->created_at));
                return $poem;
            });

//        dd($unpublishedPoems);

        // Pass the data to the view
        return Inertia::render('Admin/Dashboard', [
            'visitorCounts' => $visitorCounts,
            'poemCount' => $poemCount,
            'selectedDay' => (int)$days,
            'unpublishedPoems' => $unpublishedPoems->all(),
        ]);

    }

}
