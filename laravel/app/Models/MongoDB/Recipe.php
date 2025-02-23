<?php

namespace App\Models\MongoDB;

use App\Models\PantryItem;
use Illuminate\Pagination\LengthAwarePaginator;
use MongoDB\Laravel\Eloquent\Builder;
use RuntimeException;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property array<int, array{0: string, 1: string, 2: string}> $ingredients
 * @property string $name
 * @property array<int, string> $categories
 * @property string $picture
 * @property int $servings
 * @property string $source
 * @property string $raw_str
 * @property array<int, string> $steps
 * @property string $description
 * @property string $author
 * @property string $_id
 * @property array<string> $matching_ingredients
 * @property array<string> $ingredient_pictures
 * @property array<string> $similar_matching_ingredients
 * @property int $total_ingredients
 */
class Recipe extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'recipes';

    protected $fillable = [
        'ingredients',
        'name',
        'categories',
        'picture',
        'servings',
        'source',
        'raw_str',
        'steps',
        'description',
        'author',
    ];

    protected $visible = [
        'ingredients',
        'name',
        'categories',
        'picture',
        'servings',
        'source',
        'raw_str',
        'steps',
        'description',
        'author',
        '_id',
    ];

    /**
     * Get the similar matching ingredients in the pantry.
     *
     * @return array<string>
     */
    public function getSimilarMatchingIngredients(): array
    {
        $ingredientIds = collect($this->ingredients)
            ->pluck('0')
            ->unique()
            ->values()
            ->toArray();

        /** @var array<string> $ingredientNames */
        $ingredientNames = Ingredient::whereIn('_id', $ingredientIds)
            ->pluck('name')
            ->toArray();

        /** @var Collection<int, PantryItem> $pantryItems */
        $pantryItems = PantryItem::where('user_id', auth()->id())
            ->with('ingredient')
            ->get();

        $similarPantryItems = [];

        foreach ($ingredientNames as $ingredientName) {
            foreach ($pantryItems as $pantryItem) {
                /** @var Ingredient|null $pantryItemIngredient */
                $pantryItemIngredient = $pantryItem->ingredient;
                if (!$pantryItemIngredient) {
                    continue;
                }
                $pantryItemIngredient = $pantryItemIngredient->toArray();
                /** @var string $pantryItemName */
                $pantryItemName = $pantryItemIngredient['name'];
                similar_text(
                    (string) $ingredientName,
                    $pantryItemName,
                    $percent
                );

                if (($percent > 55.0 && $percent < 100.0) ||
                    str_contains((string) $ingredientName, $pantryItemName)) {
                    $similarPantryItems[] = $pantryItemName;
                }
            }
        }

        return array_values(array_unique($similarPantryItems));
    }

    /**
     * Get the matching ingredients in the pantry.
     *
     * @return array<string>
     */
    public function getMatchingIngredients(): array
    {
        $ingredientIds = collect($this->ingredients)
            ->pluck('0')
            ->unique()
            ->values()
            ->toArray();

        /** @var Collection<int, PantryItem> $matchingPantryItems */
        $matchingPantryItems = PantryItem::where('user_id', auth()->id())
            ->whereIn('ingredient_id', $ingredientIds)
            ->with('ingredient')
            ->get();

        $matching = [];
        foreach ($matchingPantryItems as $match) {
            $matching[] = $this->formatIngredientString($match);
        }
        return $matching;
    }

    /**
     * Format the ingredient string.
     *
     * @param PantryItem $pantryItem
     * @return string The formatted ingredient string.
     */
    private function formatIngredientString(PantryItem $pantryItem): string
    {
        $parts = [];
        $ingredient = $pantryItem->ingredient;
        if (!$ingredient) {
            return '';
        }

        $ingredient = $ingredient->toArray();

        if (!$ingredient) {
            return '';
        }

        $parts[] = $ingredient['name'];

        if ($pantryItem->quantity) {
            $parts[] = '(' . (string) $pantryItem->quantity;
        }

        if (null !== $pantryItem->unit_index &&
            null !== $ingredient['unit']) {
            /** @var array<string> $units */
            $units = $ingredient['unit'];
            $parts[] = $units[$pantryItem->unit_index];
        }

        if (count($parts) > 1) {
            $parts[] = ')';
        }

        return implode(' ', $parts);
    }

    /**
     * Get the recipes with matching pantry items.
     *
     * @param Builder $query
     * @param int $perPage
     * @return LengthAwarePaginator<Recipe>
     */
    public function scopeWithPantryMatches(Builder $query, int $perPage = 20): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<Recipe> $recipes */
        $recipes = $query->select(
            '_id',
            'name',
            'picture',
            'categories',
            'servings',
            'ingredients',
            'author'
        )->paginate($perPage);

        $recipes->through(function (Recipe $recipe) {
            $recipe->matching_ingredients = $recipe->getMatchingIngredients();
            $recipe->similar_matching_ingredients = $recipe->getSimilarMatchingIngredients();
            $recipe->total_ingredients = count($recipe->ingredients);
            return $recipe;
        });

        // Create a new collection sorted by matching_ingredients
        $sortedCollection = $recipes->getCollection()
            ->sortByDesc(fn (Recipe $recipe): int => count($recipe->matching_ingredients))
            ->sortByDesc(fn (Recipe $recipe): int => count($recipe->similar_matching_ingredients));
        $recipes->setCollection($sortedCollection);

        return $recipes;
    }

    /**
     * Get ingredient pictures mapped by name.
     *
     * @return array<string, string>
     */
    public function getIngredientPictures(): array
    {
        $ingredientIds = collect($this->ingredients)
            ->pluck('0')
            ->unique()
            ->values()
            ->toArray();

        /** @var array<string, string> $pictures */
        $pictures = Ingredient::whereIn('_id', $ingredientIds)
            ->get()
            ->mapWithKeys(fn (Ingredient $ingredient) => [$ingredient->name => $ingredient->picture ?? ''])
            ->toArray();

        return $pictures;
    }

    /**
     * Get a recipe by ID with matching pantry items.
     *
     * @param string $id
     * @return Recipe
     * @throws RuntimeException
     */
    public function getRecipeByIdWithPantryMatches(string $id): Recipe
    {
        /** @var Recipe|null $recipe */
        $recipe = $this->find($id);

        if (!$recipe) {
            throw new RuntimeException('Recipe not found');
        }

        $recipe->ingredient_pictures = array_values($recipe->getIngredientPictures());
        $recipe->matching_ingredients = $recipe->getMatchingIngredients();
        $recipe->similar_matching_ingredients = $recipe->getSimilarMatchingIngredients();

        return $recipe;
    }
}
