<?php

namespace Kuestions\Model;

class Categorias {

    public $sequence = 'categorias_cod_seq';

    public function fetchAll() {
        try {
            $dml = <<<DML
                SELECT
                    cod,
                    pai,
                    nome
                FROM
                    categorias
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function save(&$row) {
        try {
            $dml = <<<DML
                INSERT INTO categorias (pai, nome) VALUES (?, ?)
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $salvou = $stmt->execute(array(
                $row['pai'] == 0 ? null : $row['pai'],
                $row['nome']
            ));

            if ($salvou) {
                $row['cod'] = \Kuestions\System::$db->lastInsertId($this->sequence);
            }

            return $salvou;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
