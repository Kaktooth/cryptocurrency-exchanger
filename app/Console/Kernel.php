<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\Currency;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            Log::info("get json with API");
            $url = curl_file_create("http://api.exchangeratesapi.io/v1/latest?access_key=b2f8a25ef64db8f05e4fdf6a246bd4b1");
            $json = json_decode(file_get_contents($url->getFilename()), true);
            Log::info("fill db with latest exchange rates");
            Log::info($json['timestamp']);

            $success = $json['success'];
            $timestamp = $json['timestamp'];
            $base = $json['base'];
            $date = $json['date'];
            $rates = $json['rates'];
         

            DB::statement(
                'update currencies set success = ?, timestamp = ?, base = ?, date = ? where base = ?',
                [$success, $timestamp, $base, $date, $base]
            );
            $currency = Currency::where('base', $base)->first();


            foreach ($rates as $name => $value) {
                $rate = DB::selectOne('select * from rates where short_name = ?', [$name]);

                if ($rate != null) {
                    DB::statement('update rates set value = ? where short_name = ?', [$value, $name]);
                } else {
                    $currency->rates()->create([
                        'short_name' => $name,
                        'value' => $value,
                    ]);
                }
            }
        })->everyTenSeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}