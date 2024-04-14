<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

class Currency
{
    public function __invoke(...$param)
    {
        return static::format(...$param);
    }

    public static function format($amount, $currency = null)
    {
        $baseCurrency = config('app.currency', 'USD');

        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);

        if ($currency === null) {
            $currency = Session::get('currency_code', $baseCurrency);
        }


        return $formatter->formatCurrency($amount, $currency);
    }
}
