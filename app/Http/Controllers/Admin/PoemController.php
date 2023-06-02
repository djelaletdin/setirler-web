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
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $poems = Poem::select('poems.id', 'poems.title', 'poems.slug', 'users.name as author_name', 'status')
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

    public function unpublished(Request $request): Response
    {
        $search = $request->input('search');

        $poems = Poem::select('poems.id', 'poems.title', 'poems.slug', 'users.name as author_name', 'status')
            ->where('status', 0)
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

        return Inertia::render('Admin/Poem/Unpublished', [
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
        $users = User::select('id', 'name')->get();
        $tags = Tag::select('id', 'name')->get();

        return Inertia::render('Admin/Poem/Edit', [
            'poem' => $poem->load('user'),
            'users' => $users,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poem $poem): RedirectResponse
    {


        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|exists:users,id',
            'status' => 'required',
//            'tags.*' => 'exists:tags,id',
        ]);

        $poem->update([
            'title' => $request->input('title'),
            'user_id' => $request->input('author'),
            'content' => $request->input('content'),
            'status'  => $request->input('status')
        ]);

//        $poem->tags()->sync($request->tags);

        $poem->save();

        return redirect()->route('admin.poems.edit', $poem->slug)->with('message', 'Eseriňiz goşuldy');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poem $poem): RedirectResponse
    {
        //
    }
}
