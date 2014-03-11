<?php

namespace Kuestions;

Class System {

    /**
     * @var array
     */
    public static $sslPorts = array('443');

    /**
     * @var array
     */
    public static $config = array();

    /**
     * @var Lib\View\Html
     */
    public static $layout;

    /**
     * @var Lib\Database\Pdo
     */
    public static $db;
    public static $logged = false;

    /**
     * @var string
     */
    public static $basePath;

    public static function app() {
        self::bootLoader();
        self::setConstants();
        self::loadLibs();
        self::loadConfigs();
        self::loadDatabase();
        $valid = self::acl();
        if ($valid) {
            self::run();
        }
    }

    public static function bootLoader() {
        \session_start();
        \header('Content-type: text/html; charset=UTF-8');
        \chdir(\dirname(__DIR__));
        \set_include_path(\get_include_path() . PATH_SEPARATOR . \dirname(__DIR__));
        \spl_autoload_register(function($className) {
            $arrClass = \explode('\\', $className);
            $realPath = '';
            switch ($arrClass[1]) {
                case 'Controller':
                    $realPath = APP_PATH . 'module/controller/' . $arrClass[2] . '.php';
                    break;
                case 'Service':
                    $realPath = APP_PATH . 'module/service/' . $arrClass[2] . '.php';
                    break;
                case 'Model':
                    $realPath = APP_PATH . 'module/model/' . $arrClass[2] . '.php';
                    break;
            }
            if ($realPath) {
                require_once($realPath);
            }
        });
    }

    /**
     * Check if is SSL connection
     * @return boolean
     */
    public static function isSSL() {
        switch (true) {
            case isset($_SERVER['HTTPS']) && \in_array($_SERVER['HTTPS'], array('on', '1')):
            case isset($_SERVER['SERVER_PORT']) && \in_array($_SERVER['SERVER_PORT'], self::$sslPorts):
                return true;
            default:
                return false;
        }
    }

    public static function setConstants() {
        \define('APP_PATH', \dirname(__DIR__) . '/');
        $httpScheme = self::isSSL() ? 'https' : 'http';
        if ($_SERVER['SCRIPT_NAME'] == '/index.php') {
            self::$basePath = "{$httpScheme}://{$_SERVER['SERVER_NAME']}/";
        } else {
            $host = \str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
            self::$basePath = "{$httpScheme}://{$_SERVER['HTTP_HOST']}{$host}/";
        }
    }

    public static function loadLibs() {
        require_once('lib/autoload.php');
    }

    public static function loadConfigs() {
        $dir = new Lib\System\Dir(APP_PATH . 'config');
        foreach ($dir->getFiles() as $_item) {
            $config = include_once("{$dir->dirName}/{$_item}");
            self::$config = \array_merge_recursive(self::$config, $config);
        }
    }

    public static function loadDatabase() {
        self::$db = new Lib\Database\Pdo(self::$config['db']);
    }

    public static function acl() {
        $session = new Lib\System\Session('system');
        if (!$session->offsetExists('usuario')) {
            $defaults = self::$config['system']['module']['defaults'];
            self::$logged = false;
            self::run($defaults['controllerAuth'], $defaults['actionAuth']);
            return false;
        }
        self::$logged = true;
        return true;
    }

    public static function run($controller = null, $action = null) {
        $defaults = self::$config['system']['module']['defaults'];
        $request = new Lib\Http\Request();

        if ((!$controller && !$action) && $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_URI'] != '/') {
            $uri = \explode('/', \substr($_SERVER['REQUEST_URI'], 1));
            $controller = 'Kuestions\Controller\\' . \ucfirst(Lib\View\Helper\String::dashToCamel($uri[0]));
            if (\strpos($uri[1], '?')) {
                $urlQuery = explode('?', $uri[1]);
                $uri[1] = $urlQuery[0];
                $request->setQuery(urldecode($urlQuery[1]));
            }

            for ($i = 2; $i < count($uri); $i = $i + 2) {
                if (isset($uri[$i + 1])) {
                    $request->get->offsetSet($uri[$i], $uri[$i + 1]);
                }
            }

            $action = Lib\View\Helper\String::dashToCamel($uri[1]);
        }

        self::callAction($controller ? $controller : $defaults['controller'], $action ? $action : $defaults['action'], $request);
    }

    public static function callAction($controller, $action, $request) {
        self::$layout = new \Kuestions\Lib\View\Html('layout/' . self::$config['system']['view']['layout']);
        self::$layout->success = Lib\View\Helper\Messenger::getSuccess();
        self::$layout->error = Lib\View\Helper\Messenger::getError();
        self::$layout->userLogged = self::$logged;

        $arrController = explode('\\', $controller);
        $actionDashed = Lib\View\Helper\String::camelToDash($action);
        $pathCssJs = "%s/module/{$arrController[2]}/{$actionDashed}.%s";

        $js = array();
        $css = array();

        if (file_exists(sprintf(APP_PATH . 'web/' . $pathCssJs, 'css', 'css'))) {
            $css[] = sprintf(self::$basePath . $pathCssJs, 'css', 'css');
        }

        if (file_exists(sprintf(APP_PATH . 'web/' . $pathCssJs, 'js', 'js'))) {
            $js[] = sprintf(self::$basePath . $pathCssJs, 'js', 'js');
        }

        self::$layout->css = $css;
        self::$layout->js = $js;

        $controller = '\\' . $controller;

        // Instance controller
        $obj = new $controller($action, $request);

        // Call action
        $view = $obj->$action($request);

        if ($view instanceof Lib\View\Json) {
            // Render Json output
            echo $view->render();
        } else {
            // Set content var
            self::$layout->content = $view->render();

            // Render layout
            echo self::$layout->render();
        }
    }

}

\Kuestions\System::app();
