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
        // if ($request->has('daterange') && $request->daterange) {
        //     $from_date = explode('~', $request->daterange)[0];
        //     $to_date = explode('~', $request->daterange)[1];
        // } else {
        //     $from_date  = Carbon::today()->subDays(30)->toDateString();
        //     $to_date    = Carbon::today()->toDateString();
        // }
        set_time_limit(0);
        $parent_mones = Mones::whereNotNull('mone_av')->groupBy('mone_av')->select('mone_av')->pluck('mone_av');
        $parent_mones_arr = Mones::whereIn('mone', $parent_mones)->with('hisCustomer')->get();
        $mone_av = $request->input('mone_av', "fb565e42-e0b9-4973-a158-7a20768c9243");
        if ($mone_av)
            $mones = Mones::where('mone', $mone_av);
        else
            $mones = Mones::whereNull('mone', $mone_av);

        $mones = Cache::get('mones', function () use ($mones) {
            return $mones->with('_children')->get()[0];
        });

        // $this->getTree($mones, $from_date, $to_date);
        $this->getTree($mones);
        $mones = base64_encode(json_encode($mones));
        return view('treebox', compact('mones', 'parent_mones_arr', 'mone_av'));
        // return view('treebox', compact('mones', 'parent_mones_arr', 'mone_av', 'from_date', 'to_date'));
    }

    public function getTree(&$mone)
    // public function getTree(&$mone, $from_date, $to_date)
    {
        // $sql = "SELECT a.mone AS mone, c.address, b.qty AS qty, b.delta AS delta, b.per_cent AS per_cent, b.real_qty AS real_qty FROM `monim` AS a
        //         LEFT JOIN (SELECT mone, AVG(qty) AS qty, AVG(delta) AS delta, AVG(per_cent) AS per_cent, AVG(real_qty) AS real_qty FROM `kriot_yomi` WHERE day_date BETWEEN '{$from_date}' AND '{$to_date}' GROUP BY kriot_yomi.mone) AS b ON a.mone = b.mone
        //         LEFT JOIN customers AS c ON c.neches = a.neches
        //         WHERE a.mone='".$mone['mone']."'";
        $sql = "SELECT a.mone AS mone, c.address, b.qty AS qty, b.delta AS delta, b.per_cent AS per_cent, b.real_qty AS real_qty FROM `monim` AS a
                LEFT JOIN (SELECT mone, AVG(qty) AS qty, AVG(delta) AS delta, AVG(per_cent) AS per_cent, AVG(real_qty) AS real_qty FROM `kriot_yomi` WHERE day_date GROUP BY kriot_yomi.mone) AS b ON a.mone = b.mone
                LEFT JOIN customers AS c ON c.neches = a.neches
                WHERE a.mone='".$mone['mone']."'";
        $result = DB::select($sql)[0];
        $mone->qty        = $result->qty;
        $mone->address    = $result->address;
        $mone->real_qty   = $result->real_qty;
        $mone->per_cent   = $result->per_cent;
        if ($mone->_children->isNotEmpty())
            foreach($mone->_children as $each)
                $this->getTree($each);
                // $this->getTree($each, $from_date, $to_date);
    }
}
