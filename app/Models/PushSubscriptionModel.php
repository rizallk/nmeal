<?php

namespace App\Models;

use CodeIgniter\Model;

class PushSubscriptionModel extends Model
{
  protected $table = 'push_subscriptions';
  protected $primaryKey = 'id';
  protected $allowedFields = ['user_id', 'endpoint', 'p256dh', 'auth'];
  protected $useTimestamps = true;
}
