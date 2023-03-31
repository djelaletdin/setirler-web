<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/UsersIndex', [
            'filters' => Request::only('search'),

            'users' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
                })
                ->paginate(20)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'photo' => $user->photo,
                    'info' => $user->info,
                    'category_id' => $user->category_id,
                ])
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/UserEdit', [
            'user' => [
                'id'=>$user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'info' => $user->info,
//                'photo' => $user->photo ? "$user->photo" : null,
                'photo' => $user->photo ?  : null
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/UserCreate');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'max:50', Rule::unique('users')],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
        ]);

        User::create([
            'name' => Request::get('name'),
            'email' => Request::get('email'),
            'username' => Request::get('username'),
            'info' => Request::get('info'),
            'photo' => Request::get('photo'),
            'password' => Request::get('password'),
        ]);

        return Redirect::route('users.index')->with('success', 'User created.');
    }

    public function update(User $user)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'username' => ['required', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'info' => ['nullable'],
            'category_id' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('name', 'username', 'email', 'info'));

        if (Request::file('photo')) {
            $user->update(['photo' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User Updated');
    }
}
