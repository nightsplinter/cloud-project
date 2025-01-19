<?php

namespace App\Livewire\Forms;

use App\Models\MongoDB\Ingredient;
use App\Models\PantryItem;
use Livewire\Component;

/**
 * This class is a Livewire component that is used to create or edit a pantry item.
 */
class PantryItemForm extends Component
{
    /**
     * Indicates if an entry is selected from the search results.
     */
    public $isEntrySelected = false;

    public $name;

    public $unit = '';

    public $quantity;

    public $expiration_date;

    /**
     * The ingredients that match the search query.
     */
    public $ingredients = [];

    /**
     * The index of the highlighted ingredient in the search results.
     */
    public $highlightIndex = 0;

    public $units = [];

    public $item;

    public function mount($item)
    {
        if (isset($item->id)) {
            $this->name = $item->ingredient->name;
            $this->isEntrySelected = true;
            $this->quantity = $item->quantity;
            $this->unit = $item->unit;
            $this->expiration_date = $item->expiration_date;
        }
    }

    public function updatedName($value)
    {
        $this->isEntrySelected = false;
        if (strlen($value) < 2) {
            $this->ingredients = [];
            return;
        }

        $this->ingredients = Ingredient::where('name', 'like', '%'.$value.'%')
            ->select('name', 'id', 'unit', 'picture')
            ->limit(5)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
        $this->highlightIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex < count($this->ingredients) - 1) {
            $this->highlightIndex++;
        }
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex > 0) {
            $this->highlightIndex--;
        }
    }

    public function selectIngredient($index = null)
    {
        if ($index === null) {
            $index = $this->highlightIndex;
        }

        if (isset($this->ingredients[$index])) {
            $this->name = $this->ingredients[$index]['name'];
            $this->isEntrySelected = true;
            $units = $this->ingredients[$index]['unit'];

            if (is_array($units)) {
                $this->units = $units;
            }
        }
    }

    public function resetIfInvalid()
    {
        $valid = collect($this->ingredients)
            ->contains('name', $this->name);

        if (! $valid) {
            $this->name = '';
            $this->ingredients = [];
            $this->units = [];
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|min:2|max:255',
            'quantity' => 'min:1|numeric|nullable',
            'unit' => 'min:1|nullable|max:255|in:'
                .implode(',', $this->units),
            'expiration_date' => 'date|after:today|nullable',
        ]);

        $selectedIngredient = Ingredient::where('name',
            $validated['name'])->first();

        if (! $selectedIngredient) {
            session()->flash('message', 'Ingredient not found.');

            return;
        }

        $ingredient_id = $selectedIngredient->id;
        $unit_index = null;

        if (!is_null($selectedIngredient->units)) {
            $unit_index = array_search($validated['unit'],
                $selectedIngredient->units);
        }

        $data = [
            'user_id' => auth()->id(),
            'quantity' => $validated['quantity'],
            'expiration_date' => $validated['expiration_date'],
            'ingredient_id' => $ingredient_id,
            'unit_index' => $unit_index,
        ];

        if ($this->item) {
            $this->item->update($data);
        } else {
            PantryItem::create($data);
        }
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.pages.pantry.pantry-item');
    }
}
