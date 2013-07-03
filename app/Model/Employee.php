<?php
class Employee extends AppModel{
    function encryptValue($toEncrypt) {
        $secret = Security::cipher($toEncrypt, 'my_key');
        return $secret;
    }
    
    function findExistingSchedule($date,$id)
    {
        $cond = $this->find('first',array(
            'joins' => array( array(
                'type' => 'left',
                'table' => 'schedules',
                'alias' => 'Schedules',
                'conditions' => array(
                    'Employee.id = Schedules.emp_id'
                )
            )),
            'conditions' => array(
                'and' => array(
                    array('Schedules.start_date <= ' => $date,
                        'Schedules.end_date >= ' => $date
                    ),
                'Schedules.id' => $id
            ))));
        echo $conditions;
        return ($cond);
    }
      
    function findGroupId($id)
    {
        $groupId = $this->find('first', array('conditions' => array('id' => $id)));
        return ($groupId['Employee']['group_id']);
    }
    
    function findSchedId($id)
    {
        $groupId = $this->find('first', array('conditions' => array('id' => $id)));
        return ($groupId['Employee']['sched_id']);
    }
    
    function getYearCutOff()
    {
#get year for cut off
        $dateEt ='2009';
        $dateSt = date('Y');
        $i =0;
        while (($dateSt) >= ($dateEt)) {
            $exp = $exp. "$dateSt". '_';
            $dateSt = $dateSt-1;
        }
        $exp =substr_replace($exp, '', -1);
        $yearS  = explode('_',$exp);
        return $yearS;
    }
    
    function getSDayCutOffs()
    {
        $days = array('26','11');
        return $days;
    }
    function getEDayCutOffs()
    {
        $days = array('10','25');
        return $days;
    }
    function countSchedOfPrevWeek($groupId){
        $netEngCount = $this->find('count',array(
            'fields' => array(
                'COUNT(DISTINCT EmpSched.emp_id) as count',
                ),
            'joins' => array(
                array(
                    'type' => 'inner',
                    'table' => 'emp_scheds',
                    'alias' => 'EmpSched',
                    'conditions' => array(
                        'EmpSched.emp_id = Employee.id'
                    )
            )),
            'conditions' => array(
                array( 'Employee.subgroup_id' => $groupId)
            )
        ));
        return $netEngCount;
    }
    function getAllEmployeeBySubGroup($id)
    {
        $emp = $this->find('list',array('fields' => array('Employee.id'),
        'conditions' => array(array( 'Employee.subgroup_id' => $id))
        ));
        return $emp;
    }
    
    function getAllEmployeeInfoBySubGroup($id)
    {
        $emp = $this->find('all',array('conditions' => array( array('Employee.subgroup_id' => $id)),
            'order' => array('employed DESC', 'Employee.last_name', 'Employee.first_name')
        ));
        return $emp;
    }

    function getEmployeeSchedulePrf($id,$sched_id){
        $employee = $this->find('first',array(
            'fields' => array(
                'Employee.id',
                'Employee.first_name',
                'Employee.last_name',
                'Employee.userinfo_id',
                'EmpSched.sched_id',
                'EmpSched.emp_id',
                'EmpSched.week_id'
            ),
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'emp_scheds',
                    'alias' => 'EmpSched',
                    'conditions' => array(
                        'Employee.id = EmpSched.emp_id'
                    )
                ),
                array(
                    'type' => 'left',
                    'table' => 'schedules',
                    'alias' => 'Schedule',
                    'conditions' => array(
                        'EmpSched.sched_id = Schedule.order_schedules'
                    )
                )
            ),
            'conditions' => array(
                'Employee.id' => $id,
                'Schedule.order_schedules' => $sched_id
            )
        ));
    return $employee;
    }
    public function findEmployeeName($id)
    {
        $employee = $this->find('first',array(
            'fields' => array('first_name', 'last_name'),
            'conditions' => array('id' => $id)
        ));
        return $employee['Employee']['last_name'] . ', '.$employee['Employee']['first_name'];
    }
    public function getEmployeeNoLog($id)
    {
        $employee = $this->find('count',array(
            'conditions' => array('subgroup_id' => '15', 'id' => $id )
        ));
        return $employee;
    }
    public function getMissingEmployeeOnSpecWeek($id, $netType)
    {
         $employee = $this->find('all',array(
            'fields' => array('DISTINCT id', 'first_name', 'last_name'),
            'conditions' => array('id NOT IN' => $id , 'subgroup_id' => $netType, 'employed' => '1' ),
            'order' => array('last_name' => 'ASC','first_name' => 'ASC')
        ));
        return $employee;
    }
    public function getHistor($id)
    {
            $histor = $this->find('all',array(
                'fields' => array(
                  'Employee.id',
                  'EmpSched.id',
                  'EmpSched.week_id',
                  'Week.id',
                  'Week.start_date',
                  'Week.end_date',
                  'Schedule.rd',
                  'Schedule.time_in',
                  'Schedule.time_out',
                ),
                'joins' => array(
                    array(
                    'type' => 'left',
                    'table' => 'emp_scheds',
                    'alias' => 'EmpSched',
                        'conditions' => array(
                         'Employee.id = EmpSched.emp_id'
                      )
                     ),
                 array(
                    'type' => 'left',
                    'table' => 'weeks',
                    'alias' => 'Week',
                        'conditions' => array(
                            'EmpSched.week_id = Week.id'
                        )
                    ),
                array(
                    'type' => 'left',
                    'table' => 'schedules',
                    'alias' => 'Schedule',
                    'conditions' => array(
                        'EmpSched.sched_id = Schedule.order_schedules'
                        )
                    ),
                ),
                'conditions' => array(
                    'Employee.id' => $id
                )
            ));
            return $histor;
    }
    public function findGp($id)
    {
            $gp = $this->find('first',array(
                                    'fields' => array(
                                            'Employee.id',
                                            'Employee.first_name',
                                            'Employee.last_name',
									    	'Employee.monthly',                                          
                                            'Employee.subgroup_id',
                                            'Group.id',
                                            'Group.name',
                                            ),
                                    'joins' => array(
                                            array(
                                                'type' => 'left',
                                                'table' => 'sub_groups',
                                                'alias' => 'SubGroup',
                                                'conditions' => array(
                                                    'Employee.subgroup_id = SubGroup.id'
                                                    )),
                                            array(
                                                'type' => 'left',
                                                'table' => 'groups',
                                                'alias' => 'Group',
                                                'conditions' => array(
                                                    'SubGroup.group_id = Group.id'
                                                ))
                                            ),
                                    'conditions' => array(
                                             'Employee.id' => $id
                                            )
                                ));
                                return $gp;
    }
    public function getStartIn($id)
    {
              $startin = $this->find('first',array(
                'fields' => array(
                    'Schedule.time_in',
                    'Schedule.time_out',
                    'Schedule.id',
                    'EmpSched.week_id',
                    'EmpSched.sched_id',
                    'Week.week_no',
                    'Week.start_date',
                    'Week.end_date'
                ),
                'joins' => array(
                    array(
                        'type' => 'left',
                        'table' => 'groups',
                        'alias' => 'Group',
                        'conditions' => array(
                            'Employee.subgroup_id = Group.id'
                            )
                        ),
                    array(
                        'type' => 'left',
                        'table' => 'emp_scheds',
                        'alias' => 'EmpSched',
                    'conditions' => array(
                        'Employee.id = EmpSched.emp_id'
                    )
                ),
            array(
                'type' => 'left',
                'table' => 'weeks',
                'alias' => 'Week',
                'conditions' => array(
                'Week.week_no=EmpSched.week_id'
                )
            ),
            array(
                'type' => 'left',
                'table' => 'schedules',
                'alias' => 'Schedule',
                'conditions' => array(
                    'EmpSched.sched_id=Schedule.order_schedules'
                    )
                )
            ),
            'conditions' => array(
                'Employee.id' => $id
                ),
            'order' => array(
                'EmpSched.week_id DESC'
                )
            ));
    }
    public function getEmployee($id)
    {
            $employee = $this->find('first',array(
	            'fields' => array(
                  'Employee.id',
                    'Employee.userinfo_id',
                    'Employee.monthly',
                    'Employee.tax_status',																						
                    'Employee.first_name',
                    'Employee.last_name',
                    'Employee.employed',
                    'SubGroup.name',
                    'Schedule.time_in',
                    'Schedule.time_out',
                    'Schedule.id',
                    'EmpSched.week_id',
                    'EmpSched.sched_id',
                    'Week.week_no',
                    'Week.start_date',
                    'Week.end_date'
                ),
                'joins' => array(
                    array(
                        'type' => 'left',
                        'table' => 'sub_groups',
                        'alias' => 'SubGroup',
                        'conditions' => array(
                        'Employee.subgroup_id =SubGroup.id'
                        )
                    ),
                    array(
                        'type' => 'left',
                        'table' => 'emp_scheds',
                        'alias' => 'EmpSched',
                        'conditions' => array(
                        'Employee.id = EmpSched.emp_id'
                        )
                    ),
                    array(
                        'type' => 'left',
                        'table' => 'weeks',
                        'alias' => 'Week',
                            'conditions' => array(
                                'Week.week_no=EmpSched.week_id'
                                )
                    ),
                    array(
                        'type' => 'left',
                        'table' => 'schedules',
                        'alias' => 'Schedule',
                        'conditions' => array(
                        'EmpSched.sched_id=Schedule.order_schedules'
                        )
                    )
                ),
                'conditions' => array(
                    'Employee.id' => $id
                ),
                'order' => array(
                    'EmpSched.week_id DESC'
                )
            ));
              
            return $employee;
    }
     public function getEmployeeAndSchedOnSpecWeek($week)
    {
            $employee = $this->find('all',array(
	            'fields' => array(
                  'Employee.id',																					
                    'Employee.first_name',
                    'Employee.last_name',
                    'Employee.userinfo_id',
                    'Schedule.time_in',
                    'Schedule.days',
                    'Schedule.time_out',
                    'Schedule.id',
                    'EmpSched.week_id',
                ),
                'joins' => array(
                    array(
                        'type' => 'inner',
                        'table' => 'emp_scheds',
                        'alias' => 'EmpSched',
                        'conditions' => array(
                        'Employee.id = EmpSched.emp_id'
                        )
                    ),
                     array(
                        'type' => 'inner',
                        'table' => 'weeks',
                        'alias' => 'W',
                        'conditions' => array(
                        'EmpSched.week_id = W.id'
                        )
                    ),
                    array(
                        'type' => 'inner',
                        'table' => 'schedules',
                        'alias' => 'Schedule',
                        'conditions' => array(
                        'EmpSched.sched_id=Schedule.order_schedules'
                        )
                    )
                ),
                'conditions' => array(
                    'W.week_no' => $week,
                    'Employee.employed' => '1'
                ),
                'order' => array(
                    'Employee.subgroup_id ASC',
                    'Employee.last_name ASC',
                    'Employee.first_name ASC',
                )
            ));
              
            return $employee;
    }
    public $validate = array(
                      'first_name' => array(
                              'alphaNumeric' => array(
                                        'rule'     => '/^[^%#\/*@!1234567890.]+$/',
                                        'required' => true,
                                        'message'  => 'Invalid first name'
                                      ),
                                'between' => array(
                                        'rule'    => array('between', 2, 15),
                                        'message' => 'Between 2 to 15 characters'
                                        )
                                ),
                        'userinfo_id' => array(
                                'alphaNumeric' => array(
                                        'rule'     => '/^[^%#\/*@!,qwertyuiopasdfghjklzxcvbnm<>.]+$/',
                                        'message'  => 'Invalid id'
                                      )
                                ),
                        'last_name' => array(
                                'alphaNumeric' => array(
                                        'rule'     => '/^[^%#\/*@!1234567890.]+$/',
                                        'required' => true,
                                        'message'  => 'Invalid last name'
                                        ),
                                'between' => array(
                                        'rule'    => array('between', 2, 15),
                                        'message' => 'Between 2 to 15 characters'
                                        )

                                ));

}
?>


