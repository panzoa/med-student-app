<?php
class Entity_Student extends Zend_Db_Table_Row_Abstract {
    public function getCurrentAssignment() {
        $assignments = new Assignments();

        $select = $assignments->select();
        $select->where('student_id = ' . $this->id);
        $select->where('end IS NULL');

        $assignment = $assignments->fetchRow($select);

        return $assignment;
    }

    /**
     * @return Entity_Hospital
     */
    public function getCurrentHospital() {
        $assignment = $this->getCurrentAssignment();

        $hospitals = new Hospitals();
        $results = $hospitals->find($assignment->hospital_id);

        return $results[0];
    }
}