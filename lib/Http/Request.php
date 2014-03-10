<?php

namespace Kuestions\Lib\Http;

class Request {

    /**
     * @var \ArrayObject
     */
    public $get;

    /**
     * @var \ArrayObject
     */
    public $post;

    /**
     * @var \ArrayObject
     */
    public $files;

    /**
     * @var string
     */
    public $query;

    public function __construct() {
        $this->get = new \ArrayObject($_GET);

        /* $post = array();
          foreach($_POST as $index => $value) {
          $post[$index] = utf8_decode($value);
          } */
        $this->post = new \ArrayObject($_POST);

        $this->files = new \ArrayObject($_FILES);
    }

    public function setQuery($queryString) {
        $this->query = $queryString;
        $query = explode('&', $queryString);
        foreach ($query as $item) {
            $get = explode('=', $item);
            $this->get->offsetSet($get[0], $get[1]);
        }
    }

}