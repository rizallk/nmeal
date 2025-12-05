<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodIngredientModel extends Model
{
  protected $table = 'food_ingredients';
  protected $allowedFields = [
    'food_id',
    'ingredient_id',
  ];
}
