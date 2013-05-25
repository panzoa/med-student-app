<?php
class Students extends Table {
    protected $_name = 'students';
    protected $_rowClass = 'Entity_Student';

    /**
     * @return Entity_Student
     */
    public function getStudent() {
        // id of the default user hardcoded as we have no authentication procedure in the mockup
        $student =  $this->getById(1);
        return $student;
    }
}