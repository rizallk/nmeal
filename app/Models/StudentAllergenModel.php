<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAllergenModel extends Model
{
  protected $table = 'student_allergens';
  protected $allowedFields = [
    'student_id',
    'allergen_id',
  ];
}
