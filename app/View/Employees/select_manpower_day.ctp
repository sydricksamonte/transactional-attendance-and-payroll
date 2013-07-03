
<?php echo $this->Form->create('CutOff');
$s_i = null;
$s_o = null;
$s_i2 = null;
$s_o2 = null;
$l_i = null;
$l_o = null;
$absent = null;
 ?>
<table>
		<tr>
			<th>Last Name</th>
			<th>First Name</th>
			<th>Scheduled In</th>
			<th>Scheduled Out</th>
			<th>LogIn</th>
			<th>LogOut</th>
			<th>Late (In minutes)</th>
			<th>Undertime (In minutes)</th>
			<th>Absent</th>
			<th>Remark</th>
			
		</tr>
		<tr>
<?php foreach ($empScheds as $e): ?>
	<td> <?php echo $this->Html->link($e['Employee']['last_name'],array('action' => 'view_emp', $e['Employee']['id'])); ?></td>
	<td> <?php echo $this->Html->link($e['Employee']['first_name'],array('action' => 'view_emp', $e['Employee']['id'])); ?></td>
	<td> <?php if (strpos($e['Schedule']['days'], $dw) !== FALSE) 
				{ 
					$s_i = $e['Schedule']['time_in']; 
					echo $s_i2 = date('H:i',strtotime($e['Schedule']['time_in'])); 
				} 
		?></td>
	<td> <?php if (strpos($e['Schedule']['days'], $dw) !== FALSE) 
				{ 
					$s_o = $e['Schedule']['time_out']; 
					echo $s_o2 = date('H:i',strtotime($e['Schedule']['time_out'])); 
				} 
		?></td>
	<td> <?php $l_i = $this->requestAction('Employees/getLogInAccess/' .$day  . '/'.  $e['Employee']['userinfo_id'] .'/'); 
				if ($l_i != null)
				{ 
					echo date('H:i',strtotime($l_i)); 
				} ?></td>
	<td> <?php $l_o = $this->requestAction('Employees/getLogOutAccess/'.$day  . '/'.  $e['Employee']['userinfo_id'] .'/');
				if ($l_o != null)
				{ 
					echo date('H:i',strtotime($l_o)); 
				} ?></td>
	<td> <?php $late =  floor((strtotime($l_i) - strtotime((date('Y-m-d',$day) . ' '. $s_i))) / 60);
				$late = $late < 0 ? 0: $late; echo $late; 
				?></td>
	<td> <?php $under =  floor((strtotime($l_o) - strtotime((date('Y-m-d',$day) . ' '. $s_o))) / 60); 
				$under = $under > 0 ? 0: $under*-1; 
				if ($s_o != null and $l_o != null) 
				{ 
					echo $under;
				} 
				else 
				{ 
					echo $under = 0;
				} ?></td>
	<td> <?php if ($s_i2 != null and $s_o2 != null and $l_i == null and $l_o == null) 
				{
					echo $absent = 1;
				} 
		
		?></td>
	<td> <?php if ($s_i2 != null and $s_o2 != null and($l_i != null and $l_o == null))
				{
					echo $remark = 'ERROR';
				}
				ELSE if ($s_i2 != null and $s_o2 != null and($l_i == null and $l_o != null))
				{
					echo $remark = 'ERROR';
				}				?></td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->Form->end('Go'); ?>

<br>

