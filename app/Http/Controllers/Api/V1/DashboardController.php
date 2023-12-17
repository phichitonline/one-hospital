<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return "Dashboard fetch API";
    }

    public function count_visit_lastmonth()
    {
        $count_visit_lastmonth = DB::connection('mysql_hos')->select('
        SELECT COUNT(*) AS ptm_opd_vn_last
        FROM ovst WHERE DATE_FORMAT(vstdate,"%Y-%m") = DATE_FORMAT(CURDATE(),"%Y-%m")
        ');

        return response()->json([
            'message' => 'count_visit_lastmonth fetch successfully',
            'version' => '1',
            'last_update' => '2023-12-14',
            'data' => $count_visit_lastmonth
        ]);
    }
}
