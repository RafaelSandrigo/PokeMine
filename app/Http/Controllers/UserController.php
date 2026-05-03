<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Lista todos os usuários com seus tipos
     */
    public function index(): View
    {
        $users = User::with('type')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }
}