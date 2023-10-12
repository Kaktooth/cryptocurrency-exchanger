<!DOCTYPE html> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <head>
<meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Exchange Rates</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" l="stylesheet" />
<link rel="stylesheet" href="././css/app.css" />
</head>

<body>
    <div>
        <h3>
            user: {{auth()->user()->name}}
        </h3>
        <nav>
            <ul>
                <li> base currency
                    <?php echo $currency->base?>
                </li>
                <li><a href="/wallet"> * Wallet </a></li>
                <li><a href="/exchange-rates"> * Exchange Rates </a></li>
                <li><a href="/exchange-currency"> * Exchange Wallet Currency</a></li>
            </ul>
        </nav>
    </div>
    <div>
        <table>
            <thead class="text-left">
                <tr>
                    <th> № </th>
                    <th> Name </th>
                    <th> Amount </th>
                </tr>
            </thead>
            <?php
            $count = 1;
            foreach ($wallets as $wallet): ?>
            <tr>
                <td>
                    <?php echo $count; ?>
                </td>
                <td>
                    <?php echo $wallet->short_name; ?>
                </td>
                <td>
                    <?php echo $wallet->amount; ?>
                </td>
            </tr>
            <?php
            $count++;
            endforeach; ?>
        </table>
    </div>
    </div>
    </div>
</body>
</html>