<?php echo $this->Form->create('Emp',array('method' => 'post')); ?>
<div class="btn btn-primary" style='width:90px'><div class="colorw" >
<?php echo $this->Html->link("Add schedule", array('action' => 'add')); ?>
</div></div>
<table class="table-bordered">
<h1> Schedule rules </h1>
  <thead>
    <tr>
      <th>Schedule</th>
	  <?php foreach($weekData as $w):?>
	  <th><?php echo $w['Schedules']['descrip'];?> </th>
	  <?php endforeach; debug($weekData);?>
    </tr>
  </thead>
<div>
    <?php foreach($weekData as $w):?>
   <tbody>
    <tr>
     <td><?php  echo $this->Html->link($w['Schedules']['descrip'], array('action' => 'edit',$w['Schedules']['id'])); ?></td>
	  <?php foreach($weekData as $w1):
	  $checker = false;
	  if ($this->requestAction('Schedules/checkSched/'. $w1['Rule']['order_schedules'].'/'. $w['Rule']['order_schedules'] .'/' ) == true)
	  {
		$checker = true;
	  }
	  else
	  {
		$checker = false;
	  }
	?>
	  <td><?php echo $this->Form->input('cut_off',array('label' => false, 'type' => 'checkbox', 'checked' =>$checker));?> </td>
	  <?php endforeach;?>
    </tr>
    </tbody>
    <?php endforeach;?>
  </table>
</div>
<?php
?>
<td><?php echo $this->Form->end('Save');?></td>

