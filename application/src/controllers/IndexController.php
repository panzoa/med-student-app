<?php
class IndexController extends Zend_Controller_Action {
    public function preDispatch() {
        $students = new Students();
        $student = $students->getStudent();
        $this->view->student = $student;

        $this->view->hospital = $student->getCurrentHospital();
        $this->view->assignment = $student->getCurrentAssignment();
    }

    public function indexAction() {
        $hospitals = new Hospitals();
        $this->view->hospitals = $hospitals->getAll();

        $tasks = new Tasks();
        $this->view->tasks = $tasks->getAll();
    }

    public function newassignmentAction() {
        $hospitalId = $this->getRequest()->getParam('hospital_id');
        $taskIds = $this->getRequest()->getParam('task_id');

        if (empty($taskIds)) {
            $this->_redirect('/index');
        }

        // closing current assignment
        $students = new Students();
        $student = $students->getStudent();
        $assignment = $student->getCurrentAssignment();
        $assignment->end = date('Y-m-d H:i:s');
        $assignment->save();

        // adding new assignments
        $assignments = new Assignments();
        $assignments->insert(array(
            'student_id'    => $student->id,
            'hospital_id'   => $hospitalId,
            'start'         => date('Y-m-d H:i:s')
        ));

        // assigning tasks
        $students = new Students();
        $student = $students->getStudent();
        $assignment = $student->getCurrentAssignment();
        $assignmentTasks = new AssignmentTasks();
        foreach ($taskIds as $taskId) {
            $assignmentTasks->insert(array(
                'task_id'       => $taskId,
                'assignment_id'  => $assignment->id
            ));
        }

        $this->_redirect('/index');
    }

    public function tasksAction() {
        $students = new Students();
        $student = $students->getStudent();
        $this->view->tasks = $student->getCurrentAssignment()->getAssignmentTasks();
    }

    public function resolvetaskAction() {
        $assignmentTaskId = $this->getRequest()->getParam('assignment_task_id');

        // closing current assignment
        $assignmentTasks = new AssignmentTasks();
        $results = $assignmentTasks->find($assignmentTaskId);
        $assignmentTask = $results[0];

        $assignmentTask->accomplished = date('Y-m-d H:i:s');
        $assignmentTask->save();

        $this->_redirect('/index/tasks');
    }
}
