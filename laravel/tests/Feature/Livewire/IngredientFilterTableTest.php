<?php

namespace Tests\Feature\Livewire;

use App\Livewire\IngredientFilterTable;
use Livewire\Livewire;
use Tests\TestCase;

class IngredientFilterTableTest extends TestCase
{
    public function renders_successfully(): void
    {
        Livewire::test(IngredientFilterTable::class)
            ->assertStatus(200);
    }
}
