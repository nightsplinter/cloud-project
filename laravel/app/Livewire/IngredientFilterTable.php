<?php

namespace App\Livewire;

use App\Models\PantryItem;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IngredientFilterTable extends Component
{
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

    /** @var array<int, array<string, mixed>> */
    public array $userPantryItems = [];

    /** @var array<int, array<string, mixed>> */
    public array $allUserPantryItems = [];

    public string $search = '';
    public bool $noResults = false;

    /**
     * Initialize the component.
     *
     * @param array<int, array<string, mixed>> $userPantryItems
     */
    public function mount(array $userPantryItems): void
    {
        $this->userPantryItems = $userPantryItems;
        $this->allUserPantryItems = $userPantryItems;

        /** @var array<int, string> */
        $categories = PantryItem::getUniqueUserCategories();

        $this->categoryOptions = $categories;
        array_unshift($this->categoryOptions, 'All');
    }

    public function updatedSearch(): void
    {
        $this->applyFilters();
    }

    public function updatedCategory(): void
    {
        $this->applyFilters();
    }

    public function updatedExpirationDate(): void
    {
        $this->applyFilters();
    }

    /**
     * Apply all active filters on the pantry items.
     */
    public function applyFilters(): void
    {
        $filteredItems = $this->allUserPantryItems;

        if (isset($this->category) && 'All' !== $this->category) {
            $filteredItems = array_filter(
                $filteredItems,
                fn (array $ingredient): bool => isset($ingredient['ingredient'])
                        && is_array($ingredient['ingredient'])
                        && is_array($ingredient['ingredient']['categories'])
                        && in_array($this->category, $ingredient['ingredient']['categories'], true)
            );
        }

        $filteredItems = $this->applyExpirationDateFilter($filteredItems);
        $this->userPantryItems = $filteredItems;

        if (isset($this->search) && mb_strlen($this->search) > 0) {
            $this->userPantryItems = array_filter(
                $filteredItems,
                fn (array $ingredient): bool => isset($ingredient['ingredient'])
                        && is_array($ingredient['ingredient'])
                        && is_string($ingredient['ingredient']['name'])
                        && str_contains(
                            mb_strtolower($ingredient['ingredient']['name']),
                            mb_strtolower($this->search)
                        )
            );
        }
        $this->noResults = empty($filteredItems);
    }

    /**
     * Apply expiration date filter.
     *
     * @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    private function applyExpirationDateFilter(array $items): array
    {
        $now = now();

        switch ($this->expirationDate) {
            case 'expired':
                return array_filter(
                    $items,
                    fn (array $item): bool => isset($item['expiration_date'])
                            && is_string($item['expiration_date'])
                            && mb_strlen($item['expiration_date']) > 0
                            && $item['expiration_date'] < $now
                );

            case 'expiring':
                return array_filter(
                    $items,
                    fn (array $item): bool => isset($item['expiration_date'])
                            && is_string($item['expiration_date'])
                            && mb_strlen($item['expiration_date']) > 0
                            && $item['expiration_date'] < $now->addDays(7)
                );

            case 'not-expiring':
                return array_filter(
                    $items,
                    fn (array $item): bool => isset($item['expiration_date'])
                            && is_string($item['expiration_date'])
                            && mb_strlen($item['expiration_date']) > 0
                            && $item['expiration_date'] > $now->addDays(7)
                );

            case 'none':
                return array_filter(
                    $items,
                    fn (array $item): bool => !isset($item['expiration_date'])
                            || !is_string($item['expiration_date'])
                            || 0 === mb_strlen($item['expiration_date'])
                );

            case 'all':
            default:
                return $items;
        }
    }

    /**
     * Delete a pantry item.
     */
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
        return view('livewire.pages.pantry.ingredient-filter-table');
    }
}
