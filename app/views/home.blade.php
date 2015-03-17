<!doctype html>

<html lang="es">

    <head>

        <meta charset="UTF-8">
        <title>Soap Test</title>

    </head>

    <body>

        @if($client)

            <h1>Pago mediante pasarela</h1>

            <form action="{{$client->getUrl()}}" method="post">

                <input type="submit" value="pagar" />

                {{TPVPrinter::printPayFormInputs($client)}}

            </form>

        @endif

        <h1>Pago directo sin pasar por pasarela</h1>

        {{Form::open(array('route' => 'tpv'))}}

            <input type="submit" value="pagar" />

            @if($soapResp)

                @if($soapResp->isSuccess())

                    <b>Operación realizada con éxito</b>

                @else

                    <b>Error en la operación</b>, código de error: {{$soapResp->getCode()}}

                @endif

            @endif

        {{Form::close()}}

    </body>

</html>
