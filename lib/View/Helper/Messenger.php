<?php

namespace Kuestions\Lib\View\Helper;

class Messenger {

    public static function success($message) {
        $session = new \Kuestions\Lib\System\Session('__KuestionsMessenger__');
        $success = $session->success;
        $success[] = $message;
        $session->success = $success;
    }

    public static function error($message) {
        $session = new \Kuestions\Lib\System\Session('__KuestionsMessenger__');
        $error = $session->error;
        $error[] = $message;
        $session->error = $error;
    }
    
    public static function successNow($message) {
        $success = \Kuestions\System::$layout->success;
        $success[] = $message;
        \Kuestions\System::$layout->success = $success;
    }

    public static function errorNow($message) {
        $error = \Kuestions\System::$layout->error;
        $error[] = $message;
        \Kuestions\System::$layout->error = $error;
    }

    public static function getSuccess() {
        $session = new \Kuestions\Lib\System\Session('__KuestionsMessenger__');
        $success = $session->success ? $session->success : array();
        $session->success = array();
        return $success;
    }

    public static function getError() {
        $session = new \Kuestions\Lib\System\Session('__KuestionsMessenger__');
        $error = $session->error ? $session->error : array();
        $session->error = array();
        return $error;
    }

}
