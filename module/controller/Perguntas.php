<?php

namespace Kuestions\Controller;

use Kuestions\Module\Controller;

Class Perguntas extends Controller {
    
    public $service;
    
    public function init() {
        $this->service = new \Kuestions\Service\Perguntas();
    }

    public function salvarPergunta() {
        try {
            $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());
            
            $row = $this->request->post->getArrayCopy();
            if(!isset($row['cod'])) {
                $row['cod'] = null;
            }
            
            $salvou = $this->service->save($row);
            
            if($salvou) {
                $this->view->message = 'Pergunta salva com sucesso.';
                $this->view->error = false;
            } else {
                $this->view->message = 'Erro ao tentar salvar Pergunta.';
                $this->view->error = true;
            }
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }
    
    public function salvarAlternativa() {
        try {
            $this->view = new \Kuestions\Lib\View\Json($this->request->post->getArrayCopy());
            
            $row = $this->request->post->getArrayCopy();
            if(!isset($row['cod'])) {
                $row['cod'] = null;
            }
            
            $service = new \Kuestions\Service\Alternativa();
            $salvou = $service->save($row);
            
            if($salvou) {
                $this->view->message = 'Alternativa salva com sucesso.';
                $this->view->error = false;
            } else {
                $this->view->message = 'Erro ao tentar salvar Alternativa.';
                $this->view->error = true;
            }
            
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
