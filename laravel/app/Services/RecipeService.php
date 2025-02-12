<?php

namespace App\Services;

use App\Models\MongoDB\Recipe;
use App\Repositories\BigQueryRepository;

class RecipeService
{
    private BigQueryRepository $bigQueryRepository;

    public function __construct()
    {
        if (!app()->environment('local', 'testing')) {
            $this->bigQueryRepository = new BigQueryRepository('recipes');
        }
    }

    public function findById(string $id): Recipe
    {
        if (app()->environment('local', 'testing')) {
            return new Recipe()->getRecipeByIdWithPantryMatches($id);
        }

        $queryResult = $this->bigQueryRepository->findRecipeById($id);
        //FIXME: Implement mapping logic
        return new Recipe();
    }
}
