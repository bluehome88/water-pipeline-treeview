<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\KriotYomi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mones extends Model
{
    use HasFactory;
    protected $table = 'monim';

    public function oneChildren()
    {
        return $this->hasMany(self::class, 'mone_av', 'mone')
                        ->whereRaw('mone <> mone_av');
    }

    public function _children()
    {
        return $this->oneChildren()->with('_children');
    }

    public function kriot()
    {
        return $this->hasMany(kriotYomi::class, 'mone', 'mone');
    }

    public function hisCustomer()
    {
        return $this->hasOne(Customer::class, 'neches', 'neches');
    }
}
