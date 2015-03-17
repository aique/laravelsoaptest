<?php

class TPVSoapClient
{
    private $wsdl;
    private $soapClient;

    public function __construct($mode)
    {
        if($mode == TPV::TEST_MODE)
        {
            $this->wsdl = Config::get('tpv.web_service.test.wsdl');
        }
        elseif($mode == TPV::REAL_MODE)
        {
            $this->wsdl = Config::get('tpv.web_service.real.wsdl');
        }

        $this->soapClient = new SoapClient($this->wsdl);
    }

    public function sendRequest(TPVClient $client)
    {
        $peticion = '';

        $peticion .= '<DATOSENTRADA>';
        $peticion .= '<DS_MERCHANT_AMOUNT>'.$client->getDsMerchantAmount().'</DS_MERCHANT_AMOUNT>';
        $peticion .= '<DS_MERCHANT_ORDER>'.$client->getDsMerchantOrder().'</DS_MERCHANT_ORDER>';
        $peticion .= '<DS_MERCHANT_MERCHANTCODE>'.$client->getDsMerchantMerchantCode().'</DS_MERCHANT_MERCHANTCODE>';
        $peticion .= '<DS_MERCHANT_CURRENCY>'.$client->getDsMerchantCurrency().'</DS_MERCHANT_CURRENCY>';
        $peticion .= '<DS_MERCHANT_PAN>'.$client->getDsMerchantPan().'</DS_MERCHANT_PAN>';
        $peticion .= '<DS_MERCHANT_CVV2>'.$client->getDsMerchantCvv2().'</DS_MERCHANT_CVV2>';
        $peticion .= '<DS_MERCHANT_TRANSACTIONTYPE>'.$client->getDsMerchantTransactionType().'</DS_MERCHANT_TRANSACTIONTYPE>';
        $peticion .= '<DS_MERCHANT_TERMINAL>'.$client->getDsMerchantTerminal().'</DS_MERCHANT_TERMINAL>';
        $peticion .= '<DS_MERCHANT_EXPIRYDATE>'.$client->getDsMerchantExpirydate().'</DS_MERCHANT_EXPIRYDATE>';
        $peticion .= '<DS_MERCHANT_MERCHANTSIGNATURE>'.$client->getDsMerchantMerchantSignature().'</DS_MERCHANT_MERCHANTSIGNATURE>';
        $peticion .= '</DATOSENTRADA>';

        $response = $this->soapClient->trataPeticion(array('datoEntrada' => $peticion));

        return TPVParser::parseWSResponse($response->trataPeticionReturn);
    }
}