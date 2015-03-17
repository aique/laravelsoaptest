<?php

class TPVPrinter
{
    public static function printPayFormInputs(TPVClient $client)
    {
        $output = '';

        $output .= '<input name="Ds_Merchant_Amount" type="hidden" value="' . $client->getDsMerchantAmount() . '" />';
        $output .= '<input name="Ds_Merchant_Currency" type="hidden" value="' . $client->getDsMerchantCurrency() . '" />';
        $output .= '<input name="Ds_Merchant_Order" type="hidden" value="' . $client->getDsMerchantOrder() . '" />';
        $output .= '<input name="Ds_Merchant_MerchantCode" type="hidden" value="' . $client->getDsMerchantMerchantCode() . '" />';
        $output .= '<input name="Ds_Merchant_MerchantURL" type="hidden" value="' . $client->getDsMerchantMerchantURL() . '" />';
        $output .= '<input name="Ds_Merchant_MerchantSignature" type="hidden" value="' . $client->getDsMerchantMerchantSignature() . '" />';
        $output .= '<input name="Ds_Merchant_Terminal" type="hidden" value="' . $client->getDsMerchantTerminal() . '" />';
        $output .= '<input name="Ds_Merchant_TransactionType" type="hidden" value="' . $client->getDsMerchantTransactionType() . '" />';

        return $output;
    }
}