<?php

namespace App\Http\Controllers;

use App\Models\PantryItem;
use Illuminate\Contracts\View\View;

/**
 * This controller is responsible for handling pantry items.
 *
 */
class PantryController extends Controller
{
    /**
     * Display the pantry list.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $userPantryItems = PantryItem::getMappedUserPantryItems();

        return view('pantrylist', [
            'userPantryItems' => $userPantryItems,
        ]);
    }

    /**
     * Add a new pantry item.
     * @return \Illuminate\Contracts\View\View
     */
    public function add(): View
    {
        return view('pantry-item', [
            'item' => [],
        ]);
    }

    /**
     * Edit a pantry item by id.
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id): View
    {
        $item = PantryItem::find($id);

        return view('pantry-item', [
            'item' => $item,
        ]);
    }

    /**
     * Delete a pantry item by id.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        PantryItem::destroy($id);

        return redirect()->route('dashboard');
    }
}
