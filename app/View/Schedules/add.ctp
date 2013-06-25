<?php
echo $this->Form->create('Schedule');
echo 'Working days:';
echo $this->Form->input('mon',array('label' => 'Monday', 'type'=>'checkbox'));
echo $this->Form->input('tue',array('label' => 'Tuesday', 'type'=>'checkbox'));
echo $this->Form->input('wed',array('label' => 'Wednesday', 'type'=>'checkbox'));
echo $this->Form->input('thu',array('label' => 'Thursday', 'type'=>'checkbox'));
echo $this->Form->input('fri',array('label' => 'Friday', 'type'=>'checkbox'));
echo $this->Form->input('sat',array('label' => 'Saturday', 'type'=>'checkbox'));
echo $this->Form->input('sun',array('label' => 'Sunday', 'type'=>'checkbox'));
echo $this->Form->input('group',array('label' => 'Shift grouping', 'type' => 'select', 'options' =>$shiftSet));
echo $this->Form->input('time_in',array('label' => 'Time In', 'type'=>'time'));
echo $this->Form->input('time_out',array('label'=> 'Time Out', 'type'=>'time'));
echo $this->Form->input('Schedule.id', array('value' => null, 'type' => 'hidden'));
echo $this->Form->input('Schedule.authorize', array('value' => '1', 'type' => 'hidden'));
echo $this->Form->end("Add Schedule");
?>

