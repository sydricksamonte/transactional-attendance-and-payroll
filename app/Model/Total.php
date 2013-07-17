<?php
class Total extends AppModel{
    function findCutOff($id, $emp_id)
    {
        $cut = $this->find('first',array(
            'fields' => array('id'),
            'conditions' => array(
                'cutoff_id' => $id, 
                'emp_id' => $emp_id
                )
            ));
        
        return $cut['Total']['id'];
    }
    
    function fetchEmployeesOfCutOff($id)
    {
        $totals = $this->find('all',array(
            'fields' => array('Total.*',
                'Emp.first_name',
                'Emp.last_name',
                'CO.start_date',
                'CO.end_date'),
            'joins' => array(
                array(
                'type' => 'inner',
                'table' => 'employees',
                'alias' => 'Emp',
                'conditions' => array(
                    'Emp.id = Total.emp_id'
                    )
                ),
                array(
                'type' => 'inner',
                'table' => 'cutoffs',
                'alias' => 'CO',
                'conditions' => array(
                    'CO.id = Total.cutoff_id'
                    )
                )
            ),
            'conditions' => array(
                'cutoff_id' => $id,
                ),
            'order' => array(
                'Emp.last_name' => 'ASC',
                'Emp.first_name' => 'ASC'
            )));
        
        return $totals;
    }
    
    function fetchEmployeeSalary($cutoff_id)
    {
        $empSal = $this->find('all',array(
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
        
        return $empSal;
    }
}
