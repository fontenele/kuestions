<?php

namespace Kuestions\Module;

class Controller {

    /**
     * @var \Kuestions\Lib\View\Html
     */
    public $view;
    /**
     * @var string
     */
    public $action;
    /**
     * @var \Kuestions\Lib\Http\Request
     */
    public $request;

    public function __construct($action, $request) {
        $arrClass = \explode('\\', \get_class($this));
        $this->action = $action;

        $templace = \strpos($action, '-') === false ? \Kuestions\Lib\View\Helper\String::camelToDash($action) : $action;
        $this->view = new \Kuestions\Lib\View\Html("view/{$arrClass[2]}/{$templace}.phtml", array('request' => $request));
        
        $this->request = $request;

        $this->init();
    }

    public function init() {
        
    }
    
    public function redirect($path = null) {
        $path = \Kuestions\System::$basePath . $path;
        header("Location: {$path}");
        exit;
    }

}
