<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    public $table = "orders";

    protected $fillable = [
        "user_id","travelling_id","departure_date","return_date","status","active"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travellings()
    {
        return $this->belongsTo(Travel::class);
    }
}
