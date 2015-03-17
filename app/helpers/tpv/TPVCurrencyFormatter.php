<?php

class TPVCurrencyFormatter
{
    public static function format($currency)
    {
        if(is_float($currency))
        {
            $currency = $currency * 100;
        }
        else
        {
            $currency = (float)(str_replace(',', '.', $currency)) * 100;
        }

        return $currency;
    }
}