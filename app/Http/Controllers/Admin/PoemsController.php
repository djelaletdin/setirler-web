<?php

namespace App\Http\Controllers\Admin;

use App\Models\Poem;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class PoemsController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Poems/PoemsIndex', [
            'filters' => Request::only('search'),
            'poems' => Poem::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
                })
                ->paginate(20)
                ->withQueryString()
        ]);
    }
}
