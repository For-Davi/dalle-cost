<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Finance;
use App\Models\Member;
use App\Models\Origin;
use App\Models\Movement;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController
{

    public function index()
    {
        $members    = Member::all();
        $origins    = Origin::with('member')->get();
        $categories = Category::all();
        $movements = Movement::with(['member', 'origin', 'category'])
            ->orderByRaw("SUBSTRING_INDEX(date_buy, '/', -1) DESC")
            ->orderByRaw("LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(date_buy, '/', -2), '/', 1), 2, '0') DESC")
            ->orderByRaw("LPAD(SUBSTRING_INDEX(date_buy, '/', 1), 2, '0') DESC")
            ->get();
        $years = Movement::query()
            ->selectRaw("DISTINCT SUBSTRING(period, 4, 4) as year")
            ->orderBy('year')
            ->pluck('year')
            ->toArray();
        $now = Carbon::now('America/Sao_Paulo');
        $currentPeriod = $now->format('m/Y');
        $financeActual = Finance::where('period', $currentPeriod)->first();

        return Inertia::render('Panel', [
            'origins'    => $origins,
            'members'    => $members,
            'categories' => $categories,
            'years'      => $years,
            'movements'  => $movements,
            'financeActual' => $financeActual
        ]);
    }

}