<?php

include("Conectarbd.php");
include("phpqrcode/qrlib.php");

$datos = "Nombre:Cacho";
QRcode::png($datos,false,QR_ECLEVEL_L,8);


QRcode::png('url_del_perfil','cacho.png');