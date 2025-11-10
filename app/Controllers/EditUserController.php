<?php

namespace App\Controllers;

class EditUserController extends BaseController
{
  public function index()
  {
    $role = 'admin';
    $username = 'Angel Mayeri';

    $data = [
      'pageTitle' => 'Daftar User',
      'userName' => $role == 'ortu' ? "OrTu $username" : $username,
      'userRole' => $role,
    ];

    return view('pages/daftar_user/index', $data);
  }
}
