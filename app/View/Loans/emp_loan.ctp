 <h2>Employee list</h2>
 <div class='actions'>
<?php
	echo $this->Form->create('Loan',array('action' => 'emp_loan'));
	echo $this->Form->input('emp_id', array('class' => 'input-medium search-query', 'label' => '', 'type' => 'text'));
	echo $this->Form->end('Search',array('class'=>'btn btn-info'));
?>
</div>
	<table style="width:90%">
	<tr>
	<td>
	<table width=1183>
	<thead>
	<tr>
			  <th width=57>ID</th>
			  <th width=197>First Name</th>
			  <th width=279>Last Name</th>
			  <th width=266>Group</th>
			  <th width=250>Employment Status</th>
  		      <th width=80></th><th width=230></th>
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
			<td width=250 style="vertical-align:middle"><?php echo $employee['Employee']['first_name']; ?></td>     
			<td width=400 style="vertical-align:middle"><?php
                      if($employee['SubGroup']['name']==''){
                          echo '<div class="colorw"><div class="btn btn-danger" style="height:15px;">';
                          echo $this->Html->link('No Group',array('class' => 'btn', 'controller'=>'Employees','action' => 'edit', $employee['Employee']['id'])).'</div></div>';
                      }else{
                          echo $employee['SubGroup']['name'];}
                       ?></td>
			<td width=200 style="vertical-align:middle"><?php if ($employee['Employee']['employed']=='1'){echo 'Employed';} else {echo  'Resigned';} ?></td> 
			<td width=150 style="vertical-align:middle"><div class="colorw"><div class="btn btn-info" style='height:15px;'>
				<?php echo $this->Html->link('Add Loan',array('action' => 'add_loan_loan', $employee['Employee']['id']));?></div>
			<td width=150 style="vertical-align:middle"><div class="colorw"><div class="btn btn-info" style='height:15px;'>
			<?php echo $this->Html->link('Edit Loan',array('action' => 'edit_emp_loan', $employee['Employee']['id']));?></div>
			</td>
    </tr>
		</tbody>
    <?php endforeach;?>
  </table></div></td></tr></table>
