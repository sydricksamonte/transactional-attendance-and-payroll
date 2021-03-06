<?php
class EmployeesController extends AppController{
	public $components=array('Session','RequestHandler');
	
	var $sTime;
 	var $eTime;
	
	public $uses = array(
		'Employee',
		'History',
		'Shift',
		'Groups',
		'Schedule',
		'Scheduleoverride',
		'Scheduleoverride_type',
		'Historytype',
        'CompAdvanceRule',
		'User',
		'Checkinout',
		'Holiday',
		'Restday',
		'EmpSched',
		'SubGroup',
		'Week',
		'Govdeduction',
		'Govstat',
		'Total',
		'Cutoff',
		'Retro',
		'Loan',
        'DeductionsRule'
	);
	
	public $helpers = array('Html','Form');


	public function test(){
			$all = $this->Employee->find('all');
			$this->set(compact('all'));
    }

    public function upload(){
        
        
		$file = $this->data['Upload']['file'];
        if ($file != NULL)
        {
            if ($file['type'] == "application/msaccess")
            {
                $dbName = $_SERVER["DOCUMENT_ROOT"] ."/taps/". $file['name'];
                $uploading = move_uploaded_file($file['tmp_name'], $dbName);
                if($uploading){
                    $this->Session->setFlash('Logsheet has been updated','success');
                }else{
                    $this->Session->setFlash('Failed to upload','failed');
					
                }
            }
            else
            {
                $this->Session->setFlash('Invalid file','failed');
            }
        }
	}
    public function filter()
    {
        $ressult = $this->data;
        $this->set(compact('ressult'));
        $res = $this->Employee->getFilter($this->data['Employee']['emp_id']);
        $this->set(compact('res'));
    }
    
    public function index()
    {
        $yeard = date("Y", time());
        $cutoffexist=$this->Cutoff->find('first',array('conditions'=>$yeard.'01-25'));
        $this->set(compact('cutoffexist'));

        $wks=$this->Week->find('all',
            array('order'=>'Week.end_date ASC'));
        $this->set(compact('wks'));

        $employees = $this->Employee->find('all',array(
            'fields' => array(
                'Employee.id',
                'Employee.first_name',
                'Employee.last_name',
                'Employee.employed',
                'SubGroup.name',
            ),
            'joins' => array(
                array(
                'type' => 'left',
                'table' => 'sub_groups',
                'alias' => 'SubGroup',
                'conditions' => array(
                'Employee.subgroup_id = SubGroup.id'
                )
            ),
        ),
        'order' => array(
            'Employee.employed' => 'DESC',
            'SubGroup.name' => 'ASC',																					
            'Employee.last_name' => 'ASC'
            )
        ));

        $this->set(compact('employees'));
    }

    function delete($id){
        if($this->Employee->delete($id)){
            $this->Session->setFlash('Employee has been deleted.','success');
            $this->redirect(array('action' => 'index'));
        }
    }

    public function change_sched($id=null)
    {
        $this->Employee->id = $id;
        $weekStart =  $this->Week->getAllStart();
        $weekEnd =  $this->Week->getAllEnd();
        $this->set(compact('weekStart'));
        $this->set(compact('weekEnd'));
        $weekNum =  $this->Week->getWeekNo();
        $this->set(compact('weekNum'));
        $shifts = $this->Schedule->getShifts();
        $this->set(compact('shifts'));

            $employee = $this->Employee->find('first',array(
            'fields' => array(
                'Employee.id',
                'Employee.userinfo_id',
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
                                                            'Employee.subgroup_id = SubGroup.id'
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
                                                    ));
            $this->set(compact('employee'));

            $stDa = $this->data['Employee']['start'];
            $enDa = $this->data['Employee']['end'];
            $sched = $this->data['Employee']['scheds'];
            $schedOrder = $this->Schedule->getShiftOrder($sched);
            
            if (empty($this->data)) 
            {
                $this->data = $this->EmpSched->read();
            } 
            else 
            {
                {
                    $stDa2 = $this->Week->getWeekOrder($stDa);
                    $enDa2 = $this->Week->getWeekOrder($enDa);
                    $filWeeks =   $this->Week->getWeeksInBetween($stDa2, $enDa2);
                    foreach ($filWeeks as $fw):{
                        $this->request->data['EmpSched']['id'] = null;
                        $existingId = $this->EmpSched->checkExist($fw, $id);
            
                        $this->request->data['EmpSched']['emp_id'] = $id;
                        $this->request->data['EmpSched']['sched_id'] = $schedOrder;
                        $this->request->data['EmpSched']['week_id'] = $fw;
                        $this->set(compact('existingId'));																			 
                        if ($existingId == null)
                        {
                            $this->EmpSched->save($this->request->data);
                            $this->Session->setFlash('Schedule has been successfully added','success');
                        }
                        else
                        {
                            $this->request->data['EmpSched']['id'] = $existingId['EmpSched']['id'];
                            $this->EmpSched->save($this->request->data);
                            $this->Session->setFlash('Schedule has been updated','success');
                        }
                    }
                    endforeach;
                    $this->redirect(array('action' => 'view_emp', $id));
                }
            }
		}

		public function edit_selected_sched($id=null, $schedId)
        {
            $this->Employee->id = $id;
            $this->Schedule->id = $schedId;
            $shifts = $this->Shift->find('list',array(
                'fields' => array('Shift.time_shift'),
                'conditions' => array('Shift.authorize')
            ));

            $this->set(compact('shifts'));
            $actions = $this->Historytype->find('list',array(
                'fields' => array('Historytype.name')
            ));

            $this->set(compact('actions'));
            $user = $this->User->find('list',array(
                'fields' => array('User.id')
            ));

            $this->set(compact('user'));
            $employee = $this->Employee->find('first',array(
                'fields' => array(
                    'Employee.id',
                    'Employee.first_name',
                    'Employee.last_name',
                    'Group.name',
                    'Schedules.id',
                    'Shifts.start_time',
                    'Shifts.end_time',
                    'Schedules.start_date',
                    'Schedules.end_date',
                    'Schedules.changed_time',
                    'Shifts.time_shift',
                    'Schedules.shift_id',
                    'Schedules.action_taken'
                ),
                'joins' => array(
                    array(
                    'type' => 'left',
                    'table' => 'groups',
                    'alias' => 'Group',
                    'conditions' => array(
                        'Employee.group_id = Group.id'
                        )
                    ),
                    array(
                    'type' => 'left',
                    'table' => 'schedules',
                    'alias' => 'Schedules',
                    'conditions' => array(
                        'Employee.id = Schedules.id'
                        )
                    ),
                    array(
                    'type' => 'left',
					'table' => 'shifts',
					'alias' => 'Shifts',
					'conditions' => array(
					    'Schedules.shift_id = Shifts.id'
					    )
					),
					array(
					'type' => 'left',
					'table' => 'historytypes',
					'alias' => 'HistoryType',
					'conditions' => array(
						'HistoryType.id= Schedules.action_taken'
						)
					)
				),
				'conditions' => array(
                    'Employee.id' => $id,
                    'Schedules.id' => $schedId
                    )
                )
            );

            $this->set(compact('employee'));
            $reqSDate;
            $reqEDate;

            $reqSDate = $this->data['Employee']['start_date']['year'].'-'.$this->data['Employee']['start_date']['month'].'-'.$this->data['Employee']['start_date']['day'];
            $reqEDate = $this->data['Employee']['end_date']['year'].'-'.$this->data['Employee']['end_date']['month'].'-'.$this->data['Employee']['end_date']['day'];

            $hisType = $this->Schedule->findActionTaken($id);
            $condStartDate = $this->Schedule->findExSched($reqSDate,$id,$schedId);
            $condEndDate = $this->Schedule->findExSched($reqEDate,$id,$schedId);

            if (($condStartDate == true)&&($this->data['action_taken'] == $hisType))
            {
                $this->Session->setFlash('Target start date exists on this employees schedule.','failed');
            } 
            else if (($condEndDate == true)&&($this->data['action_taken'] == $hisType))
            {
                $this->Session->setFlash('Target end date exists on this employees schedule.','failed');
            }
            else if ($reqSDate > $reqEDate)
            {
                $this->Session->setFlash('Invalid date range','failed');
            } 
            else 
            {
                if (empty($this->data)) 
                {
                    $this->data = $this->Schedule->read();
                    $this->data = $this->Shift->read();
                    $this->data = $this->Scheduleoverride->read();
                } 
                else 
                {
                    if ($this->data['action_taken'] == '1') 
                    {
                        $this->request->data['history_type_id'] = '1';
                    } 
                    else  
                    {
                        $this->request->data['history_type_id'] = '2';
                    }
                    if ($this->Schedule->save($this->data)){
                        $this->request->data['start_date']  = $reqSDate;
                        $this->request->data['end_date']  = $reqEDate;
                        $this->Schedule->save($this->request->data);
						$this->request->data['sched_id']  = $schedId;
						$this->request->data['id'] = null;
						$this->History->save($this->request->data);
						$this->Session->setFlash('The schedule has been updated.','success');
						$this->redirect(array('action' => 'view_emp',$id));
					}
				}
		    }
		}
        public function getLogOutAccess($dateLO, $bId)
        {
            $curr_date_ymd = date('Y-m-d', $dateLO);
            $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));

            
                $couts = $this->Checkinout->find('first',array(
	                'fields' =>
					    'Checkinout.CHECKTIME',
				    'conditions' => array(
	                    'Checkinout.USERID' => $bId,
		                'Checkinout.CHECKTYPE' => 'O',
                        'Checkinout.CHECKTIME LIKE' => $curr_date_ymd.'%'
				        ),
				    'order' => array(
					    'Checkinout.CHECKTIME DESC',
					    ),
			    ));
              
                if ($couts != null)
                {
                    return $couts['Checkinout']['CHECKTIME'];
                }
                else
                {
                      $dbName = $this->Checkinout->findAccess();
                        if (!file_exists($dbName)) {
                        die("Could not find database file.");
                        }
                        $db = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=$dbName", NULL, NULL);     
                        $sql  = "SELECT TOP 1 CHECKTIME FROM CHECKINOUT WHERE  CHECKTYPE = 'O' AND USERID =".$bId." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME DESC ";
                        $result = odbc_exec($db,$sql);
                        $lOut = odbc_result($result,1);
                        if ($lOut != null)
                        {
                            return($lOut.'');
                        }
                        else
                        {   
                            return NULL;
                        }
            }
        }
        public function getLogInAccess($dateLO, $bId)
        {
            $curr_date_ymd = date('Y-m-d', $dateLO);
            $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));
      
                 $cins = $this->Checkinout->find('first',array(
	                'fields' =>
					    'Checkinout.CHECKTIME',
				    'conditions' => array(
	                    'Checkinout.USERID' => $bId,
		                'Checkinout.CHECKTYPE' => 'I',
                        'Checkinout.CHECKTIME LIKE' => $curr_date_ymd.'%'
				        ),
				    'order' => array(
					    'Checkinout.CHECKTIME DESC',
					    ),
			    ));
                if ($cins != null)
                {
                    return $cins['Checkinout']['CHECKTIME'];
                }
                else
                {
                        $dbName = $this->Checkinout->findAccess();
                        if (!file_exists($dbName)) {
                        die("Could not find database file.");
                        }
                        $db = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=$dbName", NULL, NULL);     
                        $sql  = "SELECT TOP 1 CHECKTIME FROM CHECKINOUT WHERE  CHECKTYPE = 'I' AND USERID =".$bId." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME ASC ";
                        $result = odbc_exec($db,$sql);
                        $lIn = odbc_result($result,1);
                        if ($lIn != null)
                        {
                            return($lIn.'');
                        }
                        else
                        {
                            return NULL;
                        }
                }
              
        }
        public function getFetchRules($type)
        {
            $fetch_rule = $this->CompAdvanceRule->getFetchRule($type);
            return $fetch_rule;
        }
        public function getComputations($type)
        {
            $fetch_rule = $this->CompAdvanceRule->getComputations($type);
            return $fetch_rule;
        }
        public function getTaggingRules()
        {
            $tag_rule = $this->CompAdvanceRule->getTaggingRule();
            return $tag_rule;
        }
        public function getDesc($type)
        {
            $desc = $this->CompAdvanceRule->getDesc($type);
            return $desc;
        }
        public function fetchDeductions()
        {
            $desc = $this->DeductionsRule->getAll();
            return $desc;
        }
        public function getInitValues()
        {
            $desc = $this->CompAdvanceRule->getInitValues();
            return $desc;
        }
        public function manpower($day)
        {
            $desc = $this->CompAdvanceRule->getInitValues();
            return $desc;
        }
        public function findDayOnWeek($date2, $emp_id){
            $weeks = $this->Week->find('first', array('fields' => array ('week_no','year'),'conditions' => array('OR' => array('monday' => $date2,'tuesday' => $date2,'wednesday' => $date2,'thursday' => $date2,'friday' => $date2,'saturday' => $date2,'sunday' => $date2)), 'order' => array ('week_no ASC')));
            $week = ($weeks['Week']['week_no']);
            $year = ($weeks['Week']['year']);
            $empScheds = $this->EmpSched->fecthEmployeeAndSchedOnSpecWeek($week, $emp_id, $year);
            return $empScheds;
        }
        public function select_manpower(){
            if ($this->data != null)
            {  
                $date = $this->data['CutOff']['cut_use'];
                $date2 = $date['year'].'-'.$date['month'].'-'.$date['day'];
                $date2 = strtotime($date2);
                $week_no = $this->Week->findDayOnWeek($date); #Model 
                $this->redirect(array('controller' => 'Employees', 'action' => 'select_manpower_day',$week_no,$date2));
            }
	    }
        public function select_manpower_day($week, $day){
            $empScheds = $this->Employee->getEmployeeAndSchedOnSpecWeek($week);
            $dw = date( "w", $day);

            $this->set(compact('empScheds'));
            $this->set(compact('day'));
            $this->set(compact('dw'));     
		}
        
        public function select_manpower_range()
        {
            if ($this->data != null)
            {  
                $date = $this->data['CutOff']['cut_use'];
                $date2 = $date['year'].'-'.$date['month'].'-'.$date['day'];
                $dateE = $this->data['CutOff']['cut_use_end'];
                $dateE2 = $dateE['year'].'-'.$dateE['month'].'-'.$dateE['day'];
                $date2 = strtotime($date2);
                $dateE2 = strtotime($dateE2);
                $week_no = $this->Week->findDayOnWeek($date); #Model 
                $this->redirect(array('controller' => 'Employees', 'action' => 'select_manpower_range_day', $date2, $dateE2));
            }
	    }
        public function select_manpower_range_day($day_s, $day_e){
            set_time_limit(100);
            $this->layout='tablescroll';
            $this->set(compact('day_s'));
            $this->set(compact('day_e'));
            $range = array();
            $i = 0;
            do {
                $range[$i] = ($day_s);
                $day_s = strtotime('+1 day',$day_s); 
                $i++;
            } while ($day_s <= $day_e); 

            $this->set(compact('range'));
            
            $emp = $this->Employee->getEmployedStatusEmployees();
            $this->set(compact('emp'));    
		}
        public function findDaySched($date, $emp_id)
        {
            $week_no = $this->Week->findDayOnWeek2(date('Y-m-d',$date));
            $empScheds = $this->Employee->getSpecEmployeeAndSchedOnSpecWeek($week_no, $emp_id,date('Y',$date));
            return $empScheds;
        }
		public function view_emp($id=null)
        {
            
            $trans = $this->CompAdvanceRule->getAll();
            $this->set(compact('trans'));
            $sdate = date("Y-m-d", time());
		    $total = $this->Cutoff->getCutOffAvailable2($sdate);
		    $this->set(compact('total'));
		    $cutDropDown = $this->Cutoff->getCutOffRecent($sdate);
			    if ($this->data['Emp']['cut_off'] == null)
			    {
			        $dayCutOff = date('d');
                    if (($dayCutOff >= '11') && ($dayCutOff <= '25'))
                    { 
                  	    $sdates =  date('Y').'-'.date('m').'-11';
                        $edates =  date('Y').'-'.date('m').'-25';
					   
                    }
                 
                    else 
                    {
                                 $sdates =  date('Y').'-'.date('m', strtotime("-1 month")).'-26';
                        $edates =  date('Y').'-'.date('m').'-10';
				
                    }	
                }
				else
				{
					$cutDropDown = $this->data['Emp']['cut_off']; 
					$sdates = $this->Cutoff->getCutOffPeriodStart($this->data['Emp']['cut_off']);
					$edates = $this->Cutoff->getCutOffPeriodEnd($this->data['Emp']['cut_off']);
				}
						
            $this->set(compact('cutDropDown'));
            $retroPay=$this->Retro->find('all',array('conditions'=>array('Retro.emp_id'=>$id, 'Retro.cutoff_id'=>$cutDropDown),'order'=>'Retro.id DESC'));
			$this->set(compact('retroPay'));
            $this->set(compact('sdates'));
            $this->set(compact('edates'));
            $weekStart =  $this->Week->getAllStart();
            $weekEnd =  $this->Week->getAllEnd();
            $weekNum =  $this->Week->getWeekNo();
            $this->set(compact('weekStart'));
            $this->set(compact('weekEnd'));
            $this->set(compact('weekNum'));

            $cutoffLoan=$this->Cutoff->find('first',array('conditions'=>array('Cutoff.id'=>$cutDropDown)));
            $this->set(compact('cutoffLoan'));
            $histor = $this->Employee->getHistor($id);
            $this->set(compact('histor'));
            
            $sl = $this->Scheduleoverride->countCredit(4, $id, $sdates);
            $vl = $this->Scheduleoverride->countCredit(3, $id, $sdates);
            $this->set(compact('sl'));
            $this->set(compact('vl'));
            $yearL = substr($sdates,0,4);
            $this->set(compact('yearL'));

            $empLoans = $this->Loan->find('all',array(
                'joins'=>array(
                    array(
                    'type' => 'left',
                    'table' => 'cutoffs',
                    'alias' => 'Cutoff',
                    'conditions' => array(
                    'Cutoff.id'=> $cutDropDown
                     )
                    ),
                ),
                'conditions'=>array(
                    'Loan.emp_id'=>$id,
                        '((Loan.start_date >= Cutoff.start_date and Loan.start_date <= Cutoff.end_date) or ((Loan.end_date > Cutoff.end_date) or (Loan.end_date >= Cutoff.end_date))) and (Loan.start_date < Cutoff.end_date)'
                )));

            $this->set(compact('empLoans'));
            $no_log = $this->Employee->getEmployeeNoLog($id);
            $this->set(compact('no_log'));

            $employee = $this->Employee->getEmployee($id);
            $this->set(compact('employee'));
            $schedId = $this->Week->findScheduleId($sdate,$id);
            $this->set(compact('schedId'));
            $startin = $this->Employee->getStartIn($id);
            $this->set(compact('startin'));
     
            $holidays = $this->Holiday->find('all',array(
            'fields' => array(
            'Holiday.date', 'Holiday.regular'),
            'conditions' => array('Holiday.authorize' => '1')
        ));
        $this->set(compact('holidays'));
        $excemptions = $this->Scheduleoverride->find('all',array(
            'fields' => array(
                'Scheduleoverride.start_date',
                'Scheduleoverride.end_date',
                'Scheduleoverride.time_in',
                'Scheduleoverride.time_out',
                'Scheduleoverride_type.name',
                ),
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'scheduleoverride_types',
                    'alias' => 'Scheduleoverride_type',
                    'conditions' => array(
                    'Scheduleoverride.scheduleoverride_type_id = Scheduleoverride_type.id',
                    )
                ),
            ),
            'conditions' => array(
                'Scheduleoverride.emp_id' => $employee['Employee']['id'],
            ),
        ));
        $this->set(compact('excemptions'));
        $shiftOrder = $this->EmpSched->find('all',array(
            'fields'=>array(
                'EmpSched.sched_id',
            ),
            'conditions' => array(
                'EmpSched.emp_id' => $employee['Employee']['id'],
            ),
        ));
        $this->set(compact('shiftOrder'));
        $res=$this->data;
        $this->set(compact('res'));

        $emplosched = $this->EmpSched->find('all',array(
            'fields' => array(
                'Schedule.time_in',
                'Schedule.time_out',
                'Week.start_date',
                'Week.end_date',
            ),
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'schedules',
                    'alias' => 'Schedule',
                    'conditions' => array(
                    'EmpSched.sched_id=Schedule.order_schedules',
                )
            ),
            array(
                'type' => 'left',
                'table' => 'weeks',
                'alias' => 'Week',
                'conditions' => array(
                'EmpSched.week_id=Week.week_no',
                    )
                ),
            ),
            'conditions' => array(
                'EmpSched.emp_id' => $employee['Employee']['id'],
            ),
        ));

            $this->set(compact('emplosched'));
            $this->set(compact('Employsched'));

#SHIFT TO SCHEDULE
            $exs=$this->Schedule->find('all',array(
                'fields'=>array(
                    'Schedule.id',
                    'Schedule.time_in',
                    'Schedule.time_out'
                )
            ));

            $this->set(compact('exs'));
            $govDeduc = $this->Govdeduction->getGovTax($employee['Employee']['tax_status'], Security::cipher($employee['Employee']['monthly'], 'my_key')); /*$employee['Employee']['monthly']*/
            $this->set(compact('govDeduc'));
		}

		public function edit($id=null)
        {
            $this->Employee->id = $id;

            $subgroups = $this->SubGroup->find('list',array(
                'conditions' => array('authorize' => '1'),
                'order' => array('group_id' => 'ASC')
            ));
            $this->set(compact('subgroups'));

            $gp = $this->Employee->findGp($id);
            $this->set(compact('gp'));
            $decMonthly = $this->Employee->encryptValue($gp['Employee']['monthly']);
            $this->set(compact('decMonthly'));
            $eData = $this->data;
            if (empty($eData)) {
                $this->data = $this->Employee->read();
            } 
            else 
            {
                $eData['Employee']['monthly']  =$this->Employee->encryptValue($this->data['Employee']['monthly']);
                if ($this->Employee->save($eData)) {
                    $this->Session->setFlash('Employee information has been updated.','success');
                    $this->redirect(array( 'controller' => 'Employees',
                    'action' => 'view_emp', $id));
                }
            }
		}

		public function add_schedule($id=null){
            $employee = $this->Employee->find('first',array(
            'fields' => array(
                'Employee.id',
                'Employee.first_name',
                'Employee.last_name',
                'Group.name',
                'Schedules.start_date',
                'Schedules.end_date'
            ),
            'joins' => array(
                array(
                'type' => 'left',
                'table' => 'groups',
                'alias' => 'Group',
                'conditions' => array(
                    'Employee.group_id = Group.id'
                    )
                ),
                array(
                'type' => 'left',
                'table' => 'schedules',
                'alias' => 'Schedules',
                'conditions' => array(
                    'Employee.id = Schedules.id'
                    )
                )
            ),
            'conditions' => array(
                'Employee.id' => $id
                )
            )
        );
        $this->set(compact('employee'));
		}
		
        public function add_employee(){
            $subgroups = $this->SubGroup->find('list',array(
            'conditions' => array('authorize' => '1'),
            'order' => array('group_id' => 'ASC')
            ));
            $this->set(compact('subgroups'));
            if (!empty($this->data)){
                if($this->Employee->save($this->data))
                {
                    $secretMonthly = Security::cipher($this->data['Employee']['monthly'], 'my_key');
                    $this->request->data['Employee']['monthly'] = $secretMonthly;							
                    $this->request->data['Employee']['id'] =$this->Employee->getLastInsertID();
                    $this->Employee->save($this->request->data);												
                    $this->Session->setFlash('New Employee Saved!','success');
                    $this->redirect(array('action' => 'index'));
                }
            }
		}
		public function modify_sched($id = null){}
		public function edit_day_sched($id=null, $dateId){
						$curr_date_ymd = date('Y-m-d', $dateId);
						$this->set('curr_date_ymd', $curr_date_ymd);
						$this->Employee->id = $id;
						$schedId = $this->Week->findScheduleId($curr_date_ymd,$id);
						$this->set(compact('schedId'));
						$schedExist = $this->Scheduleoverride->find('count',array(
																		'conditions' => array('Scheduleoverride.emp_id' => $id,'start_date' => $curr_date_ymd )));
						$this->set(compact('schedExist'));
						$employee = $this->Employee->getEmployeeSchedulePrf($id, $schedId);
				  	    $this->set(compact('employee'));
						$actions = $this->Scheduleoverride_type->findTypes();
						$this->set(compact('actions'));
						$shifts = $this->Schedule->getShifts();
						$this->set(compact('shifts'));	
						$defaultTimeIn = $this->Schedule->findTimeInCheck($schedId);
						$this->set(compact('defaultTimeIn'));				
						$defaultTimeOut = $this->Schedule->findTimeOutCheck($schedId);
						$this->set(compact('defaultTimeOut'));
						$schedTime = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd); 
						$this->set(compact('schedTime'));		

						if (empty($this->data)) 
                        {
										$this->data = $this->Schedule->read();
										$this->data = $this->Shift->read();
										$this->data = $this->Restday->read();
										$this->data = $this->Scheduleoverride->read();
						} 
                        else 
                        {
										$this->request->data['start_date'] = $curr_date_ymd;
										$this->request->data['end_date'] = $curr_date_ymd;
									    $this->request->data['emp_id'] = $id;
										$this->request->data['create_by'] = $_SESSION['Auth']['User']['User']['id'];
										$schedTime = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);
										$this->set(compact('schedTime'));

										$this->request->data['create_time'] = date("Y-m-d H:i:s");
										if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '8') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);																	
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully marked as restday.','success');
														}
										}
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '4') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);																	
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully marked as sick leave.','success');
														}
										}
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '3') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);																	
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully marked as vacation leave.','success');
														}
										}
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '5') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully marked as half day.','success');
														}
										}
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '1') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);															
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully changed.','success');
														}
										}
                                        	else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '2') {
														if ($this->Scheduleoverride->save($this->data)){
																		$over = $this->Scheduleoverride->getLastInsertID();
																		$this->request->data['sched_id']  = null;
																		$this->request->data['history_type_id'] = '4';
																		$this->request->data['id'] = null;
																		$this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);															
																		$this->History->save($this->request->data);
																		$this->Session->setFlash('Schedule successfully changed.','success');
														}
										}
										 else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '6') {
                            if ($this->Scheduleoverride->save($this->data)){
                                    $over = $this->Scheduleoverride->getLastInsertID();
                                    $this->request->data['sched_id']  = null;
                                    $this->request->data['history_type_id'] = '15';
                                    $this->request->data['id'] = null;
                                    $this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);
                                    $this->History->save($this->request->data);
                                    $this->Session->setFlash('Schedule marked as leave with no pay.','success');
                            }
                    }
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '7') {
                            if ($this->Scheduleoverride->save($this->data)){
                                    $over = $this->Scheduleoverride->getLastInsertID();
                                    $this->request->data['sched_id']  = null;
                                    $this->request->data['history_type_id'] = '4';
                                    $this->request->data['id'] = null;
                                    $this->request->data['scheduleoverride_id'] = $this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd);
                                    $this->History->save($this->request->data);
                                    $this->Session->setFlash('Schedule successfully marked as offset.','success');
                            }
                    }
										else if ($this->data['Scheduleoverride']['scheduleoverride_type_id'] == '9') {
														$this->Scheduleoverride->delete($this->Scheduleoverride->findSchebyTimeAndEmp($id,$curr_date_ymd));
														$this->Session->setFlash('Excemption successfully removed.','success');
                            
                    }

									$this->redirect(array('action' => 'view_emp', $id));									
						}
		}

    public function getLogsOnDay($curr_date_ymd, $userinfo)
    {
        $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));
        $dbName = $this->Checkinout->findAccess();
        if (!file_exists($dbName)) {
            die("Could not find database file.");
        }
        $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
        $sql  = "SELECT CHECKTIME, CHECKTYPE FROM CHECKINOUT WHERE  USERID =".$userinfo." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME ASC ";
        $result = $db->query($sql);
        $schedFound = $result->fetchAll();
        return $schedFound;
    }
    public function error($id=null, $dateId) {
        $employee = $this->Employee->find('first',array(
            'fields' => array(
                'Employee.id',
                'Employee.userinfo_id',
                'Employee.first_name',
                'Employee.last_name',
			    'Employee.tax_status',                                       
		        'Employee.monthly',
                'Employee.employed',
			    'Employee.account_id',                              
			    'Group.name',
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

        $this->set(compact('employee'));
        $userinfo = $employee['Employee']['userinfo_id'];

        $curr_date_ymd = date('Y-m-d', $dateId);
        $this->set('curr_date_ymd', $curr_date_ymd);
        
        $this->Employee->id = $id;
        $schedId = $this->Week->findScheduleId($curr_date_ymd,$id);
        $employee = $this->Employee->getEmployeeSchedulePrf($id, $schedId);
        $this->set(compact('employee'));
        
        $checkTime = $this->Checkinout->findEmployeeLogIn($id, $curr_date_ymd);
        $checkTimeOut = $this->Checkinout->findEmployeeLogOut($id, $curr_date_ymd);

        $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));
        $dateAccessFormat1 = date("n/j/Y", strtotime($curr_date_ymd. ' + 1 days'));
          
        $dbName = $this->Checkinout->findAccess();
        if (!file_exists($dbName)) {
            die("Could not find database file.");
        }
        $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
        $sql  = "SELECT CHECKTIME, CHECKTYPE FROM CHECKINOUT WHERE  USERID =".$userinfo." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME ASC ";
        $result = $db->query($sql);
        $schedFound = $result->fetchAll();
       
        $sql1  = "SELECT CHECKTIME, CHECKTYPE FROM CHECKINOUT WHERE  USERID =".$userinfo." AND CHECKTIME LIKE "."'$dateAccessFormat1%'"." ORDER BY CHECKTIME ASC ";
        $result1 = $db->query($sql1);
        $schedFound1 = $result1->fetchAll();
         
        if ($checkTime != null)
        {
            $checkIn = $checkTime[0]['Checkinout']['CHECKTIME'];      
        }
        else
        {
            $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));
            $dbName = $this->Checkinout->findAccess();
            if (!file_exists($dbName)) {
                die("Could not find database file.");
            }
            $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
            $sql  = "SELECT TOP 1 CHECKTIME FROM CHECKINOUT WHERE CHECKTYPE = 'I' AND USERID =".$userinfo." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME ASC ";
            $result = $db->query($sql);
            $checkIn = $result->fetchAll(PDO::FETCH_COLUMN);
                 
            if ($checkIn == NULL)
            {
                $checkIn = NULL;
            }
            else
            {
                $checkIn = $checkIn[0];
            }
        }
        
        if ($checkTimeOut != null)
        {
            $checkOut = $checkTimeOut[0]['Checkinout']['CHECKTIME'];  
        }
        else
        {
            $dateAccessFormat = date("n/j/Y", strtotime($curr_date_ymd));
            $dbName = $this->Checkinout->findAccess();
            if (!file_exists($dbName)) {
                die("Could not find database file.");
            }
            $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
            $sql  = "SELECT TOP 1 CHECKTIME FROM CHECKINOUT WHERE CHECKTYPE = 'O' AND USERID =".$userinfo." AND CHECKTIME LIKE "."'$dateAccessFormat%'"." ORDER BY CHECKTIME ASC ";
            $result = $db->query($sql);
            $checkOut = $result->fetchAll(PDO::FETCH_COLUMN);  
                    
            if ($checkOut == NULL)
            {
                $checkOut = NULL;
            }
            else
            {
                $checkOut = $checkOut[0];
            }
        }
        $this->set(compact('checkIn'));
        $this->set(compact('checkOut'));
        $this->set(compact('schedFound'));
        $this->set(compact('schedFound1'));
        
        if (!empty($this->data)){
            if($this->Checkinout->save($this->data))
                   {
                        $this->Session->setFlash('Log Entry Saved!','success');
                        $this->request->data['Checkinout']['USERID'] = $employee['Employee']['userinfo_id'];
                        $this->request->data['Checkinout']['CHECKTIME'] = $this->data['Checkinout']['CHECKTIME2'];
                        $this->request->data['Checkinout']['CHECKTYPE'] = 'O';
                        $this->request->data['Checkinout']['id'] = NULL;
                        $this->Checkinout->save($this->request->data);
                        $this->Session->setFlash('Log Entry Saved!','success');
                        $this->redirect(array('action' => 'view_emp', $id));
                   }
        }
    }
    public function getSpecHoliday($date)
    {   
        $date2 = date("Y-m-d", $date);
        $type = $this->Holiday->getHoliday($date2);
        return $type;
       
    }
    public function view_all($dateId, $redir_in)
    {
        $this->layout='view_all';
        $employees = $this->Employee->find('all', array(
            'conditions' => array(
                'employed' => '1'
                ),
            'order' => array(
                'subgroup_id' => 'ASC',
                'last_name' => 'ASC',
                'first_name' => 'ASC')
            ));
        $this->set(compact('employees'));
        $empCount = $this->Employee->find('count', array(
            'conditions' => array('employed' => '1')));	
        $total = $this->Total->fetchEmployeesOfCutOff($dateId);
        
        $this->set(compact('total'));
        $this->set(compact('dateId'));
        $this->set(compact('empCount'));
        
        $startCut = $this->Cutoff->getCutOffPeriodStart($dateId);
        $endCut = $this->Cutoff->getCutOffPeriodEnd($dateId);
        
        $this->set(compact('startCut'));
        $this->set(compact('endCut'));
        $this->set(compact('redir_in'));
    }

    public function view_all2($id=null, $dateId)
    {
			$this->set(compact('dateId'));
            $trans = $this->CompAdvanceRule->getAll();
            $this->set(compact('trans'));
            $sdate = date("Y-m-d", time());
		    $total = $this->Cutoff->getCutOffAvailable($sdate);
		    $this->set(compact('total'));

                    $sdates = $this->Cutoff->getCutOffPeriodStart($dateId);
					$edates = $this->Cutoff->getCutOffPeriodEnd($dateId);

            $retroPay=$this->Retro->find('all',array('conditions'=>array('Retro.emp_id'=>$id, 'Retro.cutoff_id'=>$dateId),'order'=>'Retro.id DESC'));
			$this->set(compact('retroPay'));
            $this->set(compact('sdates'));
            $this->set(compact('edates'));
            $weekStart =  $this->Week->getAllStart();
            $weekEnd =  $this->Week->getAllEnd();
            $weekNum =  $this->Week->getWeekNo();
            $this->set(compact('weekStart'));
            $this->set(compact('weekEnd'));
            $this->set(compact('weekNum'));

            $cutoffLoan=$this->Cutoff->find('first',array('conditions'=>array('Cutoff.id'=>$dateId)));
            $this->set(compact('cutoffLoan'));
            $histor = $this->Employee->getHistor($id);
            $this->set(compact('histor'));
            
            $sl = $this->Scheduleoverride->countCredit(4, $id, $sdates);
            $vl = $this->Scheduleoverride->countCredit(3, $id, $sdates);
            $this->set(compact('sl'));
            $this->set(compact('vl'));
            $yearL = substr($sdates,0,4);
            $this->set(compact('yearL'));

            $empLoans = $this->Loan->find('all',array(
                'joins'=>array(
                    array(
                    'type' => 'left',
                    'table' => 'cutoffs',
                    'alias' => 'Cutoff',
                    'conditions' => array(
                    'Cutoff.id'=> $dateId
                     )
                    ),
                ),
                'conditions'=>array(
                    'Loan.emp_id'=>$id,
                        '((Loan.start_date >= Cutoff.start_date and Loan.start_date <= Cutoff.end_date) or ((Loan.end_date > Cutoff.end_date) or (Loan.end_date >= Cutoff.end_date))) and (Loan.start_date < Cutoff.end_date)'
                )));

            $this->set(compact('empLoans'));
            $no_log = $this->Employee->getEmployeeNoLog($id);
            $this->set(compact('no_log'));

            $employee = $this->Employee->getEmployee($id);
            $this->set(compact('employee'));
            $schedId = $this->Week->findScheduleId($sdate,$id);
            $this->set(compact('schedId'));
            $startin = $this->Employee->getStartIn($id);
            $this->set(compact('startin'));
     
            $holidays = $this->Holiday->find('all',array(
            'fields' => array(
            'Holiday.date', 'Holiday.regular'),
            'conditions' => array('Holiday.authorize' => '1')
        ));
        $this->set(compact('holidays'));
        $excemptions = $this->Scheduleoverride->find('all',array(
            'fields' => array(
                'Scheduleoverride.start_date',
                'Scheduleoverride.end_date',
                'Scheduleoverride.time_in',
                'Scheduleoverride.time_out',
                'Scheduleoverride_type.name',
                ),
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'scheduleoverride_types',
                    'alias' => 'Scheduleoverride_type',
                    'conditions' => array(
                    'Scheduleoverride.scheduleoverride_type_id = Scheduleoverride_type.id',
                    )
                ),
            ),
            'conditions' => array(
                'Scheduleoverride.emp_id' => $employee['Employee']['id'],
            ),
        ));
        $this->set(compact('excemptions'));
        $shiftOrder = $this->EmpSched->find('all',array(
            'fields'=>array(
                'EmpSched.sched_id',
            ),
            'conditions' => array(
                'EmpSched.emp_id' => $employee['Employee']['id'],
            ),
        ));
        $this->set(compact('shiftOrder'));
        $res=$this->data;
        $this->set(compact('res'));

        $emplosched = $this->EmpSched->find('all',array(
            'fields' => array(
                'Schedule.time_in',
                'Schedule.time_out',
                'Week.start_date',
                'Week.end_date',
            ),
            'joins' => array(
                array(
                    'type' => 'left',
                    'table' => 'schedules',
                    'alias' => 'Schedule',
                    'conditions' => array(
                    'EmpSched.sched_id=Schedule.order_schedules',
                )
            ),
            array(
                'type' => 'left',
                'table' => 'weeks',
                'alias' => 'Week',
                'conditions' => array(
                'EmpSched.week_id=Week.week_no',
                    )
                ),
            ),
            'conditions' => array(
                'EmpSched.emp_id' => $employee['Employee']['id'],
            ),
        ));

            $this->set(compact('emplosched'));
            $this->set(compact('Employsched'));

#SHIFT TO SCHEDULE
            $exs=$this->Schedule->find('all',array(
                'fields'=>array(
                    'Schedule.id',
                    'Schedule.time_in',
                    'Schedule.time_out'
                )
            ));

            $this->set(compact('exs'));
            $govDeduc = $this->Govdeduction->getGovTax($employee['Employee']['tax_status'], Security::cipher($employee['Employee']['monthly'], 'my_key')); /*$employee['Employee']['monthly']*/
            $this->set(compact('govDeduc'));
			
#########account_id
		$emp_accnt_Id=$this->Employee->find('first',array('fields'=>array('Employee.account_id'),'conditions'=>array('Employee.id'=>$employee['Employee']['id'])));
		$this->set(compact('emp_accnt_Id'));
		
		}
                                                                                                                       

}
