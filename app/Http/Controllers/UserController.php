<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $riwayat_peminjaman = Peminjaman::where('user_id', Auth::id())
            ->with('laboratorium')
            ->latest()
            ->get();

        return view('user.index', [
            'riwayat_peminjaman' => $riwayat_peminjaman,
            'available_lab' => Laboratorium::where('status', 'tersedia')->get(),
        ]);
    }
}