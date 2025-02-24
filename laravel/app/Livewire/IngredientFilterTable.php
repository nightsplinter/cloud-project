<?php

namespace App\Livewire;

use App\Models\PantryItem;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class IngredientFilterTable extends Component
{
    use WithPagination;

    /** @var string */
    protected $paginationTheme = 'custom-tailwind';

    /** @var array<string, string> */
    public array $expirationDateOptions = [
        'all' => 'All',
        'expired' => 'Expired',
        'expiring' => 'In the next 7 days',
        'not-expiring' => 'Not Expiring',
        'none' => 'None',
    ];

    /** @var array<int, string> */
    public array $categoryOptions = [];

    public string $expirationDate = 'all';
    public string $category = 'All';
    public string $search = '';
    public bool $noResults = false;

    /** @var array<string, array<string, string>> */
    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => 'All'],
        'expirationDate' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        /** @var array<int, string> */
        $categories = PantryItem::getUniqueUserCategories();

        $this->categoryOptions = $categories;
        array_unshift($this->categoryOptions, 'All');
    }

    /**
     * Updating the search, category, and expirationDate properties
     * @param string $name
     * @return void
     */
    public function updating(string $name): void
    {
        if ('search' === $name || 'category' === $name || 'expirationDate' === $name) {
            $this->resetPage();
        }
    }

    public function delete(int $itemId): void
    {
        $item = PantryItem::find($itemId);
        if ($item instanceof PantryItem) {
            $item->delete();
            redirect()->route('dashboard');
        }
    }

    public function render(): View
    {
        $query = PantryItem::query()
            ->where('user_id', auth()->id());

        // Expiration Date Filter
        $now = now()->startOfDay();
        switch ($this->expirationDate) {
            case 'expired':
                $query->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '<', $now);
                break;
            case 'expiring':
                $query->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '<=', $now->copy()->addDays(7))
                    ->whereDate('expiration_date', '>', $now);
                break;
            case 'not-expiring':
                $query->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '>', $now);
                break;
            case 'none':
                $query->whereNull('expiration_date');
                break;
        }

        $items = $query->get();

        // Category and Search Filter
        if ('All' !== $this->category || !empty($this->search)) {
            $items = $items->filter(function ($item) {
                $ingredient = $item->ingredient;

                if (null === $ingredient) {
                    return false;
                }

                $ingredient = $ingredient->toArray();

                /** @var array<int, string>|null */
                $categories = $ingredient['categories'];

                // Category Filter
                if ('All' !== $this->category && (
                    null === $categories ||
                    !in_array($this->category, $categories)
                )) {
                    return false;
                }

                // Search Filter

                /** @var string */
                $name = $ingredient['name'];

                return empty($this->search)
                    || str_contains(
                        mb_strtolower($name),
                        mb_strtolower($this->search)
                    );
            });

        }

        $page = Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(), $perPage, $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $this->noResults = $items->isEmpty();

        return view('livewire.pages.pantry.ingredient-filter-table', [
            'items' => $paginatedItems,
        ]);
    }

}
