<?php

namespace Kuestions\Lib\View\Helper\Html;

abstract class Helper {

    /**
     * @var \Kuestions\Lib\View\Html
     */
    public $view;
    
    public function __construct() {
        $this->view = new \Kuestions\Lib\View\Html();
    }

}
