<?php

namespace Kuestions\Lib\View;

class Html extends \ArrayObject {

    public $template;

    public function __construct($template = null, $vars = array()) {
        parent::__construct($vars);
        $this->basePath = \Kuestions\System::$basePath;
        $this->template = $template;
    }
    
    public function render() {
        \ob_start();
        require APP_PATH . 'module/' . $this->template;
        $html = \ob_get_clean();
        return $html;
    }
    
    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }
    
    public function __set($name, $value = null) {
        $this->offsetSet($name, $value);
    }
    
    public function partial($template, $vars = array()) {
        $partial = new Html($template, $vars);
        return $partial->render();
    }

}
