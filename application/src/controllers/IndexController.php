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
        $assignmentTask = $assignmentTasks->getById($assignmentTaskId);

        $assignmentTask->accomplished = date('Y-m-d H:i:s');
        $assignmentTask->save();

        $this->_redirect('/index/tasks');
    }

    public function casesAction() {
        $students = new Students();
        $student = $students->getStudent();
        $this->view->cases = $student->getCurrentAssignment()->getCases();

        $specialties = new Specialties();
        $this->view->specialties = $specialties->getAll();
    }

    public function newcaseAction() {
        $complaint = $this->getRequest()->getParam('complaint');
        $specialty_id = $this->getRequest()->getParam('specialty_id');
        $description = $this->getRequest()->getParam('description');

        if (strlen($complaint) === 0) {
            $this->_redirect('/index/cases');
        }

        $students = new Students();
        $student = $students->getStudent();
        $hospital = $student->getCurrentHospital();

        $cases = new Cases();
        $cases->insert(array(
            'hospital_id'   => $hospital->id,
            'complaint'     => $complaint,
            'specialty_id'  => $specialty_id,
            'description'   => $description,
            'date'          => date('Y-m-d H:i:s')
        ));

        $this->_redirect('/index/cases');
    }

    public function caseAction()
    {
        $caseId = $this->getRequest()->getParam('id');
        if (strlen($caseId) === 0) {
            $this->_redirect('/index/cases');
        }

        $cases = new Cases();
        $case = $cases->getById($caseId);
        $this->view->case = $case;

        $this->view->comments = $case->getComments();
    }

    public function newcommentAction()
    {
        $caseId = $this->getRequest()->getParam('case_id');
        if (strlen($caseId) === 0) {
            $this->_redirect('/index/cases');
        }

        $cases = new Cases();
        $case = $cases->getById($caseId);

        $text = $this->getRequest()->getParam('text');
        if (strlen($text) === 0) {
            $this->_redirect('/index/case/?id=' . $caseId);
        }

        $students = new Students();
        $student = $students->getStudent();

        $cases = new CaseComments();
        $cases->insert(array(
            'student_id'    => $student->id,
            'case_id'       => $case->id,
            'text'          => $text,
            'date'          => date('Y-m-d H:i:s')
        ));

        $this->_redirect('/index/case/?id=' . $caseId);
    }

    public function tipsntricksAction()
    {

    }
}
