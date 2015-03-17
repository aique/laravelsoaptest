<?php

class HomeController extends BaseController {

	public function home()
	{
        // crear el cliente que la vista transformará en campos del formulario
        $client = new TPVClient(TPV::TEST_MODE, TPV::TPV_CONNECTION_MODE, array
        (
            'Ds_Merchant_Amount' => '12,95', // importe
            'Ds_Merchant_Currency' => TPV::EURO_CURRENCY_CODE, // código de moneda
            'Ds_Merchant_Order' => date('His'), // número de pedido
        ));

        return View::make('home', array
        (
            'client' => $client,
            'soapResp' => null
        ));
	}

    public function tpv()
    {
        // cambiar los siguientes valores por los introducidos por el usuario
        $cardNum = Config::get('tpv.web_service.test.card_num');
        $cvv2 = Config::get('tpv.web_service.test.cvv2');
        $expiryDate = Config::get('tpv.web_service.test.expiry_date');

        // hacer la llamada al servicio web
        $soapResp = TPV::sendPayData(TPV::TEST_MODE, array
        (
            'Ds_Merchant_Amount' => 12.95, // importe
            'Ds_Merchant_Currency' => TPV::EURO_CURRENCY_CODE, // código de moneda
            'Ds_Merchant_Order' => date('His'), // número de pedido
            'Ds_Merchant_Pan' => $cardNum, // número de tarjeta
            'Ds_Merchant_Cvv2' => $cvv2, // cvv2
            'Ds_Merchant_Expirydate' => $expiryDate // fecha de caducidad de la tarjeta
        ));

        return View::make('home', array
        (
            'client' => null,
            'soapResp' => $soapResp
        ));
    }
}
