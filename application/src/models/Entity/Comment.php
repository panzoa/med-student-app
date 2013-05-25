<?php
class Entity_Comment extends Zend_Db_Table_Row_Abstract {
    public function getStudent() {
        $students = new Students();
        $student = $students->getById($this->student_id);

        return $student;
    }
}