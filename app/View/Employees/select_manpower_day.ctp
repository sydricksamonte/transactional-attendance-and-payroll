<h3>Manpower <?php echo date('Y-m-d',$day); ?></h3>
<?php echo $this->Form->create('CutOff');
$s_i = null;
$s_o = null;
$s_i2 = null;
$s_o2 = null;
$l_i = null;
$l_o = null;
$absent = null;
$all_late = 0;
$all_ut = 0;
$all_abs = 0;
$all_error = 0;
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
	<td> <?php $over = $this->requestAction('Scheduleoverrides/getOverride/' .$day  . '/'.  $e['Employee']['id'] .'/'); 
		if ($over != null)
		{		
			if ($over['Scheduleoverride']['scheduleoverride_type_id'] == '3' or $over['Scheduleoverride']['scheduleoverride_type_id'] == '4' or $over['Scheduleoverride']['scheduleoverride_type_id'] == '8')
			{
				$s_i2 = NULL;
			}
			else
			{
				$s_i = $over['Scheduleoverride']['time_in'];
				echo $s_i2 = date('H:i',strtotime($over['Scheduleoverride']['time_in'])); 
			}
		}
		else{if (strpos($e['Schedule']['days'], $dw) !== FALSE) 
				{ 
					$s_i = $e['Schedule']['time_in']; 
					echo $s_i2 = date('H:i',strtotime($e['Schedule']['time_in'])); 
				} 
				else
				{
					$s_i2 = NULL;
				}
		}
		?></td><td> 
		<?php 
		if ($over != null)
		{	
			if ($over['Scheduleoverride']['scheduleoverride_type_id'] == '3' or $over['Scheduleoverride']['scheduleoverride_type_id'] == '4' or $over['Scheduleoverride']['scheduleoverride_type_id'] == '8')
			{
				$s_o2 = NULL;
			}
			else
			{
				$s_o = $over['Scheduleoverride']['time_out'];
				echo $s_o2 = date('H:i',strtotime($over['Scheduleoverride']['time_out'])); 
			}
		}
		else
		{
			if (strpos($e['Schedule']['days'], $dw) !== FALSE) 
				{ 
					$s_o = $e['Schedule']['time_out']; 
					echo $s_o2 = date('H:i',strtotime($e['Schedule']['time_out'])); 
				} 
				else
				{
					$s_o2 = NULL;
				}
		}
		?></td>
	<td> <?php $l_i = $this->requestAction('Employees/getLogInAccess/' .$day  . '/'.  $e['Employee']['userinfo_id'] .'/'); 
				if ($s_i2 == null and $s_o2 == null)
				{
					$l_i = null;
				}
				else if ($l_i != null)
				{ 
					echo date('H:i',strtotime($l_i)); 
				} ?></td>
	<td> <?php if ($s_i2 >= '21:00'){$l_o = $this->requestAction('Employees/getLogOutAccess/'.strtotime('+1 day', $day)  . '/'.  $e['Employee']['userinfo_id'] .'/');}
				else{$l_o = $this->requestAction('Employees/getLogOutAccess/'.$day  . '/'.  $e['Employee']['userinfo_id'] .'/');}
				if ($s_i2 == null and $s_o2 == null)
				{
					$l_o = null;
				}
				else if ($l_o != null)
				{ 
					echo date('H:i',strtotime($l_o)); 
				} ?></td>
	<td> <?php $late =  floor((strtotime($l_i) - strtotime((date('Y-m-d',$day) . ' '. $s_i))) / 60);
				$late = $late < 0 ? 0: $late; 
				if ($s_i2 == null and $s_o2 == null and($l_i != null and $l_o == null))
				{
					$late = 0;
					echo $late; 
				}
				else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o != null))
				{
					$late = 0;
					echo $late; 
				}
				else
				{
					echo $late; 
				}
				?></td>
	<td> <?php $under =  floor((strtotime($l_o) - strtotime((date('Y-m-d',$day) . ' '. $s_o))) / 60); 
				$under = $under > 0 ? 0: $under*-1; 
				if ($s_i2 == null and $s_o2 == null and($l_i != null and $l_o == null))
				{
					$under = 0;
				}
				else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o != null))
				{
					$under = 0;
				}
				
				if ($s_o != null and $l_o != null) 
				{ 
					echo $under;
				} 
				else 
				{ 
					echo $under = 0;
				} ?></td>
	<td> <?php  if ($s_i2 != null and $s_o2 != null and $l_i == null and $l_o == null) 
				{
					 $absent = 1; echo 'Yes';
				}
				
		
		?></td>
	<td> <?php if ($s_i2 != null and $s_o2 != null and($l_i != null and $l_o == null))
				{
					echo $remark = 'ERROR';
					$all_error = $all_error + 1;
				}
				ELSE if ($s_i2 != null and $s_o2 != null and($l_i == null and $l_o != null))
				{
					echo $remark = 'ERROR';
					$all_error = $all_error + 1;
				}
				else
				{
					$all_late = $all_late + $late;
					$all_abs = $all_abs + $absent;
					$all_ut = $all_ut + $under;
					$late = 0;
					$absent = 0;
					$under = 0;
					$over = null;
				}?></td>
	</tr>
	
		
<?php endforeach; ?>
</table>
<table>
	<tr>
		<th>Total Lates(In minutes, excluding errors)</th>
		<th>Total Undertimes (In minutes, excluding errors)</th>
		<th>Total Absents (Excluding errors)</th>
		<th>Total Errors</th>
	</tr>
	<tr>
		<td> <?php echo $all_late; ?></td>
		<td> <?php echo $all_ut; ?></td>
		<td> <?php echo $all_abs; ?></td>
		<td> <?php echo $all_error; ?></td>
	</tr>
</table>


<br>

