<?php
class Entity_Assignment extends Zend_Db_Table_Row_Abstract {
    public function getAssignmentTasks() {
        $assignmentTasks = new AssignmentTasks();

        $select = $assignmentTasks->select();
        $select->where('assignment_id = ' . $this->id);

        $tasks = $assignmentTasks->fetchAll($select);

        return $tasks;
    }
}