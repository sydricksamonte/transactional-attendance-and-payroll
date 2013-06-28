<?php
class DayShift extends AppModel{
    function groupShifts(){
        $shifts = $this->find('list', array(
                'fields' => 'shift',
                'order' => 'id ASC'));
               
        return $shifts;
       /* $shifts = array(
            'Regular Shift (Non Network Engineer shift)',
            'Early Morning (Network Engineer shift)',
            'Morning (Network Engineer shift)',
            'Midday (Network Engineer shift)',
            'Afternoon (Network Engineer shift)',
            'Evening (Network Engineer shift)');
        return $shifts;*/
    } 
      function getShifting($id){
        $shifts = $this->find('all', array(
                'conditions' => array('group' => $id),
                'order' => 'id ASC'));
               
        return $shifts;
    } 
}
?>
