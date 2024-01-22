<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\IpdvisitResource;

class IpdController extends Controller
{
    public function visit(Request $request)
    {
        $visit = DB::connection('mysql_hos')->select('
        SELECT COUNT(DISTINCT hn) AS ptm_ipd_hn,COUNT(DISTINCT an) AS ptm_ipd_an
        ,COUNT(DISTINCT IF(regdate = DATE_FORMAT(NOW(),"%Y-%m-%d"),an,NULL)) AS pt_ipd_today
        ,(SELECT COUNT(*) FROM ipt WHERE DATE_FORMAT(regdate,"%Y-%m") = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),"%Y-%m")) AS ptm_ipd_vn_last
        ,(SELECT COUNT(*) FROM ipt WHERE dchdate IS NULL) AS ipt_admit
        ,(SELECT (SELECT SUM(bedcount) FROM ward WHERE ward_active = "Y")-COUNT(*) FROM ipt WHERE dchdate IS NULL) AS empty_bed
        ,(SELECT SUM(bedcount) FROM ward WHERE ward_active = "Y") AS bed_count

        FROM ipt WHERE regdate BETWEEN DATE_FORMAT(NOW(),"%Y-%m-01") AND DATE_FORMAT(NOW(),"%Y-%m-%d")

        ');

        return response()->json([
            'message' => 'Visit IPD summary',
            'version' => '1',
            'last_update' => '2024-01-22',
            'data' => IpdvisitResource::collection($visit)
        ]);
    }
}
