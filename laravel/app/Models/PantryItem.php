<?php

namespace App\Models;

use App\Models\MongoDB\Ingredient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Ingredient|null $ingredient
 * @property-read User|null $user
 * @property int $id
 * @property int $user_id
 * @property float|int $quantity
 * @property string|null $expiration_date
 * @property string $ingredient_id
 * @property int|null $unit_index
 */
class PantryItem extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'quantity',
        'expiration_date',
        'ingredient_id',
        'unit_index',
    ];

    /**
     * Returns the ingredient of the pantry item.
     *
     * @return BelongsTo<Ingredient, PantryItem>
     */
    public function ingredient(): BelongsTo
    {
        /** @var BelongsTo<Ingredient, PantryItem> */
        return $this->belongsTo(
            Ingredient::class,
            'ingredient_id',
            '_id'
        );
    }

    /**
     * Returns the user of the pantry item.
     *
     * @return BelongsTo<User, PantryItem>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, PantryItem> */
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    /**
     * Returns the pantry items of the authenticated user.
     * @return array<mixed>
     */
    public static function getMappedUserPantryItems(): array
    {
        return self::where('user_id', auth()->id())
            ->with('ingredient')
            ->get()
            ->toArray();
    }

    /**
     * Returns the unique categories of the pantry items of the authenticated user.
     *
     * @return array<int, string>
     */
    public static function getUniqueUserCategories(): array
    {
        $data = self::getUserCategories();
        $categories = [];

        foreach ($data as $categoryArray) {
            foreach ($categoryArray as $category) {
                if ('' === $category) {
                    continue;
                }
                $categories[] = $category;
            }
        }

        /** @var array<int, string> */
        return array_values(array_unique($categories));
    }

    /**
     * Returns the categories of the pantry items of the authenticated user.
     *
     * @return array<array<string>>
     */
    public static function getUserCategories(): array
    {
        /** @var Collection<int, PantryItem> $items */
        $items = self::where('user_id', auth()->id())
            ->with('ingredient')
            ->get();

        if ($items->isEmpty()) {
            return [];
        }

        $categories = $items
            ->map(function (PantryItem $item): array {
                $ingredient = $item->ingredient;
                if (null === $ingredient) {
                    return [];
                }
                $categories = $ingredient->getAttribute('categories');
                /** @var array<string> */
                return $categories ?? [];
            })
            ->toArray();

        /** @var array<array<string>> */
        return $categories;
    }
}
