<?php

namespace app\core;

use \app\core\Controller;
use \app\core\db\Database;
use \app\core\db\DbModel;
use \app\core\Session;
use \app\models\User;

class Application {
    public static string $ROOT_DIR;
    public string $layout = 'main';
    public string $userClass;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public Router $router;
    public static Application $app;
    public ?Controller $controller = null;
    public ?UserModel $user;

    public function __construct($rootPath, array $config) {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->session = new Session();
        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function getController() {
        return $this->controller;
    }

    public function setController(Controller $controller) {
        $this->controller = $controller;
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch(\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(UserModel $user) {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->$primaryKey;
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest() {
        return !self::$app->user;
    }
}