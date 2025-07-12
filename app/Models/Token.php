<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $table = "tokens";

    protected $fillable = [
       "token","confirmed"
    ];
}
