<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Origin;
use App\Models\Movement;
use Inertia\Inertia;

class DashboardController
{

    public function index()
    {
        $members    = Member::all();
        $origins    = Origin::with('member')->get();
        $categories = Category::all();
        $movements = Movement::all();
        $years = Movement::query()
            ->selectRaw("DISTINCT SUBSTRING(period, 4, 4) as year")
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        return Inertia::render('Panel', [
            'origins'    => $origins,
            'members'    => $members,
            'categories' => $categories,
            'years'      => $years,
            'movements'  => $movements,
        ]);
    }

}