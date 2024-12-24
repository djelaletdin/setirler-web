<?php

namespace App\Http\Controllers;

use App\Models\Poem;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")->limit(5)->get(['id', 'name', 'username']);
        $poemTitles = Poem::where('title', 'LIKE', "%{$query}%")->limit(5)->get(['id', 'title', 'slug']);
        $poemContents = Poem::where('content', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'content', 'slug'])
            ->map(function ($poem) use ($query) {
                $lines = explode("\n", $poem->content);
                foreach ($lines as $line) {
                    if (stripos($line, $query) !== false) { // Check if the query exists in the line
                        $poem->content = $line; // Set content to the matched line
                        return $poem;
                    }
                }
                $poem->content = $lines[0] ?? ''; // Fallback to the first line if no match is found
                return $poem;
            });

        return response()->json([
            'users' => $users,
            'poemTitles' => $poemTitles,
            'poemContents' => $poemContents,
        ]);
    }
}
