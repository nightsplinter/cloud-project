<?php

namespace App\Http\Controllers;

use App\Models\PantryItem;
use Illuminate\View\View;
use App\Services\IngredientService;

/**
 * This controller is responsible for handling pantry items.
 *
 */
class PantryController extends Controller
{
    /**
     * Display the pantry list.
     * @return View
     */
    public function index(): View
    {
        return view('pantrylist');
    }

    /**
     * Add a new pantry item.
     * @return View
     */
    public function add(): View
    {
        return view('pantry-item');
    }

    /**
     * Edit a pantry item by id.
     * @return View
     */
    public function edit(int $id): View
    {
        $item = new IngredientService()->findById($id);

        return view('pantry-item', [
            'item' => $item,
        ]);
    }

    /**
     * Delete a pantry item by id.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        PantryItem::destroy($id);

        return redirect()->route('dashboard');
    }
}
