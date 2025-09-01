<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Log;

class UserController
{

    public function index()
    {
        $users = User::all();
        
        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }
    public function store(RegisterRequest $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return redirect()->route('panel.users')->with('success', 'Usuário criado');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->route('userID'));

;            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            
            $user->save();

            return redirect()->route('panel.users')->with('success', 'Usuário atualizado');

        } catch (Exception $e) {
            Log::info($e->getMessage());
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();

            return redirect()->route('panel.users')->with('success', 'Usuário excluído');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir o usuário. Por favor, tente novamente.',
            ]);
        }
    }

}