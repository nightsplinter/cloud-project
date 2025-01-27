<?php

namespace Tests\Feature\Livewire\Forms;

use App\Livewire\Forms\PantryItemForm;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\User;
use App\Models\MongoDB\Ingredient;

class PantryItemFormTest extends TestCase
{
    /**
     * Test if the component renders successfully with no item.
     */
    public function test_renders_with_no_item_successfully(): void
    {
        $item = null;
        Livewire::test(PantryItemForm::class, ['item' => $item])
            ->assertStatus(200);
    }

    /**
     * Test if the component renders successfully with an item.
     */
    public function test_renders_with_item_successfully(): void
    {
        $user = User::factory()->create();
        Ingredient::unguard();

        $ingredient = Ingredient::create([
            'name' => 'Tomato',
            'unit' => ['kg', 'g'],
            'categories' => ['Vegetable', 'Food'],
        ]);

        Ingredient::reguard();

        $user->pantryItems()->create([
            'quantity' => 1,
            'expiration_date' => '2022-12-12',
            'ingredient_id' => $ingredient->_id,
            'unit_index' => 0,
        ]);

        $item = $user->pantryItems()->first();

        Livewire::test(PantryItemForm::class, ['item' => $item])
            ->assertStatus(200);
    }
}
