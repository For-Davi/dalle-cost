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

        if ($user->role === 'admin') {
            $members    = Member::all();
            $origins    = Origin::with('member')->get();
            $categories = Category::all();
        } else {
            // Recupera somente os movements que pertencem ao usuário autenticado
            $userMovements = Movement::where('member_id', $user->id)->get();

            // IDs de categories e origins utilizadas
            $categoryIds = $userMovements->pluck('category_id')->unique();
            $originIds   = $userMovements->pluck('origin_id')->unique();

            // Filtra apenas as relacionadas
            $categories = Category::whereIn('id', $categoryIds)->get();
            $origins    = Origin::whereIn('id', $originIds)->with('member')->get();

            // Usuário comum não precisa de lista de todos os members
            $members = collect([]);
        }

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
            'origins'    => $origins,
            'members'    => $members,
            'categories' => $categories,
            'years'      => $years,
            'movements'  => $movements,
        ]);
    }

}