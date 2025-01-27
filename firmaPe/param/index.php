<?php
//-----------------------------------------------------------------------------------------------
//
// firmaPe 2025 - optimizado
// =========================
//
// * Generador de Param por APIrest
//   nomre de .pdf o .zip, logo, posXY, pagina, rol y nada mas
//   el token se genera de forma automatizada para velocidad
//
// * Coder : Ramiro Pedro Laura Murillo
// * Fecha : 24 de Enero de 2025
//
//-----------------------------------------------------------------------------------------------

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ( "../lib.php" );


// lo mas importante
$token = realGenToken();

//
// params/?$arg1=uno&arg2=dos...etc
//
$tipoFirma = $_REQUEST["tipo"] ?? "1";
$moverPos  = $_REQUEST["mover"] ?? "false";
$firmante  = $_REQUEST["role"] ?? "";
$selloPng  = $_REQUEST["stamp"] ?? "https://server/public/iFirma2.png";
$archivo   = $_REQUEST["arch"] ?? "https://server/files/demo.pdf";
$destino   = $_REQUEST["upto"] ?? "https://server/upload/?result=tetolo[F].pdf";

$visible   = $_REQUEST["visible"] ?? "true";


// if arch == .7z en lote sino PDF alone

//
 // *.FAU | *.FIR | *.Ramiro
//
$param = [
    "certificateFilter"      => ".*",
    "certificationSignature" => false,
    "signatureLevel"         => "B",
    "signatureFormat"        => "PAdES",
    "signaturePackaging"     => "enveloped",
    "theme"                  => "claro 3",
    "passwordTsa"            => "",
    "userTsa"                => "",
    "webTsa"                 => "",
    "stampTextSize"          => 14,
    "stampWordWrap"          => 38,
    "signatureReason"        => "V.B. de documento",
    "contactInfo"            => "Gobierno y Transformacion Digital - UNA Puno",

    "signatureStyle"         => $tipoFirma,
    "visiblePosition"        => $moverPos,
    "bachtOperation"         => false,
    "oneByOne"               => true,
    "documentToSign"         => $archivo,
    "imageToStamp"           => $selloPng,
    "uploadDocumentSigned"   => $destino,
    "role"                   => $firmante,
    "positionx"              => "10",
    "positiony"              => "12",
    "stampPage"              => "1",
    "token"                  => $token
];



//$obj = json_encode($param, JSON_PRETTY_PRINT);
//echo "<pre> $obj </pre>";

// directo sin tonterias
//
echo base64_encode( json_encode($param) );


/*
$objStr = json_encode( $param );
$objStr = str_replace("\\","",$objStr);
echo base64_encode($objStr);
*/

//file_put_contents( "paramsss.txt", $obj );


//
// firma en lotes un .7z con los .pdf
//

$obj = '
{
    "certificateFilter"     : ".*",
    "bachtOperation"        : true,
    "certificationSignature": false,
    "documentToSign"        : "https://server/public/titulo.7z",
    "imageToStamp"          : "https://server/public/iFirma.png",
    "uploadDocumentSigned"  : "https://server/receive/?result=opa.7z",
    "contactInfo"           : "",
    "role"                  : "DECANO FRONTEND",
    "signatureReason"       : "V.B.",
    "oneByOne"    : false,
    "passwordTsa" : "",
    "positionx"   : "10",
    "positiony"   : "12",
    "signatureFormat":"PAdES",
    "signatureLevel":"B",
    "signaturePackaging":"enveloped",
    "signatureStyle":1,
    "stampPage":1,
    "stampTextSize":13,
    "stampWordWrap":42,
    "theme":"claro",
    "userTsa":"",
    "webTsa":"",
    "visiblePosition":false,
    "token":"eyJhbGciOiJFUzUxMiIsInR5cCI6IkpXVCIsImtpZCI6IjE2NTUzMjY3MTI3NDEifQ.eyJpc3MiOiJQbGF0YWZvcm1hIE5hY2lvbmFsIGRlIEZpcm1hIERpZ2l0YWwgLSBGaXJtYSBQZXLDuiIsInN1YiI6IkZpcm1hZG9yIiwiZXhwIjoxNzM3OTIwODM5LCJpYXQiOjE3Mzc4MzQ0MzksImp0aSI6IktGbGJxT2FFM2pJd01UUTFORGsyTVRjd0Nyam8wQU4zRlEiLCJ2ZXIiOiIxLjAuMCYxLjEuMCIsImVudCI6ImVudGl0eV90eXBlPVNpbiBlc3BlY2lmaWNhciwgZW50aXR5PVVuaXZlcnNpZGFkIE5hY2lvbmFsIGRlbCBBbHRpcGxhbm8gUHVubywgaW5pdGlhbHM9VU5BUCwgZG9jdW1lbnQ9MjAxNDU0OTYxNzAiLCJhcHAiOiJTaXN0ZW1hIGRlIEFkbWlzacOzbiwgTWF0csOtY3VsYSB5IEdlc3Rpw7NuIEFjYWTDqW1pY2EgRXNjdWVsYSBkZSBQb3N0Z3JhZG8iLCJhaWQiOiI2Tlg4b21kcUo0Z2gzTjNGbFNrbmhBeWxTcngzNEliODVvS3RQbDltSHE2WVNqNUpZV1ZiMzVlQkNWbUtHdXh6IiwidG9rIjoiZHRybnFCWFk4VmxXbFJYMy9nTk1CVmhsMzlVTGZXZVF2anN2REhzcUZlWE9UbnRKaXU4NEhzRzgxa2lzZXVmZTFZdHVmYVhMazR1WUN0OGMzYlJsbFE9PSIsIm13cyI6Ii8wZVRUTnJDcG9SSHkwNW9VRFV1aWVwK2xKYjd4c1ltcitWSjd6Y3A3N1VlckIzTkJBVEN4ODJTU0hBQXRmbW96MXZLUExZeHFpK21TNThEZjZPbTVnPT0ifQ.ANfRQYFbJ-4lxroJR8x1frGxwyC2XlLZUrshulbAoss4HfPDoiAQWQYpCksf_hx4z7eFmtEMW7YGos1CLeCfc8FtAEwyLjR4Ju-ntwOTXSiPwyLRWmcHVQDv5X7A8y9yWTE-d9TT81E0R_X9PHbGZHMtr8jBH9-_dU7amqdOdjQ1MfF4"
}
';
