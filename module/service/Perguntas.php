<?php

namespace Kuestions\Service;

class Perguntas {
    
    public function fetchAll() {
        $model = new \Kuestions\Model\Perguntas();
        return $model->fetchAll();
    }

    public function save(&$row) {
        $model = new \Kuestions\Model\Perguntas();
        return $model->save($row);
    }

}
