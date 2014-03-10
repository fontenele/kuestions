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
            
            $paginator = new \Kuestions\Lib\View\Helper\Paginator('#fm-perguntas', $this->request->get->getArrayCopy());
            
            $servicePerguntas = new \Kuestions\Service\Perguntas();
            $paginator->setRows($servicePerguntas->fetchAll($paginator));
            $this->view->paginator = $paginator;
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
