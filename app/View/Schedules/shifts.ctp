<div class="btn btn-primary" style='width:90px'><div class="colorw" >
<?php echo $this->Html->link("Add schedule", array('action' => 'add')); ?>
</div></div>
<table class="bordered">

  <thead>
    <tr>
      <th>Shift</th>
      <th>Group</th>
      <th>Actions</th>
    </tr>
  </thead>
<div>
    <?php foreach($shiftSet as $s):?>
   <tbody>
    <tr>
	 <td><?php echo $s['Schedule']['descrip']; ?> </td>
     <td><?php if ($s['Schedule']['group'] == 0)
					{echo 'Regular Shift';}
				else if ($s['Schedule']['group'] == 1)
					{echo 'Early Morning';}
				else if ($s['Schedule']['group'] == 2)
					{echo 'Morning';}
				else if ($s['Schedule']['group'] == 3)
					{echo 'Midday';}
				else if ($s['Schedule']['group'] == 4)
					{echo 'Afternoon';}
				else if ($s['Schedule']['group'] == 5)
					{echo 'Evening';}?> </td>
     <td><?php echo $this->Html->link('Edit', array('controller'=>'Schedules','action' => 'edit',$s['Schedule']['id']));?>
		<?php echo $this->Html->link('Delete', array('controller'=>'Schedules','action' => 'delete',$s['Schedule']['id']));?></td>
   
 
    </tr>
    </tbody>
    <?php endforeach;?>
  </table>

