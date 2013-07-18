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
    
    function saveInfo($dateId,$employeeID,$basic,$account_id,$absent_total,$late,$deduction_amount,$attbonus,$sss,$philhealth,$pagibig,$tax,$otamount,$ndamount,$hdamount,$net_pay,$errorCount,$hmdfLoan,$ssLoan,$addpay,$lesspay)
    {
        if ($this->Total->findCutOff($dateId, $employeeID) == null)
        {
            $Total['id'] = null; 
        }
        else
        {
            $existEmp = $this->Total->findCutOff($dateId, $employeeID);
            $Total['id'] = $existEmp;
        }
        
        if ($net_pay < 1)
        {
            $error = $errorCount+1;
        }
        echo $net_pay;
        $Total['cutoff_id'] = $dateId;	 
        $Total['emp_id'] = $employeeID;
        $Total['monthly'] = $basic;
        $Total['account_number'] = $account_id;
        $Total['absents'] = $absent_total;
        $Total['lates'] = $late;
        $Total['deductions'] = $deduction_amount;
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
        $Total['other_bonus']=$addpay;
        $Total['other_deduction']=$lesspay;
        $this->Total->save($Total);	
    }
    
    public function payslip($cutoff_id)
    {
        $this->layout='view_all';
        $empSal = $this->Total->fetchEmployeeSalary($cutoff_id);
        $this->set(compact('empSal'));
        $others= $this->Retro->find('all',array('conditions'=>array('Retro.cutoff_id'=>$cutoff_id)));
        $this->set(compact('others'));
    }
    
    public function index($id)
    {
        $this->layout='payslip';
        $total = $this->Total->fetchEmployeesOfCutOff($id);
        $this->set(compact('total'));
    }

	public function gotopayslip()
    {
        $sdate = date("Y-m-d", time());
        $cutoffList=$this->Cutoff->getCutOffAvailable($sdate);
        $this->set(compact('cutoffList'));
        if ($this->data != null)
        {
          
            $this->redirect(array('controller' => 'Employees', 'action' => 'view_all', null, $this->data['Total']['Cutoff List'], '0'));
				
        }
    }
    function redirPaySlip($list)
    {
        $this->redirect(array('controller'=>'Totals','action'=>'payslip',$list));
    }
}
