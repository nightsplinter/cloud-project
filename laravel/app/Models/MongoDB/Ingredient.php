<?php

namespace App\Models\MongoDB;

use MongoDB\Laravel\Eloquent\Model;

/**
 * This class is a model for an ingredient in the MongoDB database.
 */
class Ingredient extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'ingredients';

    protected $fillable = ['name', 'unit', 'categories', 'picture', '_id'];

    /**
     * Returns the categories of the ingredient.
     * @var string[]|null Categories represented as strings.
     */
    public $categories;

    /**
     * Returns the possible units of the ingredient.
     * @var string[]|null units represented as strings.
     */
    public $unit;

    /**
     * Returns the name of the ingredient.
     * @var string
     */
    public $name;

    protected $visible = ['_id', 'name', 'unit', 'categories', 'picture'];

}
