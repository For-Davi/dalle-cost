<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Exception;
use App\Models\Category;
use Inertia\Inertia;
use Log;

class CategoryController
{

    public function index()
    {
        $categories = Category::all();
        
        return Inertia::render('Categories', [
            'categories' => $categories,
        ]);
    }
    public function store(CreateCategoryRequest $request)
    {
        try {
            Category::create([
                'name' => $request->name,
            ]);

            return redirect()->route('panel.category')->with('success', 'Categoria criada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar categoria. Por favor, tente novamente.',
            ]);
        }
    }

    public function update(UpdateCategoryRequest $request)
    {
        try {
            $category = Category::findOrFail($request->route('categoryID'));

;           $category->name = $request->name;
            
            $category->save();

            return redirect()->route('panel.category')->with('success', 'Categoria atualizada');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao atualizar categoria. Por favor, tente novamente.',
            ]);
        }
    }

    public function destroy($categoryID)
    {
        try {
            $category = Category::findOrFail($categoryID);
            $category->delete();

            return redirect()->route('panel.category')->with('success', 'Categoria excluída');

        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao excluir categoria. Por favor, tente novamente.',
            ]);
        }
    }

}