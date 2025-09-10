<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\CreateMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use Exception;
use Inertia\Inertia;

class MemberController
{
    public function index()
    {
        $members = Member::orderBy('name')->get();

        return Inertia::render('Members', [
            'members' => $members,
        ]);
    }

    public function store(CreateMemberRequest $request)
    {
        try {
            Member::create([
                'name' => $request->name,
            ]);

            return redirect()->route('panel.members')->with('success', 'Membro criado');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao criar membro. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateMemberRequest $request)
    {
        try {
            $member = Member::findOrFail($request->route('memberID'));

            $member->name = $request->name;

            $member->save();

            return redirect()->route('panel.members')->with('success', 'Membro atualizado');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar membro. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($memberID)
    {
        try {
            $member = Member::findOrFail($memberID);
            $member->delete();

            return redirect()->route('panel.members')->with('success', 'Membro excluído');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir o membro. Por favor, tente novamente.',
            ]);
        }
    }
}
