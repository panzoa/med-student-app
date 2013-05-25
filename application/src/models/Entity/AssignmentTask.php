<?php
class Entity_AssignmentTask extends Zend_Db_Table_Row_Abstract {
    public function getTask() {
        $tasks = new Tasks();
        $results = $tasks->find($this->task_id);

        return $results[0];
    }
}