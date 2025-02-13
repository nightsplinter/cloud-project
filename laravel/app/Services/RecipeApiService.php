<?php

namespace App\Services;

use App\Models\MongoDB\Ingredient;
use App\Models\MongoDB\Recipe;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use MongoDB;

class RecipeApiService
{
    private string $apiKey;
    private string $apiHost = 'spoonacular-recipe-food-nutrition-v1.p.rapidapi.com';
    private string $baseUrl = 'https://spoonacular-recipe-food-nutrition-v1.p.rapidapi.com';

    public function __construct()
    {

        $apiKey = config('services.spoonacular.api_key');
        if (!is_string($apiKey) || empty($apiKey)) {
            throw new Exception('API key not found');
        }
        $this->apiKey = $apiKey;
    }

    /**
     * Fetch and store recipes from the API
     *
     * @param int $numberOfRecipes
     * @return array<string, mixed>
     */
    public function fetchAndStoreRecipes(int $numberOfRecipes = 1): array
    {
        try {
            $response = $this->fetchFromApi($numberOfRecipes);

            if (!isset($response['recipes']) || empty($response['recipes']) ||
                !is_array($response['recipes'])) {
                Log::error('No recipes found in API response');
                return ['error' => 'No recipes found'];
            }

            $savedRecipes = [];
            foreach ($response['recipes'] as $recipeData) {
                if (!is_array($recipeData) || empty($recipeData)) {
                    Log::error('Invalid recipe data');
                    continue;
                }
                $savedRecipes[] = $this->processAndStoreRecipe($recipeData);
            }

            Log::info(count($savedRecipes) . ' recipes processed');

            return [
                'success' => true,
                'message' => count($savedRecipes) . ' recipes processed',
                'recipes' => $savedRecipes,
            ];

        } catch (Exception $error) {
            $msg = $error->getMessage();
            Log::error('Error fetching recipes: ' . $msg);
            return ['error' => $msg];
        }
    }

    /**
     * Fetch recipes from the API
     *
     * @param int $number Number of recipes to fetch
     * @return Response
     */
    private function fetchFromApi(int $number): Response
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->apiHost,
        ])->get($this->baseUrl . '/recipes/random', [
            'number' => $number,
        ]);

        if (!$response->successful()) {
            throw new Exception('API request failed: '
                . $response->body());
        }

        return $response;
    }

    /**
     * Process and attach ingredients to a recipe
     *
     * @param array<mixed> $recipeData Recipe
     * @return Recipe
     */
    private function processAndStoreRecipe(array $recipeData): Recipe
    {
        DB::beginTransaction();
        try {

            $recipe = Recipe::updateOrCreate(
                ['name' => $recipeData['title']],
                [
                    'description' => $recipeData['summary'] ?? null,
                    'servings' => $recipeData['servings'] ?? 1,
                    'picture' => $recipeData['image'] ?? null,
                    'steps' => $recipeData['instructions'] ?? null,
                    'source' => $recipeData['sourceUrl'] ?? null,
                    'categories' => $this->extractCategories($recipeData),
                    'author' => [
                        null, // Author ID from users table
                        $recipeData['creditsText'] ?? 'Unknown', // Author name
                    ],
                ]
            );

            if (isset($recipeData['extendedIngredients']) &&
                is_array($recipeData['extendedIngredients'])) {
                $this->processIngredients(
                    $recipe,
                    $recipeData['extendedIngredients']
                );
            } else {
                throw new Exception('No ingredients found in recipe data');
            }

            DB::commit();
            return $recipe;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing recipe: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process and store ingredients
     * @param Recipe $recipe Recipe
     * @param array<mixed> $ingredientsData Ingredients data
     * @return void
     */
    private function processIngredients(Recipe $recipe, array $ingredientsData): void
    {
        DB::beginTransaction();
        try {
            $recipeIngredients = [];
            $rawStrings = [];

            foreach ($ingredientsData as $ingredientData) {
                if (!is_array($ingredientData) || empty($ingredientData)) {
                    Log::error('Invalid ingredient data');
                    continue;
                }
                $name = $ingredientData['name'];
                if (!is_string($name) || empty($name)) {
                    Log::error('Invalid ingredient name');
                    continue;
                }

                // Create or update ingredient
                $ingredient = Ingredient::updateOrCreate(
                    ['name' => mb_strtolower($name)],
                    [
                        'picture' => isset($ingredientData['image']) && is_string($image = $ingredientData['image'])
                        ? "https://spoonacular.com/cdn/ingredients_100x100/" . $image
                        : null,
                        'categories' => [$ingredientData['aisle'] ?? 'Uncategorized'],
                        'unit' => $this->extractUnit($ingredientData),
                    ]
                );

                $ingredienId = $ingredient->_id;
                if (!isset($ingredienId) || !is_string($ingredienId)) {
                    throw new Exception('No ID found for ingredient');
                }

                $objectID = (object) new MongoDB\BSON\ObjectId($ingredienId);

                if (!is_array($ingredientData['measures']) ||
                    !is_array($ingredientData['measures']['us']) ||
                    !isset($ingredientData['measures']['us']['amount'])) {
                    throw new Exception('No amount found for ingredient');
                }

                $amountValue = $ingredientData['measures']['us']['amount'];
                $amount = is_numeric($amountValue) ? (string) $amountValue : $amountValue;


                $recipeIngredients[] = [$objectID, $amount];
                $rawStrings[] = $ingredientData['original'] ?? '';
            }
            $recipe->update(['ingredients' => $recipeIngredients]);
            $recipe->update(['raw_str' => $rawStrings]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing ingredients: '
                . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Extract categories from recipe data
     *
     * @param array<mixed> $recipeData
     * @return array<mixed>
     */
    private function extractCategories(array $recipeData): array
    {
        $categories = [];
        $categorieList = ['vegetarian', 'vegan', 'glutenFree', 'dairyFree'];

        foreach ($categorieList as $category) {
            if (isset($recipeData[$category])
                && true === $recipeData[$category]) {
                $categories[] = $category;
            }
        }

        if (isset($recipeData['dishTypes'])
            && is_array($recipeData['dishTypes'])) {
            $categories = array_merge(
                $categories,
                $recipeData['dishTypes']
            );
        }

        return array_unique($categories);
    }

    /**
     * Extract unit from ingredient data
     *
     * @param array<mixed> $ingredientData
     * @return array<int, mixed>|null
     */
    private function extractUnit(array $ingredientData): ?array
    {

        if (!isset($ingredientData['measures'])
            || !is_array($ingredientData['measures'])
            || !isset($ingredientData['measures']['us'])
            || !is_array($ingredientData['measures']['us'])) {
            return null;
        }

        $measures = $ingredientData['measures']['us'];
        $units = [];

        if (isset($measures['unitShort']) && !empty($measures['unitShort'])) {
            $units[] = $measures['unitShort'];
        }

        if (isset($measures['unitLong']) && !empty($measures['unitLong'])) {
            $units[] = $measures['unitLong'];
        }

        return !empty($units) ? array_unique($units) : null;
    }
}
