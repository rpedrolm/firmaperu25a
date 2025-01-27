<?php
//
// Qelqana Signer (desactivado) : firmador integrado con ReFirma PCX (los tokens estan en mi correo)
// Julio 2021 para Tramite Documentario V.
//

$enc = base64_encode( '{
            "userId" : "demoUsr",
            "view"   : true,
            "page"   : "1",
            "posX"   : "5",
            "posY"   : "90",
            "newPdf" : "local[R].pdf",
            "urlPdf" : "https://server.pe/bind/firmar/pdf/local.pdf",
            "url2Up" : "https://server.pe/bind/firmar/subir.php"
        }' );

$url = "https://webserviceOurs.pe:8080/qelqana/api/$enc";

//echo "<a href=\"$url\" target=_blank> FIRMAR </a>";

?>

<?php
//
// Area de recepcion del documento enviado por el firmador
//

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

/*
    if (isset($_FILES['apiForm']))
    {
        $tmp    = dirname(tempnam (null,''));

        $file_name = $_FILES['apiForm']['name'];
        $file_temp = $_FILES['apiForm']['tmp_name'];
        $tamanio   = $_FILES["apiForm"]["size"];
        $tipo      = $_FILES["apiForm"]["type"];

        $file_name = urldecode($file_name);

        move_uploaded_file($file_temp, getcwd()."/pdf/".$file_name);
    }
*/

$file = $_POST["apiForm"];
$name = $_POST["pdfName"];
if( ! $file ) return;

$save = file_put_contents( getcwd()."/pdf/".$name, $file );