<?php
/*
 *  Modulo de recepcion de archivo firmado enviado por Watana y Tocapu
 *  - Ideado por Ramiro Pedro Laura Murillo
 *  - Agosto de 2021
 */


    // Argumento upload watana-tocapu poner: http://server/wroot/pdfs/?name=documento[F].pdf
    //
    // watana - tocapu
    //--------------------------------------------------------------------------------------
    $archfirm = isset($_GET['name'])? $_GET['name'] : "nonamed[R].pdf";

    // seria probar con stdin, podria funcionar
    //
    $obj = fopen("php://input", "r");
    $res = stream_get_contents($obj);

    file_put_contents( $archfirm, $res);

    //--------------------------------------------------------------------------------------
    // http://fcd.org.pe/suficiencia/wroot/conf/repo/
    //--------------------------------------------------------------------------------------
?>