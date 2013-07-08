<?php
	class Scheduleoverride extends AppModel{	
        function findSchebyTimeAndEmp($id, $date){
            $sched = $this->find('first',array(
                'conditions' => array('Scheduleoverride.emp_id' => $id,'start_date' => $date )));
            if ($sched == null) 
            {
                return null;					
            }
            else 
            {		
                return $sched['Scheduleoverride']['id'];
            }
        }
        function countCredit($type, $emp_id, $year)
        {   
            $year = substr($year,0,4);
            debug($year);
            $sched = $this->find('count',array(
                'conditions' => array(
                    'emp_id' => $emp_id,
                    'scheduleoverride_type_id' => $type,
                    'year(start_date)' => $year )));
            return $sched;
        }
	}
?>
