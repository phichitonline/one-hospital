<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpdvisitResource;

class OpdController extends Controller
{
    public function visit(Request $request)
    {
        $visit = DB::connection('mysql_hos')->select('

        select a.type,sum(if(a.er = 0,1,0)) as opd,sum(if(a.er = 1 ,1,0)) as er from
(select "total" as type ,COUNT(ovst.vn) as opd,COUNT(er_regist.vn) as er
from ovst
INNER JOIN service_time s on ovst.vn = s.vn
LEFT JOIN er_regist on ovst.vn = er_regist.vn
WHERE ovst.vstdate = CURRENT_DATE
GROUP BY ovst.vn) as a
UNION
select a.type,sum(if(a.er = 0,1,0)) as opd,sum(if(a.er = 1 ,1,0)) as er from
(select "waiting" as type ,COUNT(ovst.vn) as opd,COUNT(er_regist.vn) as er
from ovst
INNER JOIN service_time s on ovst.vn = s.vn
LEFT JOIN er_regist on ovst.vn = er_regist.vn
WHERE ovst.vstdate = CURRENT_DATE
and s.service5 is null
GROUP BY ovst.vn) as a
UNION
select a.type,sum(if(a.er = 0,1,0)) as opd,sum(if(a.er = 1 ,1,0)) as er from
(select "inprocess" as type ,COUNT(ovst.vn) as opd,COUNT(er_regist.vn) as er
from ovst
INNER JOIN service_time s on ovst.vn = s.vn
LEFT JOIN er_regist on ovst.vn = er_regist.vn
WHERE ovst.vstdate = CURRENT_DATE
 and s.service5 is not null
 and s.service12 is null
GROUP BY ovst.vn) as a
UNION
select a.type,sum(if(a.er = 0,1,0)) as opd,sum(if(a.er = 1 ,1,0)) as er from
(select "success" as type ,COUNT(ovst.vn) as opd,COUNT(er_regist.vn) as er
from ovst
INNER JOIN service_time s on ovst.vn = s.vn
LEFT JOIN er_regist on ovst.vn = er_regist.vn
WHERE ovst.vstdate = CURRENT_DATE

 and s.service12 is not null
GROUP BY ovst.vn) as a

        ');

        return response()->json([
            'message' => 'Visit summary',
            'version' => '1',
            'last_update' => '2023-12-20',
            'data' => OpdvisitResource::collection($visit)
        ]);
    }

}
