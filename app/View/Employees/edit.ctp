<h3>Employee Profile</h3>
<div class="formstyle">
<?php //debug($groups);
$gName =($gp['Employee']['subgroup_id']);
$statuslist=array('','Single','Married');
 ?>
<?php
  echo $this->Form->create('Employee',array('action' => 'edit'));
  echo $this->Form->input('id');
  echo $this->Form->input('subgroup_id');
  echo $this->Form->input('userinfo_id', array('label' => 'Biometric ID',  'type' => 'text'));
  echo $this->Form->input('first_name');
  echo $this->Form->input('last_name');

  echo $this->Form->input('monthly',array('label' => 'Monthly Salary', 'type'=>'text', 'value'=> $decMonthly));
  #echo $this->Form->input('tax_status',array('type'=>'select','options'=>$statuslist));
  echo $this->Form->input('tax_status',array('label'=> 'Tax type', 'type'=>'select', 'options'=> array('S-M-NO', 'S1-M1', 'S2-M2', 'S4-M4')));

  echo $this->Form->input('account_id',array('label'=> 'AUB account', 'type'=>'text'));
  echo $this->Form->input('position',array('label'=> 'Position', 'type'=>'text'));
  echo $this->Form->input('employed', array('label' => 'Employed'));
  echo "<br><br><center>";
  echo $this->Form->end('Save');
?>
</div><br>
<div class="btn"><a href="javascript:window.history.back()"><b>Back</b></a></div>

