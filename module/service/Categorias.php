<?php

namespace Kuestions\Service;

class Categorias {
    
    public function fetchAll() {
        $categorias = array();
        $model = new \Kuestions\Model\Categorias();
        $result = $model->fetchAll();
        
        foreach($result as $row) {
            if($row['pai']) {
                $categorias[$row['pai']][$row['cod']] = $row;
            } else {
                $categorias[$row['cod']] = $row;
            }
        }
        
        return $categorias;
    }
    
    public function save(&$row) {
        $model = new \Kuestions\Model\Categorias();
        return $model->save($row);
    }

}
