<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
  protected $table = 'activities';
  protected $allowedFields = [
    'student_id',
    'status',
    'bahan_1_status',
    'bahan_2_status',
    'bahan_3_status',
    'bahan_4_status',
    'bahan_5_status',
    'bahan_6_status',
    'laporan'
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'student_id' => 'required|is_natural_no_zero',
    'status' => 'required|in_list[0,1]',
    'laporan' => 'permit_empty|max_length[255]',
    'bahan_1_status' => 'permit_empty|in_list[0,1]',
    'bahan_2_status' => 'permit_empty|in_list[0,1]',
    'bahan_3_status' => 'permit_empty|in_list[0,1]',
    'bahan_4_status' => 'permit_empty|in_list[0,1]',
    'bahan_5_status' => 'permit_empty|in_list[0,1]',
    'bahan_6_status' => 'permit_empty|in_list[0,1]',
  ];

  protected $validationMessages = [];
}
