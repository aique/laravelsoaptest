<?php

class TPV
{
    const TEST_MODE = 'test'; // indica que el cliente tpv se ejecutará en el entorno de pruebas
    const REAL_MODE = 'real'; // indica que el cliente tpv se ejecutará en el entorno real

    const TPV_CONNECTION_MODE = 'tpv'; // indica que el modo de conexión al banco será a través de la pasarela
    const WS_CONNECTION_MODE = 'ws'; // indica que el modo de conexión al banco será a través de servicio web

    const EURO_CURRENCY_CODE = 978;

    const PAY_TRANSACTION_TYPE = '0'; // tipo de transacción para el pago

    public static function sendPayData($mode, array $params)
    {
        $client = new TPVClient($mode, TPV::WS_CONNECTION_MODE, $params);
        $soapClient = new TPVSoapClient($mode);

        return $soapClient->sendRequest($client);
    }
}