<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodPickupModel extends Model
{
  protected $table = 'food_pickups';
  protected $allowedFields = [
    'student_id',
    'user_id',
    'status',
    'catatan'
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'student_id' => 'required|is_natural_no_zero',
    'user_id' => 'required|is_natural_no_zero',
    'status' => 'required|in_list[0,1]',
    'catatan' => 'permit_empty|max_length[255]',
  ];

  protected $validationMessages = [];
}
