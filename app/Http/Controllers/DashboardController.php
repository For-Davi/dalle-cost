<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\Origin;
use App\Models\Movement;
use App\Models\Receipt;
use Inertia\Inertia;

class DashboardController
{

    public function index()
    {
        $members    = Member::orderBy('name')->get();
        $origins    = Origin::with('member')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
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
        
        $receipts = Receipt::with(['member'])
            ->orderByRaw("STR_TO_DATE(date_receipt, '%d/%m/%Y') DESC")
            ->get();

        return Inertia::render('Panel', [
            'origins'    => $origins,
            'members'    => $members,
            'categories' => $categories,
            'years'      => $years,
            'movements'  => $movements,
            'receipts'   => $receipts
        ]);
    }

}