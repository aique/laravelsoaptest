<?php

/**
 * Clase que modela la respuesta recibida tras la llamada
 * al servicio web de la pasarela bancaria para solicitar
 * una petición de pago.
 */
class TPVSoapResponse
{
    /**
     * Código de respuesta devuelto por el servicio web.
     *
     * Será 0 si la operación ha ido bien, o una cadena de texto
     * con el código de error en caso de que la operación no se
     * haya podido realizar.
     *
     * @var string
     */
    private $code;

    /**
     * Indica simplemente si la operación ha tenido exito
     * con una variable booleana.
     *
     * @var boolean
     */
    private $success;

    public function __construct()
    {
        $this->code = null;
        $this->success = false;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        if($code == '0')
        {
            $this->success = true;
        }

        $this->code = $code;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }
}