<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data_lab = Laboratorium::paginate(3);
        $data_peminjaman = Peminjaman::with('user')->paginate(3);
        return view('admin.dashboard',[
            'data_lab' => $data_lab,
            'data_peminjaman' => $data_peminjaman
        ]);
    }
}
