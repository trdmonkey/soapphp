<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

$namespace = "miSuperSoap2024.alv";
$server = new soap_server();
$server->configureWSDL("PinchesSOAP", $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

/* CREAMOS LA ORDEN DE COMPRA (QUE VAMOS A ENVIAR) CON LOS TIPOS */
$server->wsdl->addComplexType(
    'ordenDeCompra',
    'complexType',
    'struct',
    '',
    array(
        'NumeroOrden' => array('name' => 'NumeroOrden', 'type' => 'xsd:string'),
        'Ordenante' => array('name' => 'Ordenante', 'type' => 'xsd:string'),
        'Moneda' => array('name' => 'Moneda', 'type' => 'xsd:string'),
        'TipoCambio' => array('name' => 'TipoCambio', 'type' => 'xsd:decimal')
    )
);

/* ESTA ES LA ORDEN DE COMPRA QUE TENDRA LA RESPUESTA (DESPUES DE ENVIAR), CON LOS TIPOS */
$server->wsdl->addComplexType(
    'response',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'NumeroDeAutorizacion' => array('name' => 'NumeroDeAutorizacion', 'type' => 'xsd:string'),
        'Resultado' => array('name' => 'Resultado', 'type' => 'xsd:boolean')
    )
);

$server->register(
    'guardarOrdenDeCompra', 
    array('name' => 'tns:ordenDeCompra'),
    array('name' => 'tns:response'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Recibe una orden de compra y regresa un numero de autorización'
);

/* ESTA ES LA FUNCION QUE CONTIENE LOS DETALLES DE LA COMPRA */
function guardarOrdenDeCompra($request) {
    return array(
        "NumeroDeAutorizacion" => "La orden de compra " . $request['NumeroOrden'] . " ha sido autorizada con el numero " . rand(10000, 100000),
        "Resultado" => true
    );
}

/* RECEPCION DE LOS DATOS SEGUN LA VERSION DE PHP */
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit;
