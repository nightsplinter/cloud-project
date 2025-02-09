<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * This controller is responsible for handling recipes.
 *
 */
class RecipeController extends Controller
{
    /**
     * Add a new recipe.
     * @return View
     */
    public function add(): View
    {
        return view('recipe');
    }

    /**
     * Edit a recipe by id.
     * @return View
     */
    public function edit(int $id): View
    {
        $recipe = null;

        return view('recipe', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Find a recipe by ingredients.
     * @return View
     */
    public function finder(): View
    {
        return view('recipe-finder');
    }
}
