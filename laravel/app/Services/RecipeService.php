<?php

namespace App\Services;

use App\Models\MongoDB\Recipe;
use App\Repositories\BigQueryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use RuntimeException;

class RecipeService
{
    private BigQueryRepository $bigQueryRepository;

    public function __construct()
    {
        if (!app()->environment('local', 'testing')) {
            $this->bigQueryRepository = new BigQueryRepository('recipes');
        }
    }

    /**
     * @return LengthAwarePaginator<Recipe>
     */
    public function getRecipesWithPantryMatches(): LengthAwarePaginator
    {
        if (app()->environment('local', 'testing')) {
            /** @var LengthAwarePaginator<Recipe> */
            return Recipe::withPantryMatches();
        }

        $queryResults = $this->bigQueryRepository->findWithPantryMatches();
        // FIXME: Implement mapping logic
        return new LengthAwarePaginator([], 0, 10);
    }

    public function findById(string $id): Recipe
    {
        if (app()->environment('local', 'testing')) {
            $recipe = Recipe::query()->find($id);

            if (!$recipe) {
                throw new RuntimeException('Recipe not found');
            }

            return $recipe;
        }

        $queryResult = $this->bigQueryRepository->findRecipeById($id);
        //FIXME: Implement mapping logic
        return new Recipe();
    }
}
