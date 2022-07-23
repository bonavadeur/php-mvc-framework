<?php

namespace bonavadeur\core;

use bonavadeur\core\middlewares\BaseMiddleware;

class Controller {
    public string $layout = 'main';
    public string $action = '';
    public array $middlewares = [];

    public function render($view, $params = []) {
        return Application::$app->router->renderView($view, $params);
    }

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares() : array {
        return $this->middlewares;
    }
}