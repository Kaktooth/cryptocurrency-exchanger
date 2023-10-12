<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Wallet;
use App\Models\Rates;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class HttpTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_if_main_page_redirects(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function test_if_pages_is_ok(): void
    {
        $response = $this->get('/wallet');
        $response->assertStatus(200);

        $response = $this->get('/exchange-rates');
        $response->assertStatus(200);

        $response = $this->get('/exchange-currency');
        $response->assertStatus(200);
    }

    public function test_if_successful_exchange_redirects_to_wallet(): void
    {
        $from = Wallet::find(1)->id;
        $to = Rates::find(1)->id;

        $request =  ['from' => $from, 'to' => $to, 'amount' => 1.0];
        $response = $this->post('/user/exchange', $request);
        $response->assertStatus(302);
    }
}