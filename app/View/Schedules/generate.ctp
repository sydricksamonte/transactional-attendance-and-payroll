<div class="colorw" >
<div class="btn btn-primary" style='width:70px'>
<?php echo $this->Html->link("Reshuffle", array('action' => 'generate', $netType)); ?>
</div>
<div class="btn btn-primary" style='width:40px'><?php echo $this->Html->link("Save", array('action' => 'save')); ?></div></div>
<div>
<br><br>

<?php if ($unable != null) { echo '<table class="table-bordered" style="width:40%; background-color:#FFADAD"><thead"><tr><th>'. 'Unable to generate schedule(s) for: (No possible matches from previous month) '; ?> </th></tr></thead><br><tbody><tr><td>

<?php foreach ($names as $u): 
?>

<?php {	echo $u;  
?><br><?php } endforeach;}?>
</td></tr></tbody></table></div>
<div>
<br><br>
<?php if ($empNoSched != null) { echo '<table class="table-bordered" style="width:40%; background-color:#FFADAD"><thead><tr><th>'. 'Unable to generate schedule(s) for: (No schedules found on previous month)'; ?> </th></tr></thead><br><tbody><tr><td>
<?php foreach ($empNoSched as $en): 
			 ?><?php {	echo $en['Employee']['last_name'].', '.$en['Employee']['first_name'] . '   ';  
						echo $this->Html->link('Add Schedule    ', array('controller'=>'Employees','action' => 'change_sched', $en['Employee']['id']));
						echo $this->Html->link('Edit Employee', array('controller'=>'Employees','action' => 'edit', $en['Employee']['id']));?>
			 <br><?php }
		endforeach;}
 ?>
</td></tr></tbody></table></div>
<table class="table-bordered" style="width:98%">
  <thead>
    <tr>
	<?php foreach ($shiftAll as $en): ?>
      <th><?php {	echo $en['DayShift']['shift']; } ?> </th>
	  <?php endforeach;?>
	</tr>
	<tr>
	<?php foreach ($shiftAll as $en): { ?>
      <td><?php echo $this->requestAction('Schedules/getGroupCount/'.$en['DayShift']['id']);?> </td>
	  <?php } endforeach;?>
	</tr>
	  </thead>
	  </table>
<table class="table-bordered" style="width:98%">
  <thead>
    <tr>
      <th>Employee</th>
      <th>Mon</th>
      <th>Tue</th>
      <th>Wed</th>
      <th>Thu</th>
      <th>Fri</th>
      <th>Sat</th>
      <th>Sun</th>
    </tr>
  </thead>
<div>
    <?php foreach($tempSched as $employee):?>
    <?php $arrDays = $this->requestAction('Schedules/getWorkDayNames/'.$employee['Schedule']['days']);?>
    <?php $timeSchedIn =date('h:i a',strtotime($employee['Schedule']['time_in']));
          $timeSchedOut =date('h:i a',strtotime($employee['Schedule']['time_out']));?>
   <tbody>
    <tr>
     <td><?php echo $this->Html->link($employee['Employee']['last_name'].', '. $employee['Employee']['first_name'], array('controller'=>'Employees','action' => 'view_emp', $employee['Employee']['id']));?></td>
     <td><?php if (in_array("Monday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Tuesday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Wednesday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Thursday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Friday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Saturday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
     <td><?php if (in_array("Sunday", $arrDays)){ echo $timeSchedIn.'-'.$timeSchedOut; }?> </td>
    </tr>
    </tbody>
    <?php endforeach;?>
  </table>
</div>
<?php
?>
<td><?php #echo $this->Form->end('Save', array('controller' => 'Groups', 'action' => 'edit') );?></td>

