<?php

namespace App\Controllers;

use App\Models\PushSubscriptionModel;

class NotificationController extends BaseController
{
  public function subscribe()
  {
    $json = $this->request->getJSON();
    $userId = session()->get('userId');

    if (!$userId) {
      return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
    }

    $model = new PushSubscriptionModel();

    $exists = $model->where('endpoint', $json->endpoint)->first();

    if (!$exists) {
      $model->save([
        'user_id' => $userId,
        'endpoint' => $json->endpoint,
        'p256dh' => $json->keys->p256dh,
        'auth' => $json->keys->auth,
      ]);
    }

    return $this->response->setJSON(['status' => 'success']);
  }

  public function getPublicKey()
  {
    // Generate keys di https://vapidkeys.com/
    return $this->response->setJSON([
      'publicKey' => 'BNHQu8Oo9mQSFH-oS8NIJlALTkenlIWb0SerlB45_EB88Qj9Sg3EU9lCgtPGcJioJZAOMCJmIxWdwvwtBGib-hE'
    ]);
  }
}
