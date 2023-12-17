<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PersonResource;

class PersonController extends Controller
{
    public function index()
    {
        return "Person fetch API";
    }

    public function person()
    {
        $person = DB::connection('mysql_hos')->select('
        SELECT * FROM person WHERE birthdate = "1973-03-05" LIMIT 100
        ');

        return response()->json([
            'message' => 'Person fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => PersonResource::collection($person)
        ]);
    }
}
