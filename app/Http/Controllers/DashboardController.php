<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Origin;
use Inertia\Inertia;

class DashboardController
{

    public function index()
    {
        $origins = Origin::with('member')->get();
        $members = Member::all();
        
        return Inertia::render('Origins', [
            'origins' => $origins,
            'members' => $members
        ]);
    }

}