<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class PoemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $poems = Poem::select('poems.id', 'poems.title', 'users.name as author_name', 'view_count')
            ->with('tags:name')
            ->join('users', 'poems.user_id', '=', 'users.id')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('tags', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            })
            ->orderByTitleWithoutQuotes()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Poem/Index', [
            'poems' => $poems,
            'filter' => $search
        ]);
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
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Poem $poem): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poem $poem): Response
    {
        return Inertia::render('Admin/Poem/Edit', [
            'poem' => $poem->load('user'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poem $poem): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poem $poem): RedirectResponse
    {
        //
    }
}
