<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Rates;
use App\Models\Wallet;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function getWalletsPage()
    {
        $credentials = ['name' => 'Test User', 'password' => 'testuser'];
        Auth::attempt($credentials);

        $currency = Currency::where('base', 'EUR')->first();
        $wallets = Wallet::where('user_id', auth()->user()->id)->get();

        return view('wallet', ['currency' => $currency], ['wallets' => $wallets]);
    }
}