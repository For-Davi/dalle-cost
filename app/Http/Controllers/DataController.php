<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movement\CreateMovementRequest;
use App\Http\Requests\Movement\UpdateMovementRequest;
use App\Models\Category;
use App\Models\Member;
use App\Models\Movement;
use App\Models\Origin;
use Exception;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DataController
{
    public function index()
    {
        $movements = Movement::with(['member', 'origin', 'category'])
            ->latest()
            ->get();

        $origins = Origin::with('member')->orderBy('name')->get();
        $members = Member::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Data', [
            'movements' => $movements,
            'origins' => $origins,
            'members' => $members,
            'categories' => $categories,
        ]);
    }

    public function store(CreateMovementRequest $request)
    {
        try {
            [$month, $year] = explode('/', $request->period);

            $date = Carbon::createFromDate($year, $month, 1);

            for ($i = 0; $i < $request->quantity; $i++) {
                $installment = $request->quantity == 1 ? "1/1" : (($i + 1) . '/' . $request->quantity);

                Movement::create([
                    'value'       => $request->value,
                    'date_buy'    => $request->dateBuy,
                    'member_id'   => $request->memberID,
                    'origin_id'   => $request->originID,
                    'category_id' => $request->categoryID,
                    'description' => $request->description,
                    'period'      => $date->format('m/Y'),
                    'installment' => $installment
                ]);

                $date->addMonth();
            }

            return redirect()->route('panel.data')
                            ->with('success', 'Movimentação(s) criada(s) com sucesso');
        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar movimentação. Por favor, tente novamente.',
            ]);
        }
    }


    public function update(UpdateMovementRequest $request)
    {
        try {
            $movement = Movement::findOrFail($request->route('dataID'));

            $movement->value = $request->value;
            $movement->period = $request->period;
            $movement->date_buy = $request->dateBuy;
            $movement->member_id = $request->memberID;
            $movement->origin_id = $request->originID;
            $movement->category_id = $request->categoryID;
            $movement->description = $request->description;

            $movement->save();

            return redirect()->route('panel.data')->with('success', 'Movimentação atualizada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar movimentação. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($dataID)
    {
        try {
            $movement = Movement::findOrFail($dataID);
            $movement->delete();

            return redirect()->route('panel.data')->with('success', 'Movimentação excluída');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir movimentação. Por favor, tente novamente.',
            ]);
        }
    }
}
