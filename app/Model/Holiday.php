<?php
class Holiday extends AppModel{
    var $name = 'Holidays';
    var $validate = array('name' => array('rule' => 'notEmpty'));

    function getHoliday($date)
    {
        $holidays = $this->find('first',array(
            'fields' => array('regular'),
            'conditions' => array('authorize' => '1','date' => $date)));
            if ($holidays != null)
            {
                $type = $holidays['Holiday']['regular'];

                if ($type == 0)
                {
                    return 'SH';
                }
                elseif  ($type == 1)
                {
                    return 'RH';
                }
            }
            else
            {
                return null;
            }
    }
}
?>
