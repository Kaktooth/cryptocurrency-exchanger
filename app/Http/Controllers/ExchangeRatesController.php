<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Rates;
use App\Models\Wallet;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExchangeRatesController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function authenticate(){
        $credentials = ['name' => 'Test User', 'password' => 'testuser'];
        Auth::attempt($credentials);
    }

    public function getExchangeRatesPage()
    { 
        $this->authenticate();

        $currency = Currency::where('base', 'EUR')->first();
        $rates = Rates::where('currency_id', $currency->id)->get();
        $wallets = Wallet::where('user_id', auth()->user()->id);
        return view('exchange-rates', ['currency' => $currency], ['rates' => $rates], ['wallets' => $wallets]);
    }

    public function getExchangeCurrencyPage()
    { 
        $this->authenticate();

        $currency = Currency::where('base', 'EUR')->first();
        $rates = Rates::where('currency_id', $currency->id)->get();
        $wallets = Wallet::where('user_id', auth()->user()->id);
        return view('exchange', ['currency' => $currency], ['rates' => $rates], ['wallets' => $wallets]);
    }

    public function exchangeCurrency(Request $request)
    {
        $this->authenticate();
        
        Log::info("post exchange");
        $formFields = $request->validate([
            'from' => ['required'],
            'to' => ['required'],
            'amount' => ['required'],
        ]);

        $from = Wallet::find($formFields['from'])->short_name;
        $to = Rates::find($formFields['to'])->short_name;
        Log::info(auth()->user()->id);
        Log::info($from);
        Log::info($to);
        Log::info($formFields['amount']);
        $this->exchange(auth()->user()->id, $from, $to, $formFields['amount']);

        return redirect('/wallet');
    }

    public function exchange(string $id, string $from, string $to, float $amount)
    {
        Currency::exchange($id, $from, $to, $amount);
    }

    public function getById(int $id)
    {
        return Currency::get($id);
    }

    public function getAll()
    {
        return Currency::getAll();
    }
}