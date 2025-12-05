<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodAllergenModel extends Model
{
  protected $table = 'food_allergens';
  protected $allowedFields = [
    'food_id',
    'allergen_id',
  ];
}
