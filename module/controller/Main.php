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

    public function login() {
        try {
            if (count($this->request->post) && $this->request->post->offsetGet('email') && $this->request->post->offsetGet('senha')) {
                $service = new \Kuestions\Service\Usuarios();
                $auth = $service->auth($this->request->post->offsetGet('email'), md5($this->request->post->offsetGet('senha')));
                if ($auth['logged']) {
                    $session = new \Kuestions\Lib\System\Session('system');
                    $session->usuario = $this->request->post->offsetGet('email');
                    \Kuestions\Lib\View\Helper\Messenger::success('Bem vindo ' . $this->request->post->offsetGet('email'));
                    $this->redirect('main/index');
                } else {
                    \Kuestions\Lib\View\Helper\Messenger::error('Login inválido');
                    $this->redirect('main/login');
                }
            }
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function tryLogin() {
        try {
            xd($this->request);
            return $this->view;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
