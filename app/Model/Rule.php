<?php
class Rule extends AppModel{
			
        function getRule($id,$type){#VACANT
            $rule = $this->find('first', array('conditions' => array('id' => $id)));
                if ($type == '1'){
                    $arrayTemp = explode(",", ($rule['Rule']['rule_or']));
                    return $arrayTemp;}
                else{ return ($rule['Rule']['rule_and']);
                    }
        } 
      
     
        function getNextSched($sched_id, $exSched){
            $rule = $this->find('first', array('fields' => array('Rule.rules'),
                'conditions' => array('NOT' => array('Rule.rules' => $exSched),
                    'AND' => array('Rule.order_schedules' => $sched_id)),
                    'order' =>'rand()'));
                if ($rule['Rule']['rules'] == null)
                { 
                    $ruleTwo = $this->find('first', array('fields' => array('Rule.rules'),
                    'conditions' => array('Rule.order_schedules' => $sched_id),
                    'order' =>'rand()'));
                    return $ruleTwo['Rule']['rules'];	
                }   
                else {return $rule['Rule']['rules'];}
			}
            function fetchDistinctSchedule(){
                $rule = $this->find('all',array(
                    'fields'=>array(
                        'DISTINCT order_schedules',
                        'Schedules.descrip',
                        'Schedules.id',
                    ),
                    'joins'=>array(array(
                        'type' => 'left',
                        'table' => 'schedules',
                        'alias' => 'Schedules',
                        'conditions' => array(
                            'Schedules.order_schedules = Rule.order_schedules'
                        ))
                    ),
                    'order' => array(
                        'Rule.order_schedules' => 'ASC'		
                        )
                    )
                );
                return $rule;
			}
             function fetchRuleSchedule($sched_id,$rule_id){
                $rule = $this->find('all',array(
                    'fields'=>array(
                        'Rule.order_schedules',
                        'Rule.id',
                        'Rule.rules',
                        'Schedules.descrip'
                    ),
                    'joins'=>array(array(
                        'type' => 'left',
                        'table' => 'schedules',
                        'alias' => 'Schedules',
                        'conditions' => array(
                            'Schedules.order_schedules = Rule.order_schedules'
                        ))
                    ),
                    'conditions' => array(
                        'Rule.rules' => $sched_id,
                        'Rule.order_schedules' => $rule_id
                    ),
                    'order' => array(
                        'Rule.order_schedules' => 'ASC'		
                        )
                    )
                );
                if ($rule == NULL)
                    {return FALSE;}
                else{return TRUE;}
			}
            function getRulesOfOrderSched($id){
                $rule = $this->find('all',array(
                    'fields'=>array(
                        'Rule.order_schedules',
                        'Rule.rules',
                        'Rule.id',
                        'Schedules.descrip'
                    ),
                    'joins'=>array(array(
                        'type' => 'left',
                        'table' => 'schedules',
                        'alias' => 'Schedules',
                        'conditions' => array(
                            'Schedules.order_schedules = Rule.order_schedules'
                        ))
                    ),
                    'conditions' => array(
                        'Rule.order_schedules' => $id
                    ),
                    'order' => array(
                        'Rule.order_schedules' => 'ASC'		
                        )
                    )
                );
                return $rule;
			}
             function findId($rules, $osched){
                            $rule = $this->find('first',array(
                    'conditions' => array(
                        'rules' => $rules,
                        'order_schedules' => $osched
                    ),
                ));   
                return $rule['Rule']['id'];
			}
}
?>
