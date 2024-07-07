<?php
include_once ("Configuration.php");
require 'vendor/autoload.php';

session_start();

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? 'get';

$router =Configuration::getRouter();
$router->route($controller,$action);
