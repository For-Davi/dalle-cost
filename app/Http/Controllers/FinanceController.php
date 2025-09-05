<?php

namespace App\Http\Controllers;

use App\Http\Requests\Finance\CreateFinanceRequest;
use App\Http\Requests\Finance\UpdateFinanceRequest;
use Exception;
use App\Models\Finance;
use Inertia\Inertia;

class FinanceController
{

    public function index()
    {
        $finances = Finance::orderBy('period', 'desc')->get();
        
        return Inertia::render('Finance', [
            'finances' => $finances,
        ]);
    }
    
    public function store(CreateFinanceRequest $request)
    {
        try {
            $existingFinance = Finance::where('period', $request->period)->first();
            if ($existingFinance) {
                return back()->withErrors([
                    'error' => 'O período já está criado. Escolha um período diferente.',
                ]);
            }

            Finance::create([
                'value'=> $request->value,
                'period'=> $request->period,
            ]);

            return redirect()->route('panel.finances')->with('success', 'Finança criada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar finança. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateFinanceRequest $request)
    {
        try {
            $finance = Finance::findOrFail($request->route('financeID'));
            $existingFinance = Finance::where('period', $request->period)
                ->where('id', '!=', $finance->id)
                ->first();
            
            if ($existingFinance) {
                return back()->withErrors([
                    'error' => 'O período já está em uso por outra finança. Escolha um período diferente.',
                ]);
            }

;           $finance->value = $request->value;
;           $finance->period = $request->period;
            
            $finance->save();

            return redirect()->route('panel.finances')->with('success', 'Finança atualizada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar finança. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($financeID)
    {
        try {
            $finance = Finance::findOrFail($financeID);
            $finance->delete();

            return redirect()->route('panel.finances')->with('success', 'Finança excluída');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir finança. Por favor, tente novamente.',
            ]);
        }
    }

}