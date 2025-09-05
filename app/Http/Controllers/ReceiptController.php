<?php

namespace App\Http\Controllers;

use App\Http\Requests\Receipt\CreateReceiptRequest;
use App\Http\Requests\Receipt\UpdateReceiptRequest;
use App\Models\Receipt;
use Exception;
use Inertia\Inertia;

class ReceiptController
{

    public function index()
    {
        $receipts = Receipt::with(['member'])
        ->orderByRaw("STR_TO_DATE(date_receipt, '%d/%m/%Y') DESC")
        ->get();
        
        return Inertia::render('Receipt', [
            'receipts' => $receipts,
        ]);
    }
    public function store(CreateReceiptRequest $request)
    {
        try {
                Receipt::create([
                    'value'       => $request->value,
                    'member_id'   => $request->memberID,
                    'description' => $request->description,
                    'period'      => $request->period,
                    'date_receipt'      => $request->dateReceipt,
                ]);


            return redirect()->route('panel.receipt')->with('success', 'Recebimento criado');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar recebimento. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateReceiptRequest $request)
    {
        try {
            $receipt = Receipt::findOrFail($request->route('receiptID'));

;           $receipt->value = $request->value;
;           $receipt->period = $request->period;
;           $receipt->date_receipt = $request->dateReceipt;
;           $receipt->member_id = $request->memberID;
;           $receipt->description = $request->description;
            
            $receipt->save();

            return redirect()->route('panel.receipt')->with('success', 'Recimento atualizado');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar recebimento. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($receiptID)
    {
        try {
            $receipt = Receipt::findOrFail($receiptID);
            $receipt->delete();

            return redirect()->route('panel.receipt')->with('success', 'Recebimento excluído');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir recebimento. Por favor, tente novamente.',
            ]);
        }
    }

}