<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil data hanya dengan role 'user'
        $users = User::where('role', 'user')->get();

        // Mengembalikan data dalam format JSON
        return response()->json($users);
    }

    public function showall()
    {
        $users = User::all();
        return response()->json($users);
    }
}
