<?php

namespace App\Livewire\Forms;

use App\Models\MongoDB\Ingredient;
use App\Models\PantryItem;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Date;

/**
 * This class is a Livewire component that is used to create or edit a pantry item.
 */
class PantryItemForm extends Component
{
    /**
     * Indicates if an entry is selected from the search results.
     */
    public bool $isEntrySelected = false;

    public string $name = '';

    public string $unit = '';

    public ?int $quantity = null;

    public string $expiration_date = '';

    /**
     * The ingredients that match the search query.
     *
     * @var array<array{name: string, picture: string, _id: string, unit: array<string>|null}>
     */
    public array $ingredients = [];

    /**
     * The index of the highlighted ingredient in the search results.
     */
    public int $highlightIndex = 0;

    /**
     * Available units for the selected ingredient
     * @var array<mixed>
     */
    public array $units = [];

    /**
     * The pantry item to edit.
     * @var PantryItem|null
     */
    public ?PantryItem $item;

    /**
     * Mounts the component.
     *
     * @param PantryItem|null $item The pantry item to edit.
     */
    public function mount($item = null): void
    {
        $this->item = $item;

        if ($item instanceof PantryItem && $item->ingredient instanceof Ingredient) {

            if (null !== $item->expiration_date) {

                $this->expiration_date = Date::parse($item->expiration_date)
                    ->format('d.m.Y');
            }

            if (null !== $item->quantity && is_int(value: $item->quantity)) {
                $this->quantity = $item->quantity;
            }

            $this->isEntrySelected = true;

            $unit_index = $item->unit_index;
            $units = null;

            if (isset($item->ingredient)) {
                $name = $item->ingredient->getAttribute('name') ?? '';
                $this->name = is_string($name) ? $name : '';
                $units = $item->ingredient->getAttribute('unit') ?? [];
            }

            if (null !== $units && is_array($units)) {
                $this->units = $units;
                if (null !== $unit_index  && isset($units[$unit_index])) {
                    $unitValue = $units[$unit_index];
                    $this->unit = is_string($unitValue) ? $unitValue : '';
                }
            }

        }
    }

    public function updatedName(string $value): void
    {
        $this->isEntrySelected = false;
        if (mb_strlen($value) < 2) {
            $this->ingredients = [];
            return;
        }

        $value = mb_strtolower($value);

        /**
         * @var array<array{name: string, picture: string, _id: string, unit: array<string>}>
         */
        $ingredients = Ingredient::where('name', 'like', '%' . $value . '%')
            ->select('name', 'picture', '_id', 'unit')
            ->limit(5)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();

        $this->ingredients = collect($ingredients)->sortBy(fn ($ingredients) => levenshtein($ingredients['name'], $value))->values()->all();

    }

    public function incrementHighlight(): void
    {
        if ($this->highlightIndex < count($this->ingredients) - 1) {
            $this->highlightIndex++;
        }
    }

    public function decrementHighlight(): void
    {
        if ($this->highlightIndex > 0) {
            $this->highlightIndex--;
        }
    }

    public function selectIngredient(): void
    {
        $selectedIndex = $this->highlightIndex;
        if (isset($this->ingredients[$selectedIndex])) {
            $ingredient = $this->ingredients[$selectedIndex];
            $this->name = $ingredient['name'];
            $this->isEntrySelected = true;

            if (null !== $ingredient['unit']) {
                $this->units = $ingredient['unit'];
            }
        }
    }

    public function resetIfInvalid(): void
    {
        $valid = collect($this->ingredients)
            ->pluck('name')
            ->contains($this->name);

        if (!$valid) {
            $this->name = '';
            $this->ingredients = [];
            $this->units = [];
        }
    }

    /**
     * Saves the pantry item to the database.
     */
    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|min:2|max:255|',
            'quantity' => 'min:1|numeric|nullable|required_with:unit',
            'unit' => 'min:1|nullable|max:255|in:' . implode(',', $this->units),
            'expiration_date' => 'date|after:today|nullable',
        ]);

        /** @var Ingredient|null */
        $selectedIngredient = Ingredient::where('name', $this->name)->first();

        if (null === $selectedIngredient || empty($selectedIngredient->getAttribute('_id'))
            || (null === $selectedIngredient->getAttribute('unit')
            && !empty($this->unit))) {
            session()->flash('message', 'Ingredient not found.');
            $this->redirect('/ingredients');
        }

        $unit_index = null;

        if (!empty($this->unit) && null !== $selectedIngredient
            && is_array($selectedIngredient->getAttribute('unit'))) {
            $unit_index = array_search(
                $this->unit,
                $selectedIngredient->getAttribute('unit')
            );
        }

        $expiration_date = empty($this->expiration_date) ? null : $this->expiration_date;
        $ingredientId = null;

        if (null !== $selectedIngredient) {
            $ingredientId = $selectedIngredient->getAttribute('_id');
        }

        $data = [
            'user_id' => auth()->id(),
            'quantity' => $this->quantity,
            'expiration_date' => $expiration_date,
            'ingredient_id' => $ingredientId,
            'unit_index' => $unit_index,
        ];

        if (isset($this->item, $this->item->id)) {
            $this->item->update($data);
        } else {
            PantryItem::create($data);
        }

        session()->flash('message', 'Pantry item successfully saved!');
        $this->redirect('/dashboard');
    }

    /**
     * Renders the component.
     */
    public function render(): View
    {
        return view('livewire.pages.pantry.pantry-item');
    }
}
