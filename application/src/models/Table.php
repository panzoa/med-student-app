<?php
abstract class Table extends Zend_Db_Table_Abstract {
    public function getAll() {
        return $this->fetchAll($this->select());
    }

    public function getById($id) {
        $results = $this->find($id);

        if (count($results) === 0) {
            throw new Exception('Entity not found');
        }

        return $results[0];
    }
}