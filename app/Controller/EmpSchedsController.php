<?php
class EmpSchedsController extends AppController{
    public $uses = array(
        'Schedule',
        'Shift',
        'Employee',
        'Groups',
        'EmpSched',
        'Rule'
    );
    public function index(){
        $actives = $this->Emp_Sched->fetchEmployeeData('1');
        $a=$this->Emp_Sched->query('SELECT COUNT(sched_id) FROM emp_scheds where sched_id=1');
    }
    function nxtweek(){
        $shiftA='06:00:00';		
        $wks=$this->EmpSched->find('all',array(
        'fields' => array(
        'Employee.id',
        'Employee.first_name',
        'Employee.last_name',
        'Employee.subgroup_id',
        'Schedule.time_in',
        'Schedule.time_out',
        'Schedule.days',
      ),
      'joins' => array(
        array(
          'type' => 'inner',
          'table' => 'schedules',
          'alias' => 'Schedule',
          'conditions' => array(
          'EmpSched.sched_id = Schedule.id'
          )
        ),
         array(
          'type' => 'inner',
          'table' => 'employees',
          'alias' => 'Employee',
          'conditions' => array(
            'EmpSched.emp_id = Employee.id','Employee.subgroup_id=18'
          )
        )
      ),
          'conditions' => array('Employee.id=20')
		));
		
		$this->set(compact('wks'));
    }
}
?>
