<?php

namespace App\Controllers;

class BiodataController extends BaseController
{
  public function index(): string
  {
    return view('pages/biodata');
  }
}
