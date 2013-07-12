<?php
    class ScheduleoverridesController extends AppController{
    public $helpers = array('Html', 'Form');
        var $name = 'Scheduleoverrides';
        public $uses=array('Schedule','Scheduleoverride','Employee');
		
        public function change_sched(){
            $empname = $this->Employee->find('list',array(
                'fields'=>array('Employee.first_name')));
                $this->set(compact('empname'));
		} 
        public function getOverride($date, $emp_id)
        {
            $date = date('Y-m-d', $date);
            $employees = $this->Scheduleoverride->find('first',array(
                'fields' => array(
                    'time_in',
                    'time_out',
                    'scheduleoverride_type_id'
                ),
                'conditions' => array(
                    'start_date' => $date,
                    'emp_id' => $emp_id
                ),
                'order' => array(
                    'change_time' => 'DESC',
                    )
                )
            );
            return $employees;
        }		
	}
?>
