<?php
class RetrosController extends AppController{

				public $components = array('RequestHandler');

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
  );


	public function index($id=null,$cutoff=null) {
					$employee=$this->Employee->find('first',array('conditions'=>array('Employee.id'=>$id)));
					$this->set(compact('employee'));
					$empRet=$this->Retro->find('first',array(
																	'fields'=>array(
																					'Retro.id',
																					'Retro.emp_id',
																					'Retro.retropay',
																					'Employee.id',
																					'Employee.first_name',
																					'Employee.last_name',
																					),
																	'joins'=>array(
																					array(
																									'type'=>'left',
																									'table'=>'employees',
																									'alias'=>'Employee',
																									'conditions'=>array(
																													'Employee.id=Retro.emp_id'
																													)
																							 )																				
																					),
																	'conditions'=>array(
																					'Retro.emp_id'=>$id
																					)
																					));
					$this->set(compact('empRet'));

					$cuts=$this->Cutoff->find('first',array('conditions'=>array('Cutoff.id'=>$cutoff)));
					$this->set(compact('cuts'));

					/*$existing = $this->Retro->findByEmp_id($id);

					if ($existing) {
									if ($this->request->is('post') || $this->request->is('put')) {
													$this->Retro->id = $empRet['Retro']['id'];
													$this->Retro->set('emp_id',$id);
													$this->Retro->set('retropay',$this->data['Retro']['Retro Pay']);
													$this->Retro->set('cutoff_id',$cutoff);
													if ($this->Retro->save($this->request->data)) {
																	$this->Session->setFlash('Retro pay has been updated.','success');
													} else {
																	$this->Session->setFlash('Unable to update.','failed');
													}
									}

					}
					else {*/
					debug($this->data);
					#debug($this->data['Retro']['taxable']);
					if (($this->data['Retro']['taxable']==1) && ($this->data['Retro']['percent']==0)){
							$this->Session->setFlash('Please Indicate the percentage to be deducted.','failed');
					}else if (($this->data['Retro']['taxable']==0) && ($this->data['Retro']['percent']!=0)){
							$this->Session->setFlash('You Enter percentage of deduction. Please check the Taxable field.','failed');
					}else{
						
									if ($this->request->is('post')) {
													$this->Retro->create();
													$this->Retro->set('emp_id',$id);
													$this->Retro->set('retropay',$this->data['Retro']['Retro Pay']);
													$this->Retro->set('cutoff_id',$cutoff);
													if ($this->Retro->save($this->request->data)) {
																	$this->Session->setFlash('Employee\' Additional Pay has been saved.','success');
																	$this->redirect(array('controller'=>'Retros','action' => 'view_pays',$id,$cutoff));
													} else {
																	$this->Session->setFlash('Unable to Save.','failed');
													}
									}

					}
	}

	public function payslip($id=null,$cutoff=null) {
$this->layout='view_all';

					$empS = $this->Retro->find('first',array(
																	'fields' => array(
																					'Retro.id',
																					'Retro.emp_id',
																					'Retro.type',
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
																					'Total.tax',
																					'Total.pagibig',
																					'Total.phil_health',
																					'Total.net_pay',
																					),
																					'joins' => array(
																													array(
																																	'type' => 'left',
																																	'table' => 'employees',
																																	'alias' => 'Employee',
																																	'conditions' => array(
																																					'Employee.id=Retro.emp_id'
																																					)
																															 ),
																													array(
																																	'type' => 'left',
																																	'table' => 'totals',
																																	'alias' => 'Total',
																																	'conditions' => array(
																																					'Retro.emp_id = Total.emp_id'
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
																						'conditions'=>array(
																														'Retro.emp_id'=>$id
																														)
																					));
						$this->set(compact('empS'));


					$total=$this->Total->find('first',array(
																	'conditions'=>array(
																					'Total.cutoff_id'=>$cutoff,
																					'Total.emp_id'=>$id
																					)
																	)	
													);
					$this->set(compact('total'));

					$retroPay=$this->Retro->find('all',array('conditions'=>array('Retro.emp_id'=>$id, 'Retro.cutoff_id'=>$cutoff),'order'=>'Retro.id DESC'));
					$this->set(compact('retroPay'));
	}

	public function view_pdf($id = null) {
					$this->Retro->id = $id;
					if (!$this->Retro->exists()) {
									throw new NotFoundException(__('Invalid Payslip'));
					}
					ini_set('memory_limit', '512M');
					$this->set('empS', $this->Retro->read(null, $id));
	}

	public function payslip_all(){
$this->layout='view_all';
            $empret = $this->Retro->find('all',array(
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
																						'Retro.retropay'
                                            ),
                                            'joins' => array(
                                                            array(
                                                                    'type' => 'left',
                                                                    'table' => 'employees',
                                                                    'alias' => 'Employee',
                                                                    'conditions' => array(
                                                                            'Employee.id=Retro.emp_id'
                                                                            )
                                                                 ),
                                                            array(
                                                                    'type' => 'left',
                                                                    'table' => 'totals',
                                                                    'alias' => 'Total',
                                                                    'conditions' => array(
                                                                            'Total.emp_id = Employee.id'
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
																																						),
																														'order' => array(
																																						'Employee.last_name' => 'ASC'
																																						)
																																		));

						$this->set(compact('empret'));
	}
	
	public function view_pays($id=null,$cutoff=null){
		
		$employee=$this->Employee->find('first',array('conditions'=>array('Employee.id'=>$id)));
		$this->set(compact('employee'));
	
		$pays=$this->Retro->find('all',array('conditions'=>array('Retro.emp_id'=>$id,'Retro.cutoff_id'=>$cutoff)));
		$this->set(compact('pays'));
	}
	
	public function edit($emp_id=null,$id=null,$cutoff=null){
	
		$this->set(compact('emp_id'));
		$this->set(compact('id'));
		$this->set(compact('cutoff'));
		
		$employee=$this->Employee->find('first',array('conditions'=>array('Employee.id'=>$emp_id)));
		$this->set(compact('employee'));
		
		$pays=$this->Retro->find('first',array('conditions'=>array('Retro.emp_id'=>$emp_id,'Retro.id'=>$id,'Retro.cutoff_id'=>$cutoff)));
		$this->set(compact('pays'));
		
		
		
		$this->Retro->id = $id;

					if (empty($this->data)) {
									$this->data = $this->Retro->read();
					} else {
									if ($this->Retro->save($this->data)) {
													$this->Session->setFlash('Pay has been updated.','success');
													$this->redirect(array('controller'=>'Retros','action' => 'view_pays',$emp_id,$cutoff));
									}
							}


	}
	
	public function minus($id,$cutoff) {
					$employee=$this->Employee->find('first',array('conditions'=>array('Employee.id'=>$id)));
					$this->set(compact('employee'));
					$empRet=$this->Retro->find('first',array(
																	'fields'=>array(
																					'Retro.id',
																					'Retro.emp_id',
																					'Retro.retropay',
																					'Employee.id',
																					'Employee.first_name',
																					'Employee.last_name',
																					),
																	'joins'=>array(
																					array(
																									'type'=>'left',
																									'table'=>'employees',
																									'alias'=>'Employee',
																									'conditions'=>array(
																													'Employee.id=Retro.emp_id'
																													)
																							 )																				
																					),
																	'conditions'=>array(
																					'Retro.emp_id'=>$id
																					)
																					));
					$this->set(compact('empRet'));

					$cuts=$this->Cutoff->find('first',array('conditions'=>array('Cutoff.id'=>$cutoff)));
					$this->set(compact('cuts'));

									if ($this->request->is('post')) {
													$this->Retro->create();
													$this->Retro->set('emp_id',$id);
													$this->Retro->set('retropay',$this->data['Retro']['Retro Pay']);
													$this->Retro->set('cutoff_id',$cutoff);
													if ($this->Retro->save($this->request->data)) {
																	
																	$this->Session->setFlash('Employee\' Deduction Pay has been Saved.','success');
																	#$this->redirect(array('action' => 'view_pays/1/1'));
																	$this->redirect(array('controller'=>'Retros','action' => 'view_pays',$id,$cutoff));
																	
													} else {
																	$this->Session->setFlash('Unable to Save.','failed');
													}
									}

						}
						
					
	}
	

?>
