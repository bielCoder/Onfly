<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;
    public $table = 'travellings';

    protected $fillable = [
        "city","company","status"
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

     protected static function newFactory()
    {
        return \Database\Factories\TravelFactory::new();
    }
}

