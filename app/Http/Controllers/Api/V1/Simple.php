<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class Simple extends Controller
{
    public function index()
    {
        $fakeData = [
            'id' => 1,
            'name' => 'TEst YOUTUVE',
            'email' => 'emailfake@gmail.com'
        ];

        return response()->json($fakeData);
    }
}
