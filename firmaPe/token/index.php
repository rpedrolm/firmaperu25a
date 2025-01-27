<?php
//-----------------------------------------------------------------------------------------------
//
// firmaPe 2025 - optimizado
// =========================
//
// * Carga el token bufferizado, si ya caduco las 24h crea nuevo usando client_id, secret_id
//   para optimizar tiempo de carga se genera una sola vez al dia
//
// * Coder : Ramiro Pedro Laura Murillo
// * Fecha : 25 de Enero de 2025
//
//-----------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------
// Generador de Token solo por caducidad de 24h, mientras optimiza usar el previo
//-------------------------------------------------------------------------------------
//
// token.php?gen=true&pass=
//


error_reporting(E_ALL);
ini_set('display_errors', 1);


require ( "../lib.php" );


// el proceso de bufferizado y creacion en unica funcion
//
$token = realGenToken();



// API REST --> POST only
//
//if( isset($_GET['gen'], $_GET['pass']) && $_GET['pass'] == "0x2020" )
//
if( isset($_POST['gen'],$_POST['pass']) && $_POST['pass'] == '4e4100bf7b2e9b98b1' )
{
    // mostrar al desnudo, listo para usar API
    //
    echo $token;

} else {

    // Mostrar información parcial del token actual
    //
    $decoded = decodeJwt($token);
    $exp = $decoded['payload']['exp'] ?? null;
    $kid = $decoded['header']['kid'] ?? 'No disponible';

    if ($exp) {

        $expiration = date('Y-m-d H:i:s', $exp);

        if(time() > $exp){
            $msg = "El token HA EXPIRADO (generando F5).";
            saveToken( createToken() );
        } else {
            $msg = "El token ESTÁ ACTIVO.";
        }

    } else {
        $expiration = "No disponible";
        $msg = "El token no contiene fecha de expiración.";
    }

    $arr = [
        "engine"     => "RPLM - FirmaPe",
        "kid"        => $kid,
        "expiration" => $expiration,
        "message"    => $msg,
        "token"      => shortToken($token)
    ];

    echo "<pre>";
    print_r($arr);
}


?>