<div class = "span13">
	<?php
	echo $this->Form->create('Employee');
/*	$sDate = ($employee['Schedules']['start_date']);
	$eDate = ($employee['Schedules']['end_date']);
	$shftId = ($employee['Schedules']['shift_id']);

	if ($shftId == null) {
		$shftId = 1;
	}

	$actId = ($employee['Schedules']['action_taken']);

	if ($actId == null){
		$actId = 1;
	}
*/	?>
<div align="right"><a href="javascript:window.history.back()"><--Back</a></div>
	<h2>Employee Profile</h2>
	<div class="formstyle" style="width:68%;">
	Employee ID: <b><?php echo $employee['Employee']['id'];?></b>
	<br><br>
	First Name: <b><?php echo $employee['Employee']['first_name'] ;?></b>
	<br><br>
	Last Name: <b><?php echo $employee['Employee']['last_name'] ;?></b>
	<br><br>
	Group: <b><?php echo $employee['SubGroup']['name'] ; ?></b>
	<br><br>
	Schedule: <b><?php echo $this->Form->input('scheds',array('label' => false,'type' => 'select','options' =>$shifts )); ?></b>
	<br><br>
	Start date: <b><?php  echo $this->Form->input('start',array('label' => false, 
																					'options' => $weekStart, 
																					'type' => 'select', 'value' => $weekNum )); ?></b>
	<br><br>
	End date:<b><?php  echo $this->Form->input('end',array('label' => false, 
																					'options' => $weekEnd, 
																					'type' => 'select', 'value' => $weekNum)); ?></b>
	<br><br>
	<b><?php   $this->Form->input('end_date2',array('label' => false, 'value' => $sDate, 'type' => 'date')); ?></b>

	

			<?php

			#<!--hidden schedule fields-->
			echo $this->Form->input('', array('name' => 'change_by', 'value' => $_SESSION['Auth']['User']['User']['id'], 'type' => 'hidden'));
			echo $this->Form->input('', array('id' => 'Employee', 'name' => 'emp_id', 'value' => $employee['Employee']['id'], 'type' => 'hidden'));
			echo $this->Form->input('', array('name' => 'changed_time', 'value' => date("Y-m-d H:i:s"), 'type' => 'hidden'));

			#<!--hidden history fields-->
			echo $this->Form->input('', array('id' => 'History', 'name' => 'create_time', 'value' => date("Y-m-d H:i:s"), 'type' => 'hidden'));
			echo $this->Form->input('', array('id' => 'History', 'name' => 'emp_id', 'value' =>  $employee['Employee']['id'], 'type' => 'hidden'));
			echo $this->Form->input('', array('id' => 'History', 'name' => 'history_type_id', 'type' => 'hidden'));
			echo $this->Form->input('', array('id' => 'History', 'name' => 'create_by', 'value' => $_SESSION['Auth']['User']['User']['id'], 'type' => 'hidden'));
			echo $this->Form->input('', array('id' => 'History', 'index' => 0, 'value' => null, 'type' => 'hidden'));	
			echo $this->Form->input('', array('name' => 'change_time', 'value' => date("Y-m-d H:i:s"), 'type' => 'hidden'));
			echo $this->Form->input('EmpSched.sched_id', array( 'type' => 'hidden', 'value' => '1'));

			?>
	
	<?php echo $this->Form->end('Add Schedule');?>
	</div>

</div>
</div>

