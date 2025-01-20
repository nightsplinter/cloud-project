<?php
namespace App\Livewire;

use App\Models\PantryItem;
use Livewire\Component;

class IngredientFilterTable extends Component
{
    public array $expirationDateOptions = [
        'all' => 'All',
        'expired' => 'Expired',
        'expiring' => 'In the next 7 days',
        'not-expiring' => 'Not Expiring',
        'none' => 'None',
    ];

    public $categoryOptions;
    public $expirationDate;
    public $category;
    public $userPantryItems;
    public $allUserPantryItems;

    public $noResults = false;

    public function mount($userPantryItems)
    {
        $this->userPantryItems = $userPantryItems;
        $this->allUserPantryItems = $userPantryItems;
        $this->categoryOptions = PantryItem::getUniqueUserCategories();
        array_unshift($this->categoryOptions, 'All');
    }

    public function updatedCategory()
    {
        $this->applyFilters();
    }

    public function updatedExpirationDate()
    {
        $this->applyFilters();
    }

    /**
     * Apply all active filters on the pantry items.
     */
    public function applyFilters()
    {
        $filteredItems = $this->allUserPantryItems;

        if (isset($this->category) && $this->category !== 'All') {
            $filteredItems = array_filter($filteredItems,
                function ($ingredient) {
                    return is_array($ingredient['categories'])
                    && in_array($this->category,
                        $ingredient['categories']);
                });
        }

        $filteredItems = $this->applyExpirationDateFilter($filteredItems);

        $this->userPantryItems = $filteredItems;
        $this->noResults = empty($filteredItems);
    }

    /**
     * Apply expiration date filter.
     */
    private function applyExpirationDateFilter($items)
    {
        switch ($this->expirationDate) {
            case 'expired':
                return array_filter($items,
                    fn($item) =>
                    strlen(string: $item['expiration_date']) > 0
                    && $item['expiration_date'] < now()
                );
            case 'expiring':
                return array_filter($items,
                    fn($item) =>
                    strlen(string: $item['expiration_date']) > 0
                    && $item['expiration_date'] < now()->addDays(7));
            case 'not-expiring':
                return array_filter($items,
                    fn($item) =>
                    strlen(string: $item['expiration_date']) > 0
                    && $item['expiration_date'] > now()->addDays(7));
            case 'none':
                return array_filter($items,
                    callback: fn($item) =>
                    strlen(string: $item['expiration_date']) == 0
                );
            case 'all':
                return $items;
            default:
                return $items;
        }
    }

    /**
     * Delete a pantry item.
     */
    public function delete($itemId)
    {
        $item = PantryItem::find($itemId);

        if ($item) {
            $item->delete();
            return redirect()->route('dashboard');
        }
    }
    public function render()
    {
        return view('livewire.pages.pantry.ingredient-filter-table');
    }
}
