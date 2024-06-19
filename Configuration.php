<?php


use PHPMailer\PHPMailer\PHPMailer;

include_once("vendor\PHPMailer\src\Exception.php");
include_once("vendor\PHPMailer\src\PHPMailer.php");
include_once("vendor\PHPMailer\src\SMTP.php");

include_once ("Controller/RegistroController.php");
include_once ("Controller/HomeController.php");
include_once ("Controller/LoginController.php");
include_once ("Controller/JuegoController.php");
include_once ("Controller/AdminController.php");
include_once ("Controller/RankingController.php");
include_once ("Controller/VerPerfilPropioController.php");
include_once ("Controller/VerPerfilAjenoController.php");
include_once ("Controller/ActivacionController.php");
include_once ("Controller/EdicionController.php");
include_once ("Controller/SuggestQuestionController.php");

include_once ("helper/Router.php");
include_once ("helper/DataBase.php");
include_once ("helper/MustachePresenter.php");
include_once ("vendor/mustache/src/Mustache/Autoloader.php");
include_once ('vendor/PHPMailer/src/PHPMailer.php');


include_once ('Model/AdminModel.php');
include_once ('Model/UserModel.php');
include_once ('Model/GameModel.php');
include_once ('Model/EdicionModel.php');
include_once ('Model/SuggestedQuestionModel.php');

class Configuration
{

    //controller
    public static function getRegistroController()
    {
        return new RegistroController(self::getUserModel(), self::getPresenter());
    }
    public static function getHomeController(){
        return new HomeController(self::getUserModel(), self::getPresenter());
    }
    public static function getLoginController(){
        return new LoginController(self::getUserModel(), self::getPresenter());
    }
    public static function getJuegoController(){
        return new JuegoController(self::getGameModel(), self::getPresenter());
    }
    public static function getAdminController(){
        return new AdminController(self::getAdminModel(), self::getPresenter());
    }
    public static function getActivacionController(){
        return new ActivacionController(self::getUserModel(), self::getPresenter());
    }
    public static function getRankingController(){
        return new RankingController(self::getUserModel(), self::getPresenter());
    }

    public static function getVerPerfilPropioController(){
        return new VerPerfilPropioController(self::getUserModel(), self::getPresenter());
    }

    public static function getVerPerfilAjenoController(){
        return new VerPerfilAjenoController(self::getUserModel(), self::getPresenter());
    }
    public static function getEdicionController()
    {
        return new EdicionController(self::getEdicionModel(), self::getPresenter());
    }

    public static function getSuggestQuestionController()
    {
        return new SuggestQuestionController(self::getSuggestedQuestionModel(), self::getPresenter());
    }

    //model
    public static function getUserModel()
    {
        return new UserModel(self::Database(),self::getMail());
    }
    public static function getGameModel()
    {
        return new GameModel(self::Database());
    }
    public static function getAdminModel()
    {
        return new AdminModel(self::Database());
    }
    public static function getEdicionModel()
    {
        return new EdicionModel(self::Database());
    }

    public static function getSuggestedQuestionModel()
    {
        return new SuggestedQuestionModel(self::Database());
    }

    //Helper
    public static function getRouter()
    {
        return new Router(
            "getHomeController", "get");
    }

    private static function getPresenter()
    {

        return new MustachePresenter("view/template");
    }
    private static function getConfig()
    {
        return parse_ini_file("config/config.ini");
    }

    public static function Database()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["database"], $config["password"]);
    }

    public static function getConfigMail()
    {
        return parse_ini_file("config/mail.ini");
    }

    private static function getMail()
    {
        return new PHPMailer();
    }

}