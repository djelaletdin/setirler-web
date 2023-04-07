<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class TagsController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Tags/TagsIndex', [
            'filters' => Request::only('search'),
            'tags' => Tag::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy('id' , 'desc')
                ->paginate(20)
                ->withQueryString()
                ->through(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'description' => $tag->description,
                    'slug' => $tag->slug
                ])
        ]);
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/TagEdit', [
            'tag' => [
                'id'=>$tag->id,
                'name' => $tag->name,
                'description' => $tag->description
            ]
        ]);
    }

    public function create(){
        return Inertia::render('Admin/Tags/TagCreate');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        $slug = Str::slug(Request::get('name'));

        Tag::create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
            'slug' => $slug
        ]);

        return Redirect::route('tags.index')->with('success', 'Tag created.');
    }

    public function update(Tag $tag)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'description' => ['required', 'max:50'],
        ]);

        $slug = Str::slug(Request::get('name'));

        $tag->update([Request::only('name', 'description','slug')]);

        return Redirect::back()->with('success', 'Tag Updated');
    }
}
