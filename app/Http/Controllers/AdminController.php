<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // Mengambil data hanya dengan role 'user'
        $users = User::where('role', 'admin')->get();

        // Mengembalikan data dalam format JSON
        return response()->json($users);
    } 
}
