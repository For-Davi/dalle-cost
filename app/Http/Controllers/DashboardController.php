<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\ExportRequest;
use App\Models\Category;
use App\Models\Member;
use App\Models\Origin;
use App\Models\Movement;
use App\Models\Receipt;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function export(ExportRequest $request)
{
    $data = $request->validated();

    // Monta e executa a query como antes
    $movements = Movement::with(['member', 'origin'])
        ->when($data['memberID']  > 0, fn($q) => $q->where('member_id',   $data['memberID']))
        ->when($data['originID']  > 0, fn($q) => $q->where('origin_id',   $data['originID']))
        ->when($data['categoryID']> 0, fn($q) => $q->where('category_id', $data['categoryID']))
        ->when(! is_null($data['year']),
              fn($q) => $q->whereRaw("RIGHT(period,4) = ?", [$data['year']]))
        ->when(! is_null($data['month']),
              fn($q) => $q->whereRaw("LEFT(period,2)  = ?", [
                    str_pad($data['month'], 2, '0', STR_PAD_LEFT)
              ]))
        ->orderBy('period')
        ->get();

    // 1º nível de agrupamento: usuário
    // 2º nível: período
    $nested = $movements
        ->groupBy(fn($m) => $m->member->name)
        ->map(fn($byMember) => $byMember->groupBy('period'));

    $pdf = Pdf::loadView('dashboard.export_pdf', [
        'nested' => $nested,
        'filter' => $data,
    ]);

    // monta o nome do arquivo (idêntico ao que você já tinha)
    $filename = 'movements_export';
    if (! is_null($data['year'])) {
        if (! is_null($data['month'])) {
            $mm = str_pad($data['month'], 2, '0', STR_PAD_LEFT);
            $filename .= "_{$mm}-{$data['year']}.pdf";
        } else {
            $filename .= "_{$data['year']}.pdf";
        }
    } else {
        $filename .= '.pdf';
    }

    return $pdf->download($filename);
}

}
   