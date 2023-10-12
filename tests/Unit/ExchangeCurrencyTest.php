<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;

class ExchangeCurrencyTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;

    public function test_if_db_seeded(): void
    {
        $this->assertDatabaseCount('currencies', 1);
        $this->assertDatabaseCount('rates', 3);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('wallets', 2);
    }

    public function test_if_exchange_is_successful(): void
    {
        $from = Wallet::find(1);
        $to = Wallet::find(2);
        $this->assertEquals($from->amount, 15);
        $this->assertEquals($to->amount, 2);
        Currency::exchange(1, $from->short_name, $to->short_name, 1.5);

        $from = Wallet::find(1);
        $to = Wallet::find(2);
        $this->assertEquals($from->amount, 13.5);
        $this->assertEquals($to->amount, 3.3274336283186);
    }

    public function test_if_exchange_is_unsuccessful_when_user_have_not_enough_money(): void
    {
        $from = Wallet::find(1);
        $to = Wallet::find(2);
        
        $this->assertThrows(fn () => Currency::exchange(1, $from->short_name, $to->short_name, 1000), 
        \InvalidArgumentException::class);
    }
    
    public function test_if_exchange_is_unsuccessful_when_negative_amount(): void
    {
        $from = Wallet::find(1);
        $to = Wallet::find(2);
        
        $this->assertThrows(fn () => Currency::exchange(1, $from->short_name, $to->short_name, -20), 
        \InvalidArgumentException::class);
    }

    public function test_if_exchange_is_unsuccessful_when_rates_not_found(): void
    {
        
        $from = 'RATE';
        $to = Wallet::find(2);
    
        $this->assertThrows(fn () => Currency::exchange(1, $from, $to->short_name, 1.0), 
        \InvalidArgumentException::class);

        $from = Wallet::find(1);
        $to = 'RATE';
    
        $this->assertThrows(fn () => Currency::exchange(1, $from->short_name, $to, 1.0), 
        \InvalidArgumentException::class);
    }

    //In method if user not have wallet, automaticaly creates new.
    public function test_if_exchange_is_successful_when_user_not_have_wallet_for_exchanged_currency(): void
    {
        $from = Wallet::find(1);
        $to = 'CAD';

        $this->assertNull(Wallet::find(3));

        Currency::exchange(1, $from->short_name, $to, 1.0);

        $this->assertNotNull(Wallet::find(3));
    }
}