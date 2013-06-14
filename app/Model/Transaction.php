<?php
class Transaction extends AppModel{
    function getAll(){ 
        $trans = $this->find('all', array(
			'conditions' => array ('status' => '1'),
			'order' => 'id ASC')); 
            return ($trans);
    }
    function getFetchRule($type)
    {
            $rule = $this->find('all', array(
			'conditions' => array ('status' => '1', 'name LIKE' => $type.'%'),
			'order' => 'statement_sort DESC')); 
            return ($rule);
        
    }
}
?>
