<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Currency extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    public function rates()
    {
        return $this->hasMany(Rates::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'success',
        'timestamp',
        'base',
        'date',
    ];


    public static function get(int $id)
    {
        $currency = DB::selectOne('select * from currencies where id = ?', [$id]);
        return $currency;
    }

    public static function getAll()
    {
        $currencies = DB::select('select * from currencies');
        return $currencies;
    }

    public static function exchange(string $id, string $from, string $to, float $amount): void
    {

        if ($amount < 0) {
            Log::error("Negative amount");
            throw new \InvalidArgumentException("Negative amount");
        }

        $user = User::find($id);
        if ($user == null) {
            Log::error("User not found");
            throw new NotFoundHttpException('User not found');
        }

        $baseCurrency = Currency::where('base', 'EUR')->first();
        if ($baseCurrency == null) {
            Log::error("Base currency not found");
            throw new NotFoundHttpException('Base currency not found');
        }
        $rates = Rates::where('currency_id', $baseCurrency->id)->first();

        $firstRate = $rates->where('short_name', $from)->first();
        if ($firstRate == null) {
            Log::error("First exchange rate of currency not found. Method argument is invalid.");
            throw new \InvalidArgumentException("First exchange rate of currency not found. Method argument is invalid.");
        }

        $secondRate = $rates->where('short_name', $to)->first();
        if ($secondRate == null) {
            Log::error("Second exchange rate of currency not found. Method argument is invalid.");
            throw new \InvalidArgumentException("Second exchange rate of currency not found. Method argument is invalid.");
        }

        $rate = $firstRate->value / $secondRate->value;
        $userWallet = Wallet::where('user_id', $user->id)->first();
        $firstCurrencyWallet = $userWallet->where('short_name', $from)->first()->amount;
        if ($firstCurrencyWallet < $amount) {
            Log::error("User dont have enough money");
            throw new \InvalidArgumentException("User dont have enough money");
        }

        if ($userWallet->where('short_name', $to)->first() == null) {
            Log::info("Creating new wallet");
            $newWallet = new Wallet;
            $newWallet->short_name = $to;
            $newWallet->amount = 0;
            $newWallet->user_id = $user->id;
            $newWallet->save();
        }

        $secondCurrencyWallet = $userWallet->where('short_name', $to)->first()->amount;

        $convertedAmount = $amount * $rate;
        $firstCurrencyAmount = $firstCurrencyWallet - $amount;
        $secondCurrencyAmount = $secondCurrencyWallet + $convertedAmount;

        printf('EXCHANGE DETAILS |||');
        printf('convertion rate of %s/%s: %f  |', $from, $to, $convertedAmount);
        printf('%s wallet currency = %f %s |', $firstCurrencyWallet, $firstCurrencyAmount, $from);
        printf('%s wallet currency = %f %s |', $secondCurrencyWallet, $secondCurrencyAmount, $to);

        Log::info('update exchanged currency');

        $updateWalletStatement = 'update wallets set amount = ? where short_name = ?';
        DB::statement($updateWalletStatement, [$firstCurrencyAmount, $from]);
        DB::statement($updateWalletStatement, [$secondCurrencyAmount, $to]);
    }
}