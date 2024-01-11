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

    public function count_visit_opd()
    {
        $count_visit_opd = DB::connection('mysql_hos')->select('
        SELECT COUNT(*) AS ptm_opd_vn,COUNT(DISTINCT(hn)) AS ptm_opd_hn,COUNT(IF(vstdate=CURDATE(),vstdate,NULL)) AS pt_opd_today
        ,(SELECT COUNT(*) FROM ovst WHERE DATE_FORMAT(vstdate,"%Y-%m") = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),"%Y-%m")) AS ptm_opd_vn_last
        FROM ovst WHERE vstdate BETWEEN DATE_FORMAT(NOW(),"%Y-%m-01") AND DATE_FORMAT(NOW(),"%Y-%m-%d")
        ');

        return response()->json([
            'message' => 'OPD fetch successfully',
            'version' => '1',
            'last_update' => '2024-01-11',
            'data' => $count_visit_opd
        ]);
    }

    public function count_visit_ipd()
    {
        $count_visit_ipd = DB::connection('mysql_hos')->select('
        SELECT COUNT(DISTINCT hn) AS ptm_ipd_hn,COUNT(DISTINCT an) AS ptm_ipd_an
        ,COUNT(DISTINCT IF(regdate = DATE_FORMAT(NOW(),"%Y-%m-%d"),an,NULL)) AS pt_ipd_today
		,(SELECT COUNT(*) FROM ipt WHERE DATE_FORMAT(regdate,"%Y-%m") = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),"%Y-%m")) AS ptm_ipd_vn_last
        ,(SELECT COUNT(*) FROM ipt WHERE dchdate IS NULL) AS ipt_admit
        ,(SELECT (SELECT SUM(bedcount) FROM ward WHERE ward_active = "Y")-COUNT(*) FROM ipt WHERE dchdate IS NULL) AS empty_bed
        ,(SELECT SUM(bedcount) FROM ward WHERE ward_active = "Y") AS bed_count
        FROM ipt WHERE regdate BETWEEN DATE_FORMAT(NOW(),"%Y-%m-01") AND DATE_FORMAT(NOW(),"%Y-%m-%d")
        ');

        return response()->json([
            'message' => 'IPD fetch successfully',
            'version' => '1',
            'last_update' => '2024-01-11',
            'data' => $count_visit_ipd
        ]);
    }

    public function count_visit_er()
    {
        $count_visit_er = DB::connection('mysql_hos')->select('
        SELECT COUNT(DISTINCT o.hn) AS pt_er_hn,COUNT(DISTINCT o.vn) AS pt_er_vn
        ,COUNT(DISTINCT IF(er.vstdate = DATE_FORMAT(NOW(),"%Y-%m-%d"),o.vn,NULL)) AS pt_er_today
		,(SELECT COUNT(*) FROM er_regist
            WHERE DATE_FORMAT(vstdate,"%Y-%m") = DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -1 MONTH),"%Y-%m")
            AND er_pt_type IN (SELECT er_pt_type FROM er_pt_type WHERE accident_code = "Y")) AS ptm_er_vn_last
        FROM er_regist er
        LEFT OUTER JOIN ovst o ON o.vn = er.vn
        LEFT OUTER JOIN er_pt_type et ON et.er_pt_type = er.er_pt_type
        WHERE er.vstdate BETWEEN DATE_FORMAT(NOW(),"%Y-%m-01") AND DATE_FORMAT(NOW(),"%Y-%m-%d")
        AND er.er_pt_type IN (SELECT er_pt_type FROM er_pt_type WHERE accident_code = "Y")
        ');

        return response()->json([
            'message' => 'ER fetch successfully',
            'version' => '1',
            'last_update' => '2024-01-11',
            'data' => $count_visit_er
        ]);
    }

    public function count_or()
    {
        $count_or = DB::connection('mysql_hos')->select('
        SELECT COUNT(DISTINCT hn) AS ptm_or_hn
        ,COUNT(hn) AS ptm_or_vn
        ,COUNT(IF(patient_department = "OPD",vn,NULL)) AS ptm_or_opd
        ,COUNT(IF(patient_department = "IPD",an,NULL)) AS ptm_or_ipd
        ,COUNT(IF(operation_date = DATE_FORMAT(NOW(),"%Y-%m-%d"),hn,NULL)) AS pt_or_today
        FROM operation_list
        WHERE operation_date BETWEEN DATE_FORMAT(NOW(),"%Y-%m-01") AND DATE_FORMAT(NOW(),"%Y-%m-%d")
        ');

        return response()->json([
            'message' => 'OR fetch successfully',
            'version' => '1',
            'last_update' => '2024-01-11',
            'data' => $count_or
        ]);
    }


    // public function count_visit()
    // {
    //     $count_visit = DB::connection('mysql_hos')->select('
    //     SELECT COUNT(*) AS ptm_opd_vn,COUNT(DISTINCT(hn)) AS ptm_opd_hn,COUNT(IF(vstdate=CURDATE(),vstdate,NULL)) AS pt_opd_today
    //     FROM ovst WHERE DATE_FORMAT(vstdate,"%Y-%m") = DATE_FORMAT(CURDATE(),"%Y-%m")
    //     ');

    //     return response()->json([
    //         'message' => 'count_visit fetch successfully',
    //         'version' => '1',
    //         'last_update' => '2023-12-14',
    //         'data' => $count_visit
    //     ]);
    // }
}
