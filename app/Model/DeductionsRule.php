<?php
class DeductionsRule extends AppModel{
    function getAll()
    {
            $rule = $this->find('all', array(
			'conditions' => array ('execute' => '1','show' => '1'),
			'order' => 'id DESC')); 
            return ($rule);
    }
 
}
?>
