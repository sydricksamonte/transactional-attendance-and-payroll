
<div class="btn btn-primary" style='width:90px'><div class="colorw" >
<?php echo $this->Html->link('Edit Month Rule', array('action' => 'edit_rule',$this->data['Schedule']['id'])); ?>
</div></div>
<?php
echo $this->Form->create('Schedule');
echo 'Working days:';
$mo = false;
$tu = false;

$we = false;
$th = false;
$fr = false;
$sa = false;
$su = false;
if (strpos($this->data['Schedule']['days'],'1') !== false)
{$mo = true;}
if (strpos($this->data['Schedule']['days'],'2') !== false)
{$tu = true;}
if (strpos($this->data['Schedule']['days'],'3') !== false)
{$we = true;}
if (strpos($this->data['Schedule']['days'],'4') !== false)
{$th = true;}
if (strpos($this->data['Schedule']['days'],'5') !== false)
{$fr = true;}
if (strpos($this->data['Schedule']['days'],'6') !== false)
{$sa = true;}
if (strpos($this->data['Schedule']['days'],'7') !== false)
{$su = true;}
 
echo $this->Form->input('mon',array('label' => 'Monday', 'type'=>'checkbox', 'checked'=> $mo));
echo $this->Form->input('tue',array('label' => 'Tuesday', 'type'=>'checkbox', 'checked'=> $tu));
echo $this->Form->input('wed',array('label' => 'Wednesday', 'type'=>'checkbox', 'checked'=> $we));
echo $this->Form->input('thu',array('label' => 'Thursday', 'type'=>'checkbox', 'checked'=> $th));
echo $this->Form->input('fri',array('label' => 'Friday', 'type'=>'checkbox', 'checked'=> $fr));
echo $this->Form->input('sat',array('label' => 'Saturday', 'type'=>'checkbox', 'checked'=> $sa));
echo $this->Form->input('sun',array('label' => 'Sunday', 'type'=>'checkbox', 'checked'=> $su));
echo $this->Form->input('group',array('label' => 'Shift grouping', 'type' => 'select', 'options' =>$shiftSet));
echo $this->Form->input('Schedule.time_in',array('label' => 'Time In', 'type'=>'time'));
echo $this->Form->input('Schedule.time_out',array('label'=> 'Time Out', 'type'=>'time'));
echo $this->Form->input('Schedule.authorize',array('label' => 'Authorized', 'type'=>'checkbox'));
echo $this->Form->end("Update Schedule");
?>

