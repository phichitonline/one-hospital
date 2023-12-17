<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExchangeController extends Controller
{
    public function index()
    {
        return "Exchange fetch API";
    }

    public function doctor()
    {
        $doctor = DB::connection('mysql_hos')->select('
        SELECT * FROM doctor
        ');

        return response()->json([
            'message' => 'Doctor fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $doctor
        ]);
    }

    public function drugitems()
    {
        $drugitems = DB::connection('mysql_hos')->select('
        SELECT * FROM drugitems LIMIT 100
        ');

        return response()->json([
            'message' => 'Drugitems fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $drugitems
        ]);
    }

    public function drugusage()
    {
        $drugusage = DB::connection('mysql_hos')->select('
        SELECT * FROM drugusage LIMIT 100
        ');

        return response()->json([
            'message' => 'Drugusage fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $drugusage
        ]);
    }

    public function kskdepartment()
    {
        $kskdepartment = DB::connection('mysql_hos')->select('
        SELECT * FROM kskdepartment
        ');

        return response()->json([
            'message' => 'kskdepartment fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $kskdepartment
        ]);
    }

    public function opitemrece(Request $request)
    {
        $opitemrece = DB::connection('mysql_hos')->select('
        SELECT * FROM opitemrece WHERE vn = '.$request->vn.'
        ');

        return response()->json([
            'message' => 'opitemrece fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $opitemrece
        ]);
    }

    public function patient(Request $request)
    {
        $patient = DB::connection('mysql_hos')->select('
        SELECT * FROM patient WHERE cid = '.$request->cid.'
        ');

        return response()->json([
            'message' => 'Patient fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $patient
        ]);
    }

    public function person(Request $request)
    {
        $person = DB::connection('mysql_hos')->select('
        SELECT * FROM person WHERE cid = '.$request->cid.'
        ');

        return response()->json([
            'message' => 'Person fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $person
        ]);
    }

    public function sp_use(Request $request)
    {
        $sp_use = DB::connection('mysql_hos')->select('
        SELECT * FROM sp_use LIMIT 100
        ');

        return response()->json([
            'message' => 'sp_use fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $sp_use
        ]);
    }

}
