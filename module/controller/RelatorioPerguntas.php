<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class RelatorioPerguntas extends Controller {
    
    public function init() {
        \Kuestions\System::$layout->topItemAtual = 'relatorios';
    }

    public function todasPerguntas() {
        try {
            $serviceCategorias = new \Kuestions\Service\Categorias();
            $this->view->categorias = $serviceCategorias->fetchAll();
            
            $servicePerguntas = new \Kuestions\Service\Perguntas();
            $this->view->listagem = $servicePerguntas->fetchAll();
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
