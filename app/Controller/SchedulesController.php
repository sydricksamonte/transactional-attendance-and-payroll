<?php
class SchedulesController extends AppController{
	public $components=array('Session');
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
    'EmpSched',
    'Rule',
    'TempEmpSched',
    'Week',
    'SubGroup'
  );
	public $helpers = array('Html','Form');

	public function index() {
					$subgroups = $this->SubGroup->find('list',array(
                                    'conditions' => array('authorize' => '1'),
                                    'order' => array('group_id' => 'ASC')
                                    ));
					$this->set(compact('subgroups'));

					$weeks = $this->Week->fetchGenerated();
					$this->set(compact('weeks'));
					if ($this->data != null){

									$weekVal = $this->data['Schedule']['week_id'];
									$weekData = $this->data['Schedule']['week_id'];
									$subgroup = $this->data['Schedule']['subgroup_id'];			
		}
					else
					{				$subgroup = 4;
									$weekVal =  $this->Week->getRecentGenerated();
									$weekData =  $this->Week->getRecentGenerated();
					}
					$this->set(compact('weekVal'));
					$this->set(compact('weekData'));
					$this->set(compact('subgroup'));
					$weekRange = $this->Week->getTodayWeekRange($weekVal);
					$this->set(compact('weekRange'));
					$empSched =  $this->Schedule->getEmpFieldsSchedByWeek($weekData, $subgroup);
					$this->set(compact('empSched'));
					$weekNo = $this->Week->getAllAvailableWeeks();
					$this->set(compact('weekNo'));
					$generate =	$this->Week->enableGeneration();
	}
     function getWorkDayNames($days)
  {
    if($days == '1-2-3-4-5'){
    $wrkDays = array('Monday','Tuesday','Wednesday','Thursday','Friday');
    }
    else if($days == '2-3-4-5-6'){
    $wrkDays = array('Tuesday','Wednesday','Thursday','Friday','Saturday');
    }
    else if($days == '3-4-5-6-7'){
    $wrkDays = array('Wednesday','Thursday','Friday','Saturday','Sunday');
    }
    else if($days == '4-5-6-7-1'){
    $wrkDays = array('Thursday','Friday','Saturday','Sunday','Monday');
    }
    else if($days == '5-6-7-1-2'){
    $wrkDays = array('Friday','Saturday','Sunday','Monday','Tuesday');
    }
    else if($days == '6-7-1-2-3'){
    $wrkDays = array('Saturday','Sunday','Monday','Tuesday','Wednesday');
    }
    else if($days == '7-1-2-3-4'){
    $wrkDays = array('Sunday','Monday','Tuesday','Wednesday','Thursday');
    }
    return $wrkDays;
  }
    function getWorkDayNames2($days)
    {
        if($days == 'MTWThF'){
            $wrkDays = '01-05';
        }
        else if($days == 'TWThFSa'){
            $wrkDays = '02-06';
        }
        else if($days == 'WThFSaSu'){
            $wrkDays = '03-07';
        }
        else if($days == 'MThFSaSu'){
            $wrkDays = '04-01';
        }
        else if($days == 'MTFSaSu'){
            $wrkDays = '05-02';
        }
        else if($days == 'MTWSaSu'){
            $wrkDays = '06-03';
        }
        else if($days == 'MTWThSu'){
            $wrkDays = '07-01';
        }
        return $wrkDays;
    }

    public function add(){
        $group = NULL;
        $descPattern = NULL;
        $wrkPattern1 = NULL;
        $rstPattern = NULL;
        $timePattern =  $this->data['Schedule']['time_in']['hour'].':'.$this->data['Schedule']['time_in']['min'].$this->data['Schedule']['time_in']['meridian'].'-'.$this->data['Schedule']['time_out']['hour'].':'.$this->data['Schedule']['time_out']['min'].$this->data['Schedule']['time_out']['meridian'];
        $shiftSet = $this->Schedule->groupShifts();
        $this->set(compact('shiftSet'));
        if($this->data['Schedule']['sun'] == '1')
        {
            $descPattern = 'Su'. $descPattern;
            $wrkPattern1 = '7-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Sun-'. $rstPattern;
        }
        if($this->data['Schedule']['sat'] == '1')
        {
            $descPattern = 'Sa'. $descPattern;
            $wrkPattern1 = '6-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Sat-'. $rstPattern;
        }
        if($this->data['Schedule']['fri'] == '1')
        {
            $descPattern = 'F'. $descPattern;
            $wrkPattern1 = '5-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Fri-'. $rstPattern;
        }
        if($this->data['Schedule']['thu'] == '1')
        {
            $descPattern = 'Th'. $descPattern;
            $wrkPattern1 = '4-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Thu-'. $rstPattern;
        }
        if($this->data['Schedule']['wed'] == '1')
        {
            $descPattern = 'W'. $descPattern;
            $wrkPattern1 = '3-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Wed-'. $rstPattern;
        }
        if($this->data['Schedule']['tue'] == '1')
        {
            $descPattern = 'T'. $descPattern;
            $wrkPattern1 = '2-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Tue-'. $rstPattern;
        }
        if($this->data['Schedule']['mon'] == '1')
        {
            $descPattern = 'M'. $descPattern;
            $wrkPattern1 = '1-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Mon-'. $rstPattern;
        }
        $regs = 0;
        if ($this->data['Schedule']['group'] == 0)
        {
            $regs = 0;
        }
        else
        {
            $regs = 1;
        }
        $wrkPattern1 = substr($wrkPattern1, 0, -1);
        $rstPattern = substr($rstPattern, 0, -1);
        $descPattern = $descPattern.' '. strtoupper($timePattern);
        $order_sched_last =  $this->Schedule->getLastOrderSched($regs);
    
        
        if ($this->Schedule->getExistingSchedDesc($descPattern)== TRUE)
        {
            $this->Session->setFlash('Schedule Exist');
        }
         else{
            if (!empty($this->data)){
                if($this->Schedule->save($this->data)){
                    $this->request->data['Schedule']['regular'] = $regs;
                    $this->request->data['Schedule']['days'] = $wrkPattern1;	
                    $this->request->data['Schedule']['group'] = $this->data['Schedule']['group'];
                    $this->request->data['Schedule']['descrip'] = $descPattern;	
                    $this->request->data['Schedule']['rd'] = $rstPattern;
                    $this->request->data['Schedule']['order_schedules'] = $order_sched_last;										
                    $this->request->data['Schedule']['id'] =$this->Schedule->getLastInsertID();
                    $this->Schedule->save($this->request->data);													
                    $this->Session->setFlash('New Schedule Saved!');
                    $this->redirect(array('action' => 'shifts'));
                }
            }
        }
    }
    public function shifts(){
        $shiftSet = $this->Schedule->fetchShifts();
        $this->set(compact('shiftSet'));
    }
    function delete($id){
        if($this->Schedule->delete($id)){
            $this->Session->setFlash('Schedule has been deleted.');
            $this->redirect(array('action' => 'shifts'));
        }
    }
     public function edit($id){
        $this->Schedule->id = $id;
        
        $descPattern = NULL;
        $wrkPattern1 = NULL;
        $rstPattern = NULL;
        $timePattern =  $this->data['Schedule']['time_in']['hour'].':'.$this->data['Schedule']['time_in']['min'].$this->data['Schedule']['time_in']['meridian'].'-'.$this->data['Schedule']['time_out']['hour'].':'.$this->data['Schedule']['time_out']['min'].$this->data['Schedule']['time_out']['meridian'];
        $shiftSet = $this->Schedule->groupShifts();
        $this->set(compact('shiftSet'));
        if($this->data['Schedule']['sun'] == '1')
        {
            $descPattern = 'Su'. $descPattern;
            $wrkPattern1 = '7-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Sun-'. $rstPattern;
        }
        if($this->data['Schedule']['sat'] == '1')
        {
            $descPattern = 'Sa'. $descPattern;
            $wrkPattern1 = '6-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Sat-'. $rstPattern;
        }
        if($this->data['Schedule']['fri'] == '1')
        {
            $descPattern = 'F'. $descPattern;
            $wrkPattern1 = '5-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Fri-'. $rstPattern;
        }
        if($this->data['Schedule']['thu'] == '1')
        {
            $descPattern = 'Th'. $descPattern;
            $wrkPattern1 = '4-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Thu-'. $rstPattern;
        }
        if($this->data['Schedule']['wed'] == '1')
        {
            $descPattern = 'W'. $descPattern;
            $wrkPattern1 = '3-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Wed-'. $rstPattern;
        }
        if($this->data['Schedule']['tue'] == '1')
        {
            $descPattern = 'T'. $descPattern;
            $wrkPattern1 = '2-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Tue-'. $rstPattern;
        }
        if($this->data['Schedule']['mon'] == '1')
        {
            $descPattern = 'M'. $descPattern;
            $wrkPattern1 = '1-'. $wrkPattern1;
        }
        else
        {   
            $rstPattern = 'Mon-'. $rstPattern;
        }

        $wrkPattern1 = substr($wrkPattern1, 0, -1);
        $rstPattern = substr($rstPattern, 0, -1);
        $descPattern = $descPattern.' '. strtoupper($timePattern);
        $order_sched_last =  $this->Schedule->getLastOrderSched($this->data['Schedule']['regular']);
        $regs = 0;
        if ($this->data['Schedule']['group'] == 0)
        {
            $regs = 0;
        }
        else
        {
            $regs = 1;
        }


        if ($this->Schedule->getExistingSchedDesc($descPattern)== TRUE)
        {
            $this->Session->setFlash('Schedule Exist');
        }
        else{
            if (!empty($this->data)){
                $this->Schedule->read();
                    $this->request->data['Schedule']['regular'] = $regs;	
                    $this->request->data['Schedule']['days'] = $wrkPattern1;	
                    $this->request->data['Schedule']['group'] = $this->data['Schedule']['group'];
                    $this->request->data['Schedule']['descrip'] = $descPattern;	
                    $this->request->data['Schedule']['rd'] = $rstPattern;
                    $this->request->data['Schedule']['order_schedules'] = $order_sched_last;										
                    $this->request->data['Schedule']['id'] =$id;
                    $this->Schedule->save($this->request->data);													
                    $this->Session->setFlash('Schedule successfully modified!');
                    #$this->redirect(array('action' => 'shifts'));
                
            }
            else {
                $this->data = $this->Schedule->read();
               
            } 
            

        }
    }
	public function weeks(){

	}
    public function checkSched($sched_id,$rule_id)
    {
            $desc = $this->Rule->fetchRuleSchedule($sched_id,$rule_id);
            return $desc;
    }
    public function rule(){
        $weekData = $this->Rule->fetchDistinctSchedule();
        $this->set(compact('weekData'));
	}
     public function findIdByRuleAndOSched($rule, $osched){
        $rule_id = $this->Rule->findId($rule, $osched);
        $this->set(compact('rule_id'));
        return $rule_id;
        
	}
    public function editruleSave($orderSchedId, $ruleid, $id){
            {
                $this->request->data['Rule']['id'] = $id;
                $this->request->data['Rule']['order_schedules'] = $orderSchedId;										
                $this->request->data['Rule']['rules'] = $ruleid;
                $this->Rule->save($this->request->data);
                $this->Session->setFlash('Schedule information has been updated');
                $this->redirect(array('action' => 'edit', $ruleid));
            }		
	}
    public function deleteruleSave( $id){
        $this->Rule->delete($id);
        $this->Session->setFlash('Schedule information has been updated');
        $this->redirect(array('action' => 'edit'));
	}
     public function edit_rule($id){
        $weekData = $this->Rule->fetchDistinctSchedule();
        $orderScheds = $this->Rule->getRulesOfOrderSched($id);
        $this->set(compact('weekData'));
        $this->set(compact('orderScheds'));
        $this->set(compact('id'));  
	}
    
    public function generate($netType){
					$unable = null;
					$this->set(compact('netType'));
					$toGenWeek =  $this->Week->getFollowUpWeek();
					$this->set(compact('toGenWeek')); #'2013-04-01 to 2013-04-07'
					$this->TempEmpSched->deleteAll('1=1');
					$weekData = $this->Week->getRecentGenerated(); #week id: 1
					$netEmp = $this->EmpSched->getAllNetEmpOfWeek($weekData, $netType);
					foreach ($netEmp as $gen):
					{
									$this->request->data['TempEmpSched']['id'] = null;
									$this->request->data['TempEmpSched']['week_id'] = 0;
									$exSched = $this->TempEmpSched->fetchTempSched();									
									$this->request->data['TempEmpSched']['sched_id'] = $this->Rule->getNextSched($gen['EmpSched']['sched_id'], $exSched);
									$this->request->data['TempEmpSched']['emp_id'] = $gen['EmpSched']['emp_id'];
									if  ($this->request->data['TempEmpSched']['sched_id'] == null)
									{
											$unable =  $gen['EmpSched']['emp_id'] .','.$unable;	
									}
									else
									{	
													$this->TempEmpSched->save($this->request->data);	
									}
					}
					endforeach;	
					$tempSched = $this->Schedule->getTempData();
					$unable = substr($unable,0,-1);
				
					$unable = explode(',',$unable);				
					if ($unable[0] == '')
					{ $unable = null;} 
					$names= null;	
					foreach ($unable as $u):
					{
						$names = $this->Employee->findEmployeeName($u) . '!' . $names;
						
					}
					endforeach;
					$names = substr($names,0,-1);
					$names = explode('!',$names);
					$this->set(compact('unable'));	
					$this->set(compact('names'));
          $this->set(compact('tempSched'));
	}
	public function save(){
					if ($this->request->data == null)
					{}
					else {
									$tempFields = $this->TempEmpSched->fetchTempField();
									$this->set(compact('tempFields'));
									$unGen = $this->Week->fetchUngenerated();
									$this->set(compact('unGen'));
									$newEmpFields = $this->Week->getWeeksToGenerate($this->data['EmpSched']['week_use']);
									foreach ($newEmpFields as $week):
									{			
													foreach ($tempFields as $gen):
													{			
																	$this->request->data['EmpSched']['id'] = null;
																	$this->request->data['EmpSched']['week_id'] = $week;
																	$this->request->data['EmpSched']['sched_id'] =  $gen['TempEmpSched']['sched_id'];
																	$this->request->data['EmpSched']['emp_id'] = $gen['TempEmpSched']['emp_id'];
																	$this->EmpSched->save($this->request->data);
													}
													endforeach;
													$this->request->data['Week']['generated'] = 1;
													$this->request->data['Week']['id']		= $week;						
													$this->Week->save($this->data);								
													$this->Session->setFlash('Schedule(s) saved.');
									}
									endforeach;
									$this->TempEmpSched->deleteAll('1=1');
									$this->redirect(array('action' => 'index'));
					}
	}
}
?>
