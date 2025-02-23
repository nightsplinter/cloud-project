<?php

namespace App\Services;

use App\Models\PantryItem;
use App\Repositories\BigQueryRepository;

class IngredientService
{
    private BigQueryRepository $bigQueryRepository;

    public function __construct()
    {
        if (!app()->environment('local', 'testing')) {
            $this->bigQueryRepository = new BigQueryRepository('ingredients');
        }
    }

    /**
     * Find pantry item by id
     *
     * @param int $id Pantry item id
     * @return PantryItem|null
     */
    public function findById(int $id): PantryItem|null
    {
        if (app()->environment('local', 'testing')) {
            return PantryItem::with('ingredient')->find($id);
        }
        $queryResults = $this->bigQueryRepository->findPantryItem($id);
        //FIXME: Implement mapping logic
        return new PantryItem();
    }

}
