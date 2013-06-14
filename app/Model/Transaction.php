<?php
class Transaction extends AppModel{
    function getAll(){ 
        $trans = $this->find('all', array(
			'conditions' => array ('status' => '1'),
			'order' => 'name ASC')); 
            return ($trans);
    }
}
?>
