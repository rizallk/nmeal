<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentFoodModel extends Model
{
  protected $table = 'student_foods';
  protected $allowedFields = [
    'student_id',
    'food_id',
  ];

  protected $validationRules = [
    'student_id' => 'required',
    'food_id' => 'required',
  ];

  protected $validationMessages = [
    'student_id' => [
      'required' => 'Menu makanan wajib diisi.',
    ],
    'food_id' => [
      'required' => 'Menu makanan wajib diisi.',
    ],
  ];
}
