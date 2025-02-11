<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Services\RecipeService;

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
        $recipes = new RecipeService()->getRecipesWithPantryMatches();
        return view('recipe-finder', ['recipes' => $recipes]);
    }

    /**
     * Show a recipe by id.
     * @param String $id Base64 encoded _id of the recipe.
     *
     * @return View
     */
    public function detail(String $id): View
    {
        $convertId = base64_decode($id);
        $recipe =  new RecipeService()->findById($convertId);

        return view('recipe-detail', [
            'recipe' => $recipe,
        ]);
    }
}
