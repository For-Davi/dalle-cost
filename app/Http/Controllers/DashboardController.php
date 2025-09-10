<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\ExportRequest;
use App\Models\Category;
use App\Models\Member;
use App\Models\Movement;
use App\Models\Origin;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        $members = Member::orderBy('name')->get();
        $origins = Origin::with('member')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $movements = Movement::with(['member', 'origin', 'category'])
            ->orderByRaw("SUBSTRING_INDEX(date_buy, '/', -1) DESC")
            ->orderByRaw("LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(date_buy, '/', -2), '/', 1), 2, '0') DESC")
            ->orderByRaw("LPAD(SUBSTRING_INDEX(date_buy, '/', 1), 2, '0') DESC")
            ->get();

        $years = Movement::query()
            ->selectRaw('DISTINCT SUBSTRING(period, 4, 4) as year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $receipts = Receipt::with(['member'])
            ->orderByRaw("STR_TO_DATE(date_receipt, '%d/%m/%Y') DESC")
            ->get();

        return Inertia::render('Panel', [
            'origins' => $origins,
            'members' => $members,
            'categories' => $categories,
            'years' => $years,
            'movements' => $movements,
            'receipts' => $receipts,
        ]);
    }

    public function export(ExportRequest $request)
    {
        $data = $request->validated();

        $year = ! is_null($data['year']) ? intval($data['year']) : date('Y');
        $month = ! is_null($data['month']) ? intval($data['month']) : null;
        if ($month !== null && ($month < 1 || $month > 12)) {
            $month = null;
        }

        $movements = Movement::with(['member', 'origin'])
            ->when($data['memberID'] > 0, fn ($q) => $q->where('member_id', $data['memberID']))
            ->when($data['originID'] > 0, fn ($q) => $q->where('origin_id', $data['originID']))
            ->when($data['categoryID'] > 0, fn ($q) => $q->where('category_id', $data['categoryID']))
            ->whereRaw('RIGHT(period, 4) = ?', [$year]) // Filtra pelo ano
            ->when($month !== null, function ($q) use ($month) {
                $q->whereRaw('LEFT(period, 2) = ?', [
                    str_pad($month, 2, '0', STR_PAD_LEFT),
                ]);
            })
            ->orderBy('period')
            ->get();

        $nested = $movements
            ->groupBy(fn ($m) => $m->member ? $m->member->name : 'Sem Membro')
            ->map(fn ($byMember) => $byMember->groupBy('period'));

        $pdf = Pdf::loadView('dashboard.export_pdf', [
            'nested' => $nested,
            'filter' => $data,
        ]);

        $filename = 'movements_export';
        if (! is_null($data['year'])) {
            $filename .= "_{$data['year']}";
            if ($month !== null) {
                $mm = str_pad($month, 2, '0', STR_PAD_LEFT);
                $filename .= "_{$mm}";
            }
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
}
