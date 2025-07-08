<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    public $table = 'travellings';

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
