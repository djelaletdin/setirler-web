<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::has('poems')->withCount('poems')->paginate(12);
        return Inertia::render('User/Index', [
            'users' => $users,
        ]);
    }

    public function show(User $user): Response
    {
        $poems = $user->poems()
            ->with('tags')
            ->orderByTitleWithoutQuotes()
            ->paginate(24);

        return Inertia::render('User/Show', [
            'user' => $user,
            'poems' => $poems,
        ]);
    }
}
