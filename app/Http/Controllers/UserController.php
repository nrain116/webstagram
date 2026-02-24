<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Suche Nutzer nach Username.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('username', 'like', "%{$query}%")
                     ->take(20) // optional, max 20 Ergebnisse
                     ->get();

        return view('users.search_results', compact('users', 'query'));
    }
}