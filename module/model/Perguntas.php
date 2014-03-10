<?php

namespace Kuestions\Model;

class Perguntas {

    public $sequence = 'perguntas_cod_seq';

    public function fetchAll() {
        try {
            $rowsPerPage = \Kuestions\System::$config['system']['view']['datagrid']['rowsPerPage'];

            $dml = <<<DML
                    SELECT
                        p.cod,
                        p.descricao,
                        p.alternativa1,
                        a1.descricao as alternativa1_desc,
                        p.alternativa2,
                        a2.descricao as alternativa2_desc,
                        p.alternativa3,
                        a3.descricao as alternativa3_desc,
                        p.alternativa4,
                        a4.descricao as alternativa4_desc,
                        p.alternativa5,
                        a5.descricao as alternativa5_desc,
                        p.correta,
                        c.descricao as correta_desc,
                        p.tags,
                        p.categoria,
                        cat.nome as categoria_desc,
                        p.status
                    FROM
                        perguntas p
                        INNER JOIN alternativa a1 ON (a1.cod = p.alternativa1)
                        INNER JOIN alternativa a2 ON (a2.cod = p.alternativa2)
                        INNER JOIN alternativa a3 ON (a3.cod = p.alternativa3)
                        INNER JOIN alternativa a4 ON (a4.cod = p.alternativa4)
                        INNER JOIN alternativa a5 ON (a5.cod = p.alternativa5)
                        INNER JOIN alternativa c ON (c.cod = p.correta)
                        INNER JOIN categorias cat ON (cat.cod = p.categoria)
                    ORDER BY p.cod
                    LIMIT {$rowsPerPage}
                    OFFSET 0
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
            if (isset($row['cod']) && $row['cod']) {
                return $this->update($row);
            } else {
                return $this->insert($row);
            }
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function insert(&$row) {
        try {
            $dml = <<<DML
                    INSERT INTO perguntas
                        (descricao, alternativa1, alternativa2, alternativa3, alternativa4, alternativa5, correta, tags)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            $salvou = $stmt->execute(array(
                $row['descricao'],
                $row['alternativa1'],
                $row['alternativa2'],
                $row['alternativa3'],
                $row['alternativa4'],
                $row['alternativa5'],
                $row['correta'],
                $row['tags'],
            ));

            if ($salvou) {
                $row['cod'] = \Kuestions\System::$db->lastInsertId($this->sequence);
            }

            return $salvou;
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

    public function update(&$row) {
        try {
            $data = $row;
            unset($data['cod']);
            $set = '';

            foreach ($data as $dataIndex => $dataValue) {
                $set.= ",{$dataIndex}='{$dataValue}'";
            }

            $set = substr($set, 1);

            $dml = <<<DML
                    UPDATE perguntas
                        SET {$set}
                    WHERE cod = ?
DML;
            $stmt = \Kuestions\System::$db->prepare($dml);
            return $stmt->execute(array(
                        $row['cod']
            ));
        } catch (\Exception $ex) {
            xd($ex);
        }
    }

}
