<?php
//-----------------------------------------------------------------------------------------------
//
// firmaPe 2025 - optimizado
// =========================
//
// * Este es un apiRest general para la U de Puno (SUGE), para los sistemas que requieran
//   firma digital en lote .7z o un solo pdf
//
// * Coder : Ramiro Pedro Laura Murillo
// * Fecha : 25 de Enero de 2025
//
//-----------------------------------------------------------------------------------------------
function createToken() // el firme
{
    $clientId  = "";    // Reemplaza con tu client_id
    $clientSec = "";   // Reemplaza con tu client_secret
    $tokenUrl  = "https://apps.firmaperu.gob.pe/admin/api/security/generate-token";

    if( empty($clientId) || empty($clientSec) || empty($tokenUrl) )
        die("El archivo fwAuthorization.json no contiene todos los campos necesarios.");

    //
    // client_id=asdvs&client_secret=3s4d55etr5
    //
    $postData = http_build_query( ['client_id' => $clientId, 'client_secret' => $clientSec] );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded'] );

    $response = curl_exec($ch);
    curl_close($ch);

    // $responseData = json_decode($response, true);
    return $response;
}

// verfica caducidad y crea, de no existir crea nuevo sino en txt
//
function realGenToken()
{
    $token = cargarToken();

    if (!$token) {
        //echo "No hay token almacenado, generamos";
        saveToken( createToken() );
        $token = cargarToken();
    }

    // deciframos
    //
    $decoded = decodeJwt($token);
    $exp = $decoded['payload']['exp'] ?? null;
    $kid = $decoded['header']['kid'] ?? 'No disponible';

    // si expiro el Token, crear y recargar
    //
    if( time() > $exp ){

        saveToken( createToken() );
        $token = cargarToken();
    }

    return $token;
}

function saveToken( $token )
{
    file_put_contents( "../token/tolkien.txt", $token );
}

function cargarToken()
{
    if ( ! file_exists("../token/tolkien.txt") ) return null;

    $datos = file_get_contents("../token/tolkien.txt");
    return $datos;
}

function shortToken( $token )
{
    $primero = substr($token, 0, 90);
    $ultimo = substr($token, -90);

    $resultado = $primero . "..." . $ultimo;

    return $resultado;
}


function decodeJwt($token)
{
    //
    // base64 -> {"alg":"ES512","typ":"JWT","kid":"1655326712741"}.blablablabla
    //

    // Dividir el token en sus partes
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        throw new Exception("Token JWT inválido.");
    }

    // Decodificar el encabezado (header)
    $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0]));
    if ($header === false) {
        throw new Exception("Error al decodificar el encabezado.");
    }

    $headerData = json_decode($header, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el JSON del encabezado.");
    }

    // Decodificar el payload
    $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
    if ($payload === false) {
        throw new Exception("Error al decodificar el payload.");
    }

    $payloadData = json_decode($payload, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el JSON del payload.");
    }

    return [
        'header' => $headerData,
        'payload' => $payloadData
    ];
}

// funcion no usada solo es para alternativa de uso sin CURL
//
function createTokenPrev()
{
    /*
    // Ruta al archivo fwAuthorization.json
    $authFile = 'fwAuthorization.json';

    // Verificar si el archivo existe
    if (!file_exists($authFile)) {
        die("El archivo fwAuthorization.json no existe.");
    }

    // Leer el contenido del archivo JSON
    $authData = file_get_contents($authFile);
    if ($authData === false) {
        die("Error al leer el archivo fwAuthorization.json.");
    }

    // Decodificar el JSON
    $authConfig = json_decode($authData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error al decodificar el archivo JSON.");
    }

    $authConfig['client_id'];
    $authConfig['client_secret'];
    $authConfig['token_url'];
    */


    // credenciales: Ing. René
    //
    $clientId     = "";
    $clientSecret = "";
    $tokenUrl     = "https://apps.firmaperu.gob.pe/admin/api/security/generate-token";
    if (empty($clientId) || empty($clientSecret) || empty($tokenUrl)) {
        die("El archivo fwAuthorization.json no contiene todos los campos necesarios.");
    }

    $postData = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($postData),
        ],
    ];

    $context  = stream_context_create($options);
    $response = file_get_contents($tokenUrl, false, $context);


    if ($response === false) {
        die("Error al realizar la solicitud POST.");
    }

    // directo y limpio
    echo $response;
}

?>