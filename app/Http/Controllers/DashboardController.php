<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Origin;
use App\Models\Movement;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController
{

    public function index()
    {
        $user = Auth::user();

        $members = $user && $user->role === 'admin'
            ? Member::all()
            : collect([]);

        $origins = $user && $user->role === 'admin'
            ? Origin::with('member')->get()
            : collect([]);

        $categories = $user && $user->role === 'admin'
            ? Category::all()
            : collect([]);

        $years = Movement::query()
            ->when(
                $user->role !== 'admin',
                fn($q) => $q->where('member_id', $user->id)
            )
            ->selectRaw("DISTINCT SUBSTRING(period, 4, 4) as year")
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $movements = Movement::when(
            $user->role !== 'admin',
            fn($q) => $q->where('member_id', $user->id)
        )->get();

        
        return Inertia::render('Panel', [
            'origins' => $origins,
            'members' => $members,
            'categories' => $categories,
            'years' => $years,
            'movements' => $movements
        ]);
    }

}