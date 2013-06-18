<?php
class CompAdvanceRule extends AppModel{
    function getAll(){ 
        $trans = $this->find('all', array(
			'conditions' => array ('show' => '1'),
			'order' => 'id ASC')); 
            return ($trans);
    }
    function getFetchRule($type)
    {
            $rule = $this->find('all', array(
			'conditions' => array ('execute' => '1', 'name LIKE' => $type.'%'),
			'order' => 'statement_sort DESC')); 
            return ($rule);
        
    }
    function getComputations($type)
    {
            $rule = $this->find('all', array(
			'conditions' => array ('execute' => '1','show' => '1', 'name LIKE' => $type.'%'),
			'order' => 'statement_sort DESC')); 
            return ($rule);
        
    }
    function getTaggingRule()
    {
            $rule = $this->find('all', array(
			'conditions' => array ('execute' => '1','show' => '1' ),
			'order' => 'id DESC')); 
            return ($rule);
        
    }
    function getDesc($type)
    {
            $rule = $this->find('all', array(
            'fields' => 'desc','computation_rule','var_total',
			'conditions' => array ('execute' => '1','show' => '1', 'name LIKE' => $type.'%' ),
			'order' => 'id DESC')); 
            return ($rule);
        
    }
 
}
?>
