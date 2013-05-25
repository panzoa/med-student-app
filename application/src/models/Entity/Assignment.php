<?php
class Entity_Assignment extends Zend_Db_Table_Row_Abstract {
    public function getAssignmentTasks() {
        $assignmentTasks = new AssignmentTasks();

        $select = $assignmentTasks->select();
        $select->where('assignment_id = ' . $this->id);

        $tasks = $assignmentTasks->fetchAll($select);

        return $tasks;
    }

    public function getCases() {
        $cases = new Cases();

        $select = $cases->select();
        $select->where('hospital_id = ' . $this->hospital_id);

        $select->where("date >= '" . $this->start . "'");
        if ($this->end) {
            $select->where("date < '" . $this->end . "'");
        }

        $cases = $cases->fetchAll($select);

        return $cases;
    }
}