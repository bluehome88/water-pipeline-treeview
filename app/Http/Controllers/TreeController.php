<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Mones;
use App\Models\KriotYomi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TreeController extends Controller
{
    public function index(Request $request)
    {
        set_time_limit(0);
        $sql = "SELECT a.mone AS mone, c.address, b.qty AS qty, b.delta AS delta, b.per_cent AS per_cent, b.real_qty AS real_qty FROM `monim` AS a
                LEFT JOIN (SELECT mone, AVG(qty) AS qty, AVG(delta) AS delta, AVG(per_cent) AS per_cent, AVG(real_qty) AS real_qty FROM `kriot_yomi` WHERE day_date GROUP BY kriot_yomi.mone) AS b ON a.mone = b.mone
                LEFT JOIN customers AS c ON c.neches = a.neches";
        $mone_arr = DB::select($sql);
        $this->mone_arr = array_combine(array_column($mone_arr, 'mone'), $mone_arr);

        $parent_mones = Mones::whereNotNull('mone_av')->groupBy('mone_av')->select('mone_av')->pluck('mone_av');
        $parent_mones_arr = Mones::whereIn('mone', $parent_mones)->with('hisCustomer')->get();
        $mone_av = $request->input('mone_av', "fb565e42-e0b9-4973-a158-7a20768c9243");
        if ($mone_av)
            $mones = Mones::where('mone', $mone_av);
        else
            $mones = Mones::whereNull('mone', $mone_av);
        $mones = $mones->with('_children')->get()[0];

        $this->getTree($mones);
        $mones = base64_encode(json_encode($mones));
        return view('treebox', compact('mones', 'parent_mones_arr', 'mone_av'));        
    }

    public function getTree(&$mone)
    {
        $result = $this->mone_arr[$mone['mone']];
        $mone->qty        = $result->qty;
        $mone->address    = $result->address;
        $mone->real_qty   = $result->real_qty;
        $mone->per_cent   = $result->per_cent;
        $mone->delta   = $result->delta;
        if ($mone->_children->isNotEmpty())
            foreach($mone->_children as $each)
                $this->getTree($each);
    }
}
