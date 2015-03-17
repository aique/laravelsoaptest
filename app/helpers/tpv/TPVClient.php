<?php

class TPVClient
{
    /**
     * Distingue entre el entorno de pruebas o el real.
     *
     * @var string
     */
    private $mode;
    /**
     * Determina el medio por el que se realizará la transacción,
     * que puede ser a través de la pasarela bancaria o por
     * servicio web.
     *
     * @var string
     */
    private $connectionMode;

    private $url;
    private $signatureSecretKey;

    private $Ds_Merchant_Amount;
    private $Ds_Merchant_Currency;
    private $Ds_Merchant_Order;
    private $Ds_Merchant_MerchantCode;
    private $Ds_Merchant_MerchantURL;
    private $Ds_Merchant_Terminal;
    private $Ds_Merchant_TransactionType;
    private $Ds_Merchant_Pan;
    private $Ds_Merchant_Cvv2;
    private $Ds_Merchant_Expirydate;

    public function __construct($mode, $connectionMode, array $params)
    {
        $this->mode = $mode;
        $this->connectionMode = $connectionMode;

        if($mode == TPV::TEST_MODE)
        {
            $this->url = Config::get('tpv.tpv.test.url');
            $this->signatureSecretKey = Config::get('tpv.tpv.test.signature_secret_key');

            $this->Ds_Merchant_MerchantCode = Config::get('tpv.tpv.test.fuc');
            $this->Ds_Merchant_MerchantURL = Config::get('tpv.tpv.test.merchanturl');
            $this->Ds_Merchant_Terminal = Config::get('tpv.tpv.test.terminal');
        }
        elseif($mode == TPV::REAL_MODE)
        {
            $this->url = Config::get('tpv.tpv.real.url');
            $this->signatureSecretKey = Config::get('tpv.tpv.real.signature_secret_key');

            $this->$Ds_Merchant_MerchantCode = Config::get('tpv.tpv.real.fuc');
            $this->Ds_Merchant_MerchantURL = Config::get('tpv.tpv.real.merchanturl');
            $this->Ds_Merchant_Terminal = Config::get('tpv.tpv.real.terminal');
        }
        else
        {
            throw new Exception('Se ha recibido un modo inadecuado en la contrucción del cliente TPV');
        }

        $this->Ds_Merchant_TransactionType = TPV::PAY_TRANSACTION_TYPE;

        $this->setParams($params);
    }

    private function setParams(array $params)
    {
        if(count($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                switch($key)
                {
                    case('Ds_Merchant_Amount'): // importe

                        $this->setDsMerchantAmount($value);

                        break;

                    case('Ds_Merchant_Currency'): // moneda

                        $this->Ds_Merchant_Currency = $value;

                        break;

                    case('Ds_Merchant_Order'): // número de pedido

                        $this->Ds_Merchant_Order = $value;

                        break;

                    case('Ds_Merchant_Pan'): // número de tarjeta

                        $this->Ds_Merchant_Pan = $value;

                        break;

                    case('Ds_Merchant_Cvv2'): // cvv2

                        $this->Ds_Merchant_Cvv2 = $value;

                        break;

                    case('Ds_Merchant_Expirydate'): // fecha de caducidad de la tarjeta

                        $this->Ds_Merchant_Expirydate = $value;

                        break;

                    default:

                        throw new Exception("Se ha recibido un parámetro inesperado en la construcción del cliente TPV: " . $key);
                }
            }
        }
        else
        {
            throw new Exception('No se ha recibido ningún parámetro en la construcción del cliente TPV');
        }
    }

    /**
     * Operación que genera la firma para las operaciones a través de la
     * pasarela de pago ofrecida por el banco.
     *
     * @return null|string
     * @throws Exception
     */
    private function generateTPVSignature()
    {
        $signature = null;

        if
        (
            $this->Ds_Merchant_Amount &&
            $this->Ds_Merchant_Order &&
            $this->Ds_Merchant_MerchantCode &&
            $this->Ds_Merchant_Currency &&
            $this->signatureSecretKey
        )
        {
            $signature =
                $this->Ds_Merchant_Amount.
                $this->Ds_Merchant_Order.
                $this->Ds_Merchant_MerchantCode.
                $this->Ds_Merchant_Currency.
                $this->Ds_Merchant_TransactionType.
                $this->Ds_Merchant_MerchantURL.
                $this->signatureSecretKey;

            $signature = strtoupper(sha1($signature));
        }
        else
        {
            throw new Exception('Uno de los datos necesarios para generar la firma no es válido');
        }

        return $signature;
    }

    /**
     * Operación que genera la firma para las operaciones a través
     * de servicio web con la pasarela del banco.
     *
     * La manera de generar la firma es diferente para cada caso
     * contemplado.
     *
     * @return null|string
     * @throws Exception
     */
    private function generateWSSignature()
    {
        $signature = null;

        if
        (
            $this->Ds_Merchant_Amount &&
            $this->Ds_Merchant_Order &&
            $this->Ds_Merchant_MerchantCode &&
            $this->Ds_Merchant_Currency &&
            $this->Ds_Merchant_Pan &&
            $this->Ds_Merchant_Cvv2 &&
            $this->signatureSecretKey
        )
        {
            $signature =
                $this->Ds_Merchant_Amount.
                $this->Ds_Merchant_Order.
                $this->Ds_Merchant_MerchantCode.
                $this->Ds_Merchant_Currency.
                $this->Ds_Merchant_Pan.
                $this->Ds_Merchant_Cvv2.
                $this->Ds_Merchant_TransactionType.
                $this->signatureSecretKey;

            $signature = strtoupper(sha1($signature));
        }
        else
        {
            throw new Exception('Uno de los datos necesarios para generar la firma no es válido');
        }

        return $signature;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getSignatureSecretKey()
    {
        return $this->getSignatureSecretKey();
    }

    public function setSignatureSecretKey($signatureSecretKey)
    {
        $this->signatureSecretKey = $signatureSecretKey;
    }

    public function getDsMerchantAmount()
    {
        return $this->Ds_Merchant_Amount;
    }

    public function setDsMerchantAmount($Ds_Merchant_Amount)
    {
        $this->Ds_Merchant_Amount = TPVCurrencyFormatter::format($Ds_Merchant_Amount);
    }

    public function getDsMerchantCurrency()
    {
        return $this->Ds_Merchant_Currency;
    }

    public function setDsMerchantCurrency($Ds_Merchant_Currency)
    {
        $this->Ds_Merchant_Currency = $Ds_Merchant_Currency;
    }

    public function getDsMerchantOrder()
    {
        return $this->Ds_Merchant_Order;
    }

    public function setDsMerchantOrder($Ds_Merchant_Order)
    {
        $this->Ds_Merchant_Order = $Ds_Merchant_Order;
    }

    public function getDsMerchantMerchantCode()
    {
        return $this->Ds_Merchant_MerchantCode;
    }

    public function setDsMerchantMerchantCode($Ds_Merchant_MerchantCode)
    {
        $this->Ds_Merchant_MerchantCode = $Ds_Merchant_MerchantCode;
    }

    public function getDsMerchantMerchantURL()
    {
        return $this->Ds_Merchant_MerchantURL;
    }

    public function setDsMerchantMerchantURL($Ds_Merchant_MerchantURL)
    {
        $this->Ds_Merchant_MerchantURL = $Ds_Merchant_MerchantURL;
    }

    public function getDsMerchantMerchantSignature()
    {
        $signature = null;

        if($this->connectionMode == TPV::TPV_CONNECTION_MODE)
        {
            $signature = $this->generateTPVSignature();
        }
        elseif($this->connectionMode == TPV::WS_CONNECTION_MODE)
        {
            $signature = $this->generateWSSignature();
        }
        else
        {
            throw new Exception('Error al generar la firma del cliente TPV, el modo de conexión no es adecuado');
        }

        return $signature;
    }

    public function setDsMerchantMerchantSignature($Ds_Merchant_MerchantSignature)
    {
        $this->Ds_Merchant_MerchantSignature = $Ds_Merchant_MerchantSignature;
    }

    public function getDsMerchantTerminal()
    {
        return $this->Ds_Merchant_Terminal;
    }

    public function setDsMerchantTerminal($Ds_Merchant_Terminal)
    {
        $this->Ds_Merchant_Terminal = $Ds_Merchant_Terminal;
    }

    public function getDsMerchantSumTotal()
    {
        return $this->Ds_Merchant_SumTotal;
    }

    public function setDsMerchantSumTotal($Ds_Merchant_SumTotal)
    {
        $this->Ds_Merchant_SumTotal = $Ds_Merchant_SumTotal;
    }

    public function getDsMerchantTransactionType()
    {
        return $this->Ds_Merchant_TransactionType;
    }

    public function setDsMerchantTransactionType($Ds_Merchant_TransactionType)
    {
        $this->Ds_Merchant_TransactionType = $Ds_Merchant_TransactionType;
    }

    public function getDsMerchantPan()
    {
        return $this->Ds_Merchant_Pan;
    }

    public function setDsMerchantPan($Ds_Merchant_Pan)
    {
        $this->Ds_Merchant_Pan = $Ds_Merchant_Pan;
    }

    public function getDsMerchantCvv2()
    {
        return $this->Ds_Merchant_Cvv2;
    }

    public function setDsMerchantCvv2($Ds_Merchant_Cvv2)
    {
        $this->Ds_Merchant_Cvv2 = $Ds_Merchant_Cvv2;
    }

    public function getDsMerchantExpirydate()
    {
        return $this->Ds_Merchant_Expirydate;
    }

    public function setDsMerchantExpirydate($Ds_Merchant_Expirydate)
    {
        $this->Ds_Merchant_Expirydate = $Ds_Merchant_Expirydate;
    }
}