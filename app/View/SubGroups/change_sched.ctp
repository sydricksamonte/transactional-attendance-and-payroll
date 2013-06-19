<h3>Change subgroup shift</h3>
<div class="formstyle" style="width:30%;">
<?php 
    echo $this->Form->create('SubGroup', array( 'onsubmit' => 'return confirm("Continuing will override affected schedule(s). Are you sure you want to continue?");')); ?>
	Subgroup ID:
    <?php echo $subgroup['SubGroup']['id'];?>
	Name:
    <?php echo $subgroup['SubGroup']['name'] ;?>
	Group:
	<?php echo $subgroup['SubGroup']['group_id'] ;?>
	Shift:<br><br><br>
	<?php  echo $this->Form->input('start',array('label' => 'From: ', 'type' => 'select' ,'options' => $weekStart, 'value' => $weekNum));?>
	<?php  echo $this->Form->input('end',array('label' => 'To: ', 'type' => 'select' ,'options' => $weekEnd, 'value' => $weekNum)); ?>
	<?php  echo $this->Form->input('scheds',array('label' => 'New Schedule: ', 'type' => 'select', 'options' => $shifts , 'value' => $shifts,)); ?>
	<?php
	echo "<br><center>";
	echo $this->Form->input('Employee.subgroup_id', array( 'value' => $subgroup['SubGroup']['id'], 'type' => 'hidden'));
	?>
	<?php echo $this->Form->end('Save', array('controller' => 'Groups', 'action' => 'edit') );?>
</div>