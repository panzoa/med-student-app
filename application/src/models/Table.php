<?php
abstract class Table extends Zend_Db_Table_Abstract {
    public function getAll()
    {
        return $this->fetchAll($this->select());
    }
}