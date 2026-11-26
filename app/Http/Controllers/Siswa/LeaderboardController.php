<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;

class LeaderboardController extends Controller
{
    /*
      Leaderboard Gamifikasi
     */

      
    public function index()
    {
        $leaderboard = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->orderByDesc('poin')
            ->orderBy('name')
            ->get();

        return view(
            'siswa.leaderboard.index',
            compact('leaderboard')
        );
    }
} 