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
        try {
            if (!$this->template || !\file_exists(APP_PATH . 'module/' . $this->template)) {
                throw new \Exception("Template {$this->template} not found.");
            }

            \ob_start();
            require APP_PATH . 'module/' . $this->template;
            $html = \ob_get_clean();
            return $html;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function __get($name) {
        return $this->offsetExists($name) ? $this->offsetGet($name) : '';
    }

    public function __set($name, $value = null) {
        $this->offsetSet($name, $value);
    }

    public function __call($name, $arguments) {
        $name = 'Kuestions\Lib\View\Helper\Html\\' . ucfirst($name);
        if (\class_exists($name)) {
            $class = new $name();
            return \call_user_func_array(array($class, "__invoke"), $arguments);
        }
    }

    public function partial($template, $vars = array()) {
        $partial = new Html($template, $vars);
        return $partial->render();
    }

}
