<?php


// verificar el HTTP_REFERER solo los permitidos pueden usar


/*
if( isset($_SERVER['HTTP_REFERER']) ){
    echo $_SERVER['HTTP_REFERER'];
} else {
    echo "Sin Referer";
}
*/

$arch = $_POST["arch"] ?? "";
$tipo = $_POST["tipo"] ?? "1";
$role = $_POST["role"] ?? "";
$move = $_POST["mover"] ?? "false";
$visi = $_POST["visible"] ?? "true";

$move = $move=="on"? "true" : "false";
$visi = $visi=="on"? "true" : "false";

$args = "?arch=" . urlencode($arch)
      . "&role=" . urlencode($role)
      . "&tipo=$tipo"
      . "&mover=$move"
      . "&visible=$visi" ;


$param = '
{
    "param_url" : "https://server.pe/firmaPe/param/'.$args.'",
    "param_token": "1626476967",
    "document_extension": "pdf"
}';

$obj = [
    "success" => true,
    "msg"     => "Acceso restringido con credencial UNAP",
    "param"   => base64_encode($param)
];

echo json_encode( $obj );