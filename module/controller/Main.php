<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class Main extends Controller {
    
    public function init() {
        \Kuestions\System::$layout->topItemAtual = 'painel';
    }

    public function index() {
        try {
            
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
