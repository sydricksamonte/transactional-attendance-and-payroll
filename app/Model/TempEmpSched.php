<?php
class TempEmpSched extends AppModel{
    function fetchTempEmp()
    {
        $tempString = null;
        $tempFields = $this->find('all',array(
            'fields' => array('emp_id')));
        foreach ($tempFields as $tf):
        {
            $tempString = $tempString . $tf['TempEmpSched']['emp_id']. '_';
        }
        endforeach;
        $tempString =	substr_replace($tempString ,"",-1);
        $arrayTemp = explode("_", $tempString);
        return ($arrayTemp);
    }
    function fetchTempSched()
    {
        $tempString = null;
        $tempFields = $this->find('all',array(
            'fields' => array(
                'sched_id')));
        foreach ($tempFields as $tf):
        {
            $tempString = $tempString . $tf['TempEmpSched']['sched_id']. '_';
        }
        endforeach;
        $tempString = substr_replace($tempString ,"",-1);
        $arrayTemp = explode("_", $tempString);
        return ($arrayTemp);
    }
    function fetchTempField()
    {
        $tempFields = $this->find('all');
        return ($tempFields);
    }
    function getGroupCount($id)
    {
       $employee = $this->find('count',array(
            'joins' => array(
                array(
                'type' => 'inner',
                'table' => 'schedules',
                'alias' => 'S',
                'conditions' => array(
                    'TempEmpSched.sched_id = S.order_schedules' 
                )
            )),
            'conditions' => array(
                'S.group' => $id
            ),
        ));
        return ($employee);
    }
    
}
?>

