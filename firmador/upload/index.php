<?php
/*
 *  Modulo de recepcion de archivo firmado enviado por FirmaPeru 2025
 *  - Ideado por Ramiro Pedro Laura Murillo
 *  - Enero de 2025
 *
 *  firmaperu envia con ?result=arch[f].pdf que le dimos al crear el param de envio
 *
 */


if (isset($_FILES['signed_file']) && $_FILES['signed_file']['error'] == UPLOAD_ERR_OK){

    // $fileName = $_FILES['signed_file']['name'];
    // $fileSize = $_FILES['signed_file']['size'];
    $fileTmpPath   = $_FILES['signed_file']['tmp_name'];
    $fileType      = $_FILES['signed_file']['type'];
    $fileNameCmps  = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Directorio donde se guardará el archivo (carpeta actual)
    //
    $fileName = isset($_GET["result"])? $_GET["result"] : ("doc[F]." . $fileExtension);


    // set folder
    //
    $destPath = '../public/' . $fileName;

    // Mover el archivo a la carpeta deseada
    //
    if( move_uploaded_file($fileTmpPath, $destPath) )
        $res = "El archivo $fileName se ha subido correctamente.";
    else
        $res = "Hubo un error al subir el archivo $fileName.";

} else {
    $res = "No se ha enviado ningún archivo, error en la subida.";
}

file_put_contents("log.txt", $res);
?>