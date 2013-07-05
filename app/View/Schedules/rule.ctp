<?php echo $this->Form->create('Emp',array('method' => 'post')); ?>
<div class="btn btn-primary" style='width:125px;'><div class="colorw" >
<?php echo $this->Html->link("Add schedule", array('action' => 'add')); ?>
</div></div>

<h2> Schedule rules </h2>
    	<div class="container_12">
        	    		<div class="grid_4" style="height:800px">

        	<div class="grid_4 height400" style="height:800px">
        		<table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0" style="height:800px">
  <thead>
    <tr>
      <th>Schedule</th>
	  <?php foreach($weekData as $w):?>
	  <th><?php echo $w['Schedules']['descrip'];?> </th>
	  <?php endforeach;?>
    </tr>
  </thead>

    <?php foreach($weekData as $w):?>
		<?php 
			
		?>
   <tbody>
    <tr>
     <td <?php $addcols;?> ><?php  echo $this->Html->link($w['Schedules']['descrip'], array('action' => 'edit_rule',$w['Rule']['order_schedules'])); ?></td>
	  <?php foreach($weekData as $w1):
	  $checker = false;
	  if ($this->requestAction('Schedules/checkSched/'. $w1['Rule']['order_schedules'].'/'. $w['Rule']['order_schedules'] .'/' ) == true)
	  {
		$checker = 'Yes';
	  }
	  else
	  {
		$checker = '';
	  }
	?>
	  <td class="numeric"><?php echo $checker;?> </td>
	  <?php endforeach;?>
    </tr>
    </tbody>
    <?php endforeach;?>


<?php
?>

  </table>
</div><div class="clear"></div>
</div></div>
