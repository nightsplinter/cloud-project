<?php

namespace App\Livewire;

use App\Models\MongoDB\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PantryItem;
use Illuminate\Support\Collection;
use MongoDB\BSON\ObjectId;
use Exception;

class RecipeFilterTable extends Component
{
    use WithPagination;

    /** @var string */
    protected $paginationTheme = 'custom-tailwind';

    /** @var array<string, string> */
    public array $servingsOptions = [
        'all' => 'All',
        '1-2' => '1-2 Persons',
        '3-4' => '3-4 Persons',
        '5-6' => '5-6 Persons',
        '7+' => '7+ Persons'
    ];

    /** @var array<string, string> */
    public array $categoryOptions = [];

    /** @var array<mixed> */
    public array $pantryOptions = [];

    /** @var array<string, string> */
    public array $sortOptions = [
        'matching_desc' => 'Matching Ingredients (High to Low)',
        'matching_asc' => 'Matching Ingredients (Low to High)',
        'similar_desc' => 'Similar Ingredients (High to Low)',
        'similar_asc' => 'Similar Ingredients (Low to High)',
    ];

    public string $search = '';
    public string $servings = 'all';
    public string $category = 'All';
    public string $sort = 'matching_desc';

    /** @var array<string> */
    public array $pantry = [];
    public bool $noResults = false;

    public bool $isOpen = false;

    public function togglePantryDropdown(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    /** @var array<string, array<string, mixed>> */
    protected $queryString = [
        'search' => ['except' => ''],
        'servings' => ['except' => 'all'],
        'category' => ['except' => 'All'],
        'sort' => ['except' => 'matching_desc'],
        'pantry' => ['except' => [], 'as' => 'ingredients'],
    ];

    public function mount(): void
    {
        /** @var array<string, string> */
        $categories = Recipe::pluck('categories')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $this->categoryOptions = $categories;
        array_unshift($this->categoryOptions, 'All');

        /** @var Collection<int, PantryItem> $pantryItems */
        $pantryItems = PantryItem::where('user_id', auth()->id())
            ->with('ingredient')
            ->orderBy('expiration_date', 'asc')
            ->get();

        if ($pantryItems->count() > 0) {

            $this->pantryOptions = $pantryItems->values()
                ->map(function (PantryItem $pantryItem, $index) {

                    if (!isset($pantryItem['ingredient'])) {
                        throw new Exception('Ingredient not found');
                    }

                    /** @phpstan-ignore-next-line */ // Needed for the ingredient property
                    $name = $pantryItem['ingredient']['name'];

                    /** @var string|null */ // Needed for the ingredient property
                    $expDate = $pantryItem->expiration_date;

                    /** @phpstan-ignore-next-line */ // Needed for the ingredient property
                    $id = $pantryItem['ingredient']['_id'];
                    /** @phpstan-ignore-next-line */
                    $base64Id = base64_encode((string) $id);

                    return [
                        'base64Id' => $base64Id,
                        'label' => $expDate /** @phpstan-ignore-next-line */ // Needed for the ingredient property
                            ? $name . ' (' . 'EXP: ' . $expDate . ')'
                            : $name
                    ];
                })->toArray();
        }
    }

    /**
     * Reset pagination when filters change
     * @param string $name
     * @return void
     */
    public function updating(string $name): void
    {
        if (in_array(
            $name,
            ['search', 'servings', 'category', 'sort']
        )) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        $query = Recipe::query()->select(
            '_id',
            'name',
            'picture',
            'categories',
            'servings',
            'ingredients',
            'author',
            'source'
        );

        // Apply search filter
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply pantry items filter
        if (!empty($this->pantry)) {
            $ids = array_map(fn ($pantryItem) =>
                base64_decode($pantryItem), $this->pantry);

            foreach (array_unique($ids) as $id) {
                /** @phpstan-ignore-next-line */
                $query->whereRaw([
                    '$or' => array_map(fn ($index)
                    => ['ingredients.' . $index . '.0' =>
                    ['$all' => [new ObjectId($id)]]
                    ], range(0, 100))
                ]);
            }
        }

        // Apply servings filter
        if ('all' !== $this->servings) {
            switch ($this->servings) {
                case '1-2':
                    $query->whereIn('servings', [1, 2, '1', '2']);
                    break;
                case '3-4':
                    $query->whereIn('servings', [3, 4, '3', '4']);
                    break;
                case '5-6':
                    $query->whereIn('servings', [5, 6, '5', '6']);
                    break;
                case '7+':
                    $query->where('servings', '>=', 7)
                        ->orWhere('servings', '>=', '7');
                    break;
            }
        }

        // Apply category filter
        if ('All' !== $this->category) {
            $query->where('categories', 'all', [$this->category]);
        }

        /** @var LengthAwarePaginator<Recipe> $recipes */
        $recipes = $query->paginate(12);

        // Add matching ingredients and similar matching ingredients
        $recipes->through(function (Recipe $recipe) {
            $recipe->matching_ingredients = $recipe->getMatchingIngredients();
            $recipe->similar_matching_ingredients = $recipe->getSimilarMatchingIngredients();
            $recipe->total_ingredients = count($recipe->ingredients);
            return $recipe;
        });

        // Sort the collection based on the selected sort option
        $sortedCollection = $recipes->getCollection();

        switch ($this->sort) {
            case 'matching_desc':
                $sortedCollection = $sortedCollection->sortByDesc(
                    fn (Recipe $recipe): int => count($recipe->matching_ingredients)
                );
                break;
            case 'matching_asc':
                $sortedCollection = $sortedCollection->sortBy(
                    fn (Recipe $recipe): int => count($recipe->matching_ingredients)
                );
                break;
            case 'similar_desc':
                $sortedCollection = $sortedCollection->sortByDesc(
                    fn (Recipe $recipe): int => count($recipe->similar_matching_ingredients)
                );
                break;
            case 'similar_asc':
                $sortedCollection = $sortedCollection->sortBy(
                    fn (Recipe $recipe): int => count($recipe->similar_matching_ingredients)
                );
                break;
        }

        $recipes->setCollection($sortedCollection);

        $this->noResults = $recipes->isEmpty();

        return view('livewire.pages.recipe.recipe-filter-table', [
            'recipes' => $recipes
        ]);
    }
}
