<?php

class Router {
    private $defaultController;
    private $defaultMethod;

    public function __construct($defaultController, $defaultMethod) {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function route($controllerName, $methodName, $param = null) {
        $controller = $this->getControllerFrom($controllerName);
        $this->executeMethodFromController($controller, $methodName, $param);
    }

    private function getControllerFrom($module) {
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists("Configuration", $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array("Configuration", $validController));
    }

    private function executeMethodFromController($controller, $method, $param = null) {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        if ($param) {
            call_user_func(array($controller, $validMethod), $param);
        } else {
            call_user_func(array($controller, $validMethod));
        }
    }
}
?>