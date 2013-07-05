<?php echo $this->Form->create('Rule',array('method' => 'post')); ?>
<div class="btn btn-primary" style='width:90px'><div class="colorw" >
<?php  echo $this->Html->link("Add new schedule", array('action' => 'add')); ?>
</div></div>

<div class="btn btn-primary" style='width:90px'><div class="colorw" >
<?php  echo $this->Html->link("Edit this schedule", array('action' => 'edit',$weekId)); ?>
</div></div>

<table class="table-bordered">
<h1> Rule for schedule <?php echo $orderScheds[0]['Schedules']['descrip']; ?></h1>
  <thead>
    <tr>
	<?php ?>
      <th>Schedule</th>
	  <th>Possible for Next Month Schedule</th>
    </tr>
  </thead>
<div>
    <?php $i = 0; foreach($weekData as $w):?>
   <tbody>
    <tr>
     <td><?php  echo $w['Schedules']['descrip']; ?></td>
	  <?php 
	  $checker = false;
	  if ($this->requestAction('Schedules/checkSched/'.$w['Rule']['order_schedules'] .'/'. $id.'/'  ) == true)
	  {
		$checker = true;
	  }
	  else
	  {
		$checker = false;
	  }
	?>
	  <td><?php echo $this->Form->input('chk_'.$i,array('label' => false, 'type' => 'checkbox', 'checked' =>$checker));
		   echo $this->Form->input('or_sched_'.$i,array('label' => false, 'type' => 'hidden', 'value' => $w['Rule']['order_schedules']));
		  $rule_id = null;
		  $rule_id = $this->requestAction('Schedules/findIdByRuleAndOSched/'. $id.'/'. $w['Rule']['order_schedules'] .'/' );
			if ($this->data['Rule']['chk_'.$i] == '1'){
			
			$this->requestAction('Schedules/editruleSave/'. $w['Rule']['order_schedules'] .'/'. $id  .'/'. $rule_id.'/'  );
			}
			else if ($this->data['Rule']['chk_'.$i] == '0' and $rule_id!= null){
			$this->requestAction('Schedules/deleteruleSave/'.  $rule_id  .'/'  );
			}
			echo $this->Form->input('id_'.$i,array('label' => false, 'type' => 'hidden', 'value' => $rule_id2 )); 
	  
	?>
	  </td>
    </tr>
    </tbody>
    <?php $i++; endforeach; 
	if($this->data != null) {$this->requestAction('Schedules/redir/'.'/' );} ?>
	
  </table>
</div>
<?php
?>
<td><?php echo $this->Form->end('Save');?></td>

