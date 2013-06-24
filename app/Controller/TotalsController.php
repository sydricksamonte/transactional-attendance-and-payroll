<?php
class TotalsController extends AppController{

  public $uses = array(
    'Employee',
    'History',
    'Shift',
    'Groups',
    'Schedule',
    'Scheduleoverride',
    'Scheduleoverride_type',
    'Historytype',
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
		'Loan'
  );

#		function saveInfo($date,$id,$basic,$account_id,$absent,$late,$deduc,$att, $sss, $ph, $pag,$tax, $ot,$nd,$hd,$net,$error,$hmdf,$sssL )
		function saveInfo($dateId,$employeeID,$basic,$account_id,$absent_total,$late,$deduct_amnt,$attbonus,$sss,$philhealth,$pagibig,$tax,$otamount,$ndamount,$hdamount,$net_pay,$errorCount,$hmdfLoan,$ssLoan)
		{
           
						if ($this->Total->findCutOff($dateId, $employeeID) == null){
										$Total['id'] = null; 
						}
						else
						{
										$existEmp = $this->Total->findCutOff($dateId, $employeeID);
										$Total['id'] = $existEmp;
						}
                        if ($net_pay < 1)
                            {$error= $errorCount+1;
                            }
                            echo $net_pay;
						$Total['cutoff_id'] = $dateId;	 
						$Total['emp_id'] = $employeeID;
						$Total['monthly'] = $basic;
						$Total['account_number'] = $account_id;
						$Total['absents'] = $absent_total;
						$Total['lates'] = $late;
						$Total['deductions'] = $deduct_amnt;
						$Total['att_bonus'] = $attbonus;
						$Total['sss'] = $sss;
						$Total['phil_health'] = $philhealth;
						$Total['pagibig'] = $pagibig;
						$Total['tax'] = $tax;
						$Total['OT'] =number_format($otamount, 2, '.', ''); 
						$Total['night_diff'] =number_format($ndamount, 2, '.', '');
						$Total['holiday'] =number_format($hdamount, 2, '.', '');
						$Total['net_pay'] = $net_pay;
						$Total['error'] = $errorCount;
						$Total['hmdf_loan'] = $hmdfLoan;
                        $Total['sss_loan'] = $ssLoan;
						$this->Total->save($Total);	
                        
		}
		function payslip($cutoff_id){
						$this->layout='view_all';
						$empSal = $this->Total->find('all',array(
																		'fields' => array(
																						'Total.id',
																						'Total.emp_id',
																						'Total.cutoff_id',
																						'Cutoff.start_date',
																						'Cutoff.end_date',
																						'Employee.id',
																						'Employee.first_name',
																						'Employee.last_name',
																						'Employee.monthly',
																						'Employee.tax_status',
																						'Govstat.name',
																						'Employee.subgroup_id',
																						'Group.name',
																						'Total.night_diff',
																						'Total.OT',
																						'Total.holiday',
																						'Total.deductions',
																						'Total.absents',
																						'Total.lates',
																						'Total.att_bonus',
																						'Total.sss',
																						'Total.pagibig',
																						'Total.phil_health',
																						'Total.net_pay',
																						'Total.tax',
																						'Loan.loan_type',
																						'Loan.amount',
																						'Total.sss_loan',
																						'Total.hmdf_loan'
																						),
																						'joins' => array(
																														array(
																																		'type' => 'left',
																																		'table' => 'employees',
																																		'alias' => 'Employee',
																																		'conditions' => array(
																																						'Employee.id=Total.emp_id'
																																						)
																																 ),
																														array(
																																		'type' => 'left',
																																		'table' => 'loans',
																																		'alias' => 'Loan',
																																		'conditions' => array(
																																						'Employee.id = Loan.emp_id'
																																						)
																																 ),
																														array(
																																		'type' => 'left',
																																		'table' => 'cutoffs',
																																		'alias' => 'Cutoff',
																																		'conditions' => array(
																																						'Cutoff.id = Total.cutoff_id'
																																						)
																																 ),
																														array(
																																		'type' => 'left',
																																		'table' => 'groups',
																																		'alias' => 'Group',
																																		'conditions' => array(
																																						'Group.id = Employee.subgroup_id'
																																						)
																																 ),
																														array(
                                                    'type' => 'left',
                                                    'table' => 'govstats',
                                                    'alias' => 'Govstat',
                                                    'conditions' => array(
                                                            'Govstat.id = Employee.tax_status'
                                                            )
                                                 ),
																						),
																						'conditions' => array(
																														'Total.cutoff_id'=>$cutoff_id
																														),
																						'order' => array(
																														'Employee.last_name' => 'ASC'
																														)
																										));

						$this->set(compact('empSal'));
		}
		public function index($id)
		{
						$this->layout='payslip';
						$total = $this->Total->fetchEmployeesOfCutOff($id);
						$this->set(compact('total'));
		}

	public function gotopayslip(){
			$sdate = date("Y-m-d", time());
			$cutoffList=$this->Cutoff->getCutOffAvailable($sdate);
			$this->set(compact('cutoffList'));
			if ($this->data != null)
			{
							$this->redirect(array('controller'=>'Totals','action'=>'payslip',$this->data['Total']['Cutoff List']));
			}
    }


}
