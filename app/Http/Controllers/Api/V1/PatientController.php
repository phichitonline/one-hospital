<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Patient fetch API";
    }

    public function patient()
    {
        $patient = DB::connection('mysql_hos')->select('
        SELECT * FROM patient
        WHERE birthday = "1973-03-05" LIMIT 100
        ');

        return response()->json([
            'message' => 'Patient fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => PatientResource::collection($patient)
        ]);
    }

}
