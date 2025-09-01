<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

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

            $users = User::all();

            return Inertia::render('Users', [
                'users' => $users,
            ]);

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $user = User::findOrFail($request->id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            
            $user->save();

            $users = User::all();

            return Inertia::render('Users', [
                'users' => $users,
                'message' => 'Usuário atualizado',
            ]);

        } catch (Exception $e) {
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

            $users = User::all();

            return Inertia::render('Users', [
                'users' => $users,
                'message' => 'Usuário excluído',
            ]);

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir o usuário. Por favor, tente novamente.',
            ]);
        }
    }

}