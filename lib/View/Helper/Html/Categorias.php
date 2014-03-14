<?php

namespace Kuestions\Lib\View\Helper\Html;

class Categorias extends Helper {

    public function __invoke($name = 'categoria', $id = null, $required = true) {
        $service = new \Kuestions\Service\Categorias();
        $this->view->template = 'view/Helper/categorias.phtml';
        $this->view->request = new \Kuestions\Lib\Http\Request();
        $this->view->categorias = $service->fetchAll();
        $this->view->attrs = array(
            'name' => $name,
            'id' => $id ? $id : $name,
            'required' => $required ? 'required' : null
        );
        
        return $this->view->render();
    }

}
