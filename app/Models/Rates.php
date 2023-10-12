<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Rates extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    public function cryptocurrency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'short_name',
        'value',
        'currencies_id',
    ];

    public static function get(string $id): Currency
    {
        $currency = DB::selectOne('select * from currencies where id = ?', [$id]);

        return $currency;
    }
}