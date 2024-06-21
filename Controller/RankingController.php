<?php
include_once ('vendor/phpqrcode/qrlib.php');

class RankingController {
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter) {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }


    public function get()
    {
        $rankingData = $this->model->getRankingData();
        foreach ($rankingData as &$player) {
            $player['qr_code'] = $this->generateQrCode($player['id']);
        }

        $this->presenter->render("view/Ranking.mustache", ['players' => $rankingData]);
    }

    private function generateQrCode($userId)
    {
        $url = "http://localhost/verPerfilAjeno/get&user=$userId";
        $qrCodePath = 'public/img/qrs/' . $userId . '.png';
        QRcode::png($url, $qrCodePath, QR_ECLEVEL_H, 3);
        return $qrCodePath;



    }


}