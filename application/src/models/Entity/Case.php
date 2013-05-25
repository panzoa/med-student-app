<?php
class Entity_Case extends Zend_Db_Table_Row_Abstract {
    public function getComments() {
        $comments = new CaseComments();

        $select = $comments->select();
        $select->where('case_id = ' . $this->id);
        $select->order('date ASC');

        $comments = $comments->fetchAll($select);
        return $comments;
    }
}