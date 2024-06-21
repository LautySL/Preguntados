<?php
include("vendor\phpqrcode\qrlib.php");
class Gererador_qr
{
    public function __construct(){
    }

    public function qr($url){
        QRcode::png($url,false,QR_ECLEVEL_L,8);
    }
}


