<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;
use Illuminate\Http\Request;
use function Pest\Laravel\get;

class UserController extends Controller
{
    public function index(Category $category = null): Response
    {

        $categories = Category::all();
        $selectedCategory = $category ? $category->id : 0;

        $users = User::select('id', 'name', 'username', 'photo')
            ->when($category, function ($query, $category) {
                $query->where('category_id', $category->id);
            })
            ->withCount(['poems' => function ($query) {
                $query->where('status', 1);
            }])
            ->whereHas('poems', function ($query) {
                $query->where('status', 1);
            })
            ->paginate(12);

        return Inertia::render('User/Index', [
            'users' => $users,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function show(User $user): Response
    {
        $poems = $user->poems()
            ->where('status', 1)
            ->with('tags')
            ->orderByTitleWithoutQuotes()
            ->paginate(24);

        return Inertia::render('User/Show', [
            'user' => $user,
            'poems' => $poems,
        ]);
    }
}
