<?php

namespace App\Livewire\Pantry;

use App\Models\PantryItem;
use Livewire\Component;

/**
 * This class is a Livewire component that is used to delete a pantry item.
 */
class DeleteItem extends Component
{
    public $itemId;

    public function delete()
    {
        $item = PantryItem::find($this->itemId);

        if ($item) {
            $item->delete();

            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.pantry.delete-item');
    }
}
