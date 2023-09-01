<?php

namespace App\Http\Controllers;

use App\Http\JsonApiResponse;
use Illuminate\Support\Carbon;

class AboutMeController extends Controller
{
    public function index(): JsonApiResponse
    {
        // My bday 👀
        $age = Carbon::parse('2006-11-01', 'Europe/Paris');

        return new JsonApiResponse([
            'firstname' => 'Robin',
            'altname' => 'moon250',
            'age' => $age->diffInYears(),
            'hobbies' => [
                'Web development',
                'Music',
                'Sport',
                // Insert more...
            ],
            // Find other things to add...
        ]);
    }
}
