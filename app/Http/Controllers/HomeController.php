<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;

class HomeController extends Controller
{
    public function index(): JsonApiResponse
    {
        return $this->buildIndexResponse();
    }
}
