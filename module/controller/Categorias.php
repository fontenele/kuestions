<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class Categorias extends Controller {
    
    public $service;
    
    public function init() {
        \Kuestions\System::$layout->topItemAtual = 'painel';
        $this->service = new \Kuestions\Service\Categorias();
    }

    public function novaCategoria() {
        try {
            $this->view->categorias = $this->service->fetchAll();
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }
    
    public function salvarCategoria() {
        try {
            $row = $this->request->post->getArrayCopy();
            if(!isset($row['cod'])) {
                $row['cod'] = null;
            }
            
            $salvou = $this->service->save($row);
            if($salvou) {
                \Kuestions\Lib\View\Helper\Messenger::success('Categoria salva com sucesso.');
            } else {
                \Kuestions\Lib\View\Helper\Messenger::error('Erro ao tentar salvar Categoria.');
            }
            
            return $this->redirect('categorias/nova-categoria');
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
