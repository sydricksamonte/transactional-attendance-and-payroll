<?php
class Retro extends AppModel{
 public $validate = array(
        'Employee' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Required Name'
            )
        ),
        'Retro Pay' => array(
            'required' => array(false,
                'rule' => array('Numeric'),
                'message' => 'Required Amount'
            )
        ),
		'type' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Required Type of Pay'
            )
        ),
		'percent' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Required Percentage or set to it\'s default as 0.'
            )
        ),
		);
}
?>
