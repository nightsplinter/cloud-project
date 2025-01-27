<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MongoDB\Ingredient;
use App\Models\User;

/**
 * This class tests all related things to the Pantry feature that are not
 * related to the Livewire components.
 */
class PantryTest extends TestCase
{
    /**
     * Check if the dashboard route is protected.
     */
    public function test_dashboard_route_is_protected(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
    }

    /**
     * Check if the dashboard route allows access to authenticated users.
     */
    public function test_dashboard_route_allows_authenticated_users(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Check if the ingredient where you can add a new ingredient is protected.
     */
    public function test_ingredient_route_is_protected(): void
    {
        $response = $this->get('/ingredient');
        $response->assertStatus(302);
    }

    /**
     * Check if the ingredient route where you can add a new ingredient
     * allows access to authenticated users.
     */
    public function test_ingredient_route_allows_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/ingredient');
        $response->assertStatus(200);
    }

    /**
     * Check if the ingredient/{id} route is protected.
     */
    public function test_ingredient_id_route_is_protected(): void
    {
        $id = 1;
        $response = $this->get('/ingredient/' . $id);
        $response->assertStatus(302);
    }

    /**
     * Check if the ingredient/{id} route allows access to authenticated users.
     */
    public function test_ingredient_id_route_allows_authenticated_users(): void
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

        $userIngredientId = $user->pantryItems()->first()->id;

        $response = $this->actingAs($user)
            ->get('/ingredient/' . $userIngredientId);
        $response->assertStatus(200);
    }
}
