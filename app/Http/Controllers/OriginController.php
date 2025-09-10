<?php

namespace App\Http\Controllers;

use App\Http\Requests\Origin\CreateOriginRequest;
use App\Http\Requests\Origin\UpdateOriginRequest;
use App\Models\Member;
use App\Models\Origin;
use Exception;
use Inertia\Inertia;

class OriginController
{
    public function index()
    {
        $origins = Origin::with('member')->orderBy('name')->get();
        $members = Member::orderBy('name')->get();

        return Inertia::render('Origins', [
            'origins' => $origins,
            'members' => $members,
        ]);
    }

    public function store(CreateOriginRequest $request)
    {
        try {
            Origin::create([
                'name' => $request->name,
                'member_id' => $request->memberID,
                'payday' => $request->payday,
            ]);

            return redirect()->route('panel.origins')->with('success', 'Origem criada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar origem. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateOriginRequest $request)
    {
        try {
            $origin = Origin::findOrFail($request->route('originID'));

            $origin->name = $request->name;
            $origin->member_id = $request->memberID;
            $origin->payday = $request->payday;

            $origin->save();

            return redirect()->route('panel.origins')->with('success', 'Origem atualizada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar origem. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($originID)
    {
        try {
            $origin = Origin::findOrFail($originID);
            $origin->delete();

            return redirect()->route('panel.origins')->with('success', 'Origem excluída');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir origem. Por favor, tente novamente.',
            ]);
        }
    }
}
