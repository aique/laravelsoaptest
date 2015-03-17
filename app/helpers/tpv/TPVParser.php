<?php

class TPVParser
{
    /**
     * Método que parsea la respuesta recibida por el servicio
     * web y devuelve un objeto con la información recibida.
     *
     * @param string $response
     *
     *      Cadena de texto con la respuesta recibida por el
     *      servicio web.
     *
     * @return TPVSoapResponse
     */
    public static function parseWSResponse($response)
    {
        $xmlReader = new XMLReader();

        $xmlReader->xml($response);

        $response = new TPVSoapResponse();

        while($xmlReader->read())
        {
            if($xmlReader->nodeType == XMLReader::ELEMENT)
            {
                $exp = $xmlReader->expand();

                if($exp->nodeName == 'CODIGO')
                {
                    $response->setCode($exp->nodeValue);
                }
            }

        }

        return $response;
    }
}