<h2>Employee list</h2>
<div class='actions'>
<?php
	echo $this->Form->create('Employee',array('action' => 'filter'));
	echo $this->Form->input('emp_id', array('class' => 'input-medium search-query', 'label' => 'Search', 'type' => 'text'));
	echo $this->Form->end('Search');
?>
</div>
<table style="width:90%">
<tr>
<td>
<table width=1183>
<thead>
<tr>
<th width=57>ID</th>
<th width=197>Last Name</th>     
<th width=279>First Name</th>
<th width=266>Group</th>
<th width=200>Status</th>
<th width=230></th>  
</tr>
</thead>
</table>
</td>
</tr>
<tr>
<td>
<div class="span3">
<table width=1183>
    <?php foreach($res as $employee):?>
    <tbody>
		<tr>
      <td width=57 style="vertical-align:middle"><?php echo $employee['Employee']['id']; ?></td>
	  <td width=197 style="vertical-align:middle"><?php echo $employee['Employee']['last_name']; ?></td>
	  <td width=279 style="vertical-align:middle"><?php echo $employee['Employee']['first_name']; ?></td>     
	  <td width=266 style="vertical-align:middle"><?php
                      if($employee['SubGroup']['name']==''){
                          echo '<div class="colorw"><div class="btn btn-danger" style="height:15px;">';
                          echo $this->Html->link('No Group',array('class' => 'btn', 'action' => 'edit', $employee['Employee']['id'])).'</div></div>';
                      }else{
                          echo $employee['SubGroup']['name'];}
                       ?></td>
	  <td width=153 style="vertical-align:middle"><?php if ($employee['Employee']['employed']=='1'){echo 'Employed';} else {echo  'Resigned';} ?></td> 
      <td width=230 style="vertical-align:middle;text-align:center;"><div class="colorw"><div class="btn btn-info" style='height:15px;'>
        <?php echo $this->Html->link('View',array('action' => 'view_emp', $employee['Employee']['id']));?></div>
       <div class="btn btn-info" style='height:15px;'>
        <?php echo $this->Html->link('Edit',array('class' => 'btn', 'action' => 'edit', $employee['Employee']['id']));?></div>
</td>
    </tr>
		</tbody>
    <?php endforeach;?>
  </table></div></td></tr></table>
