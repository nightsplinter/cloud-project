<?php

namespace App\Models\MongoDB;

use App\Models\PantryItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Builder;

/**
 * @property array<int, array<int, mixed>> $ingredients
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
 * @property int $matching_ingredients
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
     * Get the count of matching ingredients in the pantry.
     *
     * @return int
     */
    public function getMatchingIngredientsCount(): int
    {
        $ingredientIds = collect($this->ingredients)
            ->pluck('0')
            ->unique()
            ->values()
            ->toArray();

        return PantryItem::whereIn('ingredient_id', $ingredientIds)->count();
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
            $recipe->matching_ingredients = $recipe->getMatchingIngredientsCount();
            $recipe->total_ingredients = count($recipe->ingredients);
            return $recipe;
        });

        // Create a new collection sorted by matching_ingredients
        $sortedCollection = $recipes->getCollection()->sortByDesc('matching_ingredients');
        $recipes->setCollection($sortedCollection);

        return $recipes;
    }
}
