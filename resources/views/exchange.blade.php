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
        <form method="POST" action="/user/exchange">
            <div class="mb-6 ">
                @csrf
                <label for="from" class="inline-block text-lg mb-2"> From</label>
                <select name="from">
                    <?php $count = 1; foreach ($rates as $rate): ?>
                    @if ($rate->short_name === "EUR")
                    <option value="{{$rate->id}}" selected>{{$rate->short_name}} rate: {{$rate->value}}</option>
                    @else
                    <option value="{{$rate->id}}">{{$rate->short_name}} rate: {{$rate->value}}</option>
                    @endif
                    <?php $count++; endforeach; ?>
                </select>

                <label for="to" class="inline-block text-lg mb-2"> To</label>
                <select name="to">
                    <?php $count = 1; foreach ($rates as $rate): ?>
                    @if ($rate->short_name === "EUR")
                    <option value="{{$rate->id}}" selected>{{$rate->short_name}} rate: {{$rate->value}}</option>
                    @else
                    <option value="{{$rate->id}}">{{$rate->short_name}} rate: {{$rate->value}}</option>
                    @endif
                    <?php $count++; endforeach; ?>
                </select>

                <label for="amount" class="inline-block text-lg mb-2"> Amount </label>
                <input name="amount" type="number" step="any"
                    class="border border-gray-200 rounded bg-gray-100 shadow-gray-500\/20" />

                <div class="mb-6 dark float-right">
                    <button type="submit" class="bg-red-50 rounded">
                        Exchange
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
</body>

</html>