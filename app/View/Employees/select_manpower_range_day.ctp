<h3>Manpower</h3>
<?php 
$s_i = null;$s_o = null;$s_i2 = null;$s_o2 = null;
$l_i = null;$l_o = null;$l_i2 = null;$l_o2 = null;
$remark = null;
$absent = null;$all_late = 0;$all_ut = 0;$all_abs = 0;$all_error = 0;
?>
<?php echo $this->Form->create('CutOff');
$s_i = null;$s_o = null;$s_i2 = null;$s_o2 = null;
$l_i = null;$l_o = null;$l_i2 = null;$l_o2 = null;
$remark = null;
$absent = null;$all_late = 0;$all_ut = 0;$all_abs = 0;$all_error = 0;
$late = null;
$under =null;

?>

    	<div class="container_12">
        	    		<div class="grid_4" style="height:800px">

        	<div class="grid_4 height400" style="height:800px">
		
        		<table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0" style="height:800px">
  <thead>
		<tr>
			<th valign="top" rowspan=2>Name</th>
			<?php foreach ($range as $r): ?>
			<th colspan=3><center><?php echo date('M d',$r); ?></center></th>
			<?php endforeach; ?>
		</tr>
		<tr>
		
		<?php foreach ($range as $r): ?>
		<th>Late</th>
		<th>Under</th>
		<th>Remarks</th>
		<?php endforeach; ?>
		</tr>
</thead>
</tbody>
<?php foreach ($emp as $e): ?>
	<tr>
	<td class="numeric" nowrap> <?php echo $this->Html->link(($e['Employee']['last_name'].' ,'.$e['Employee']['first_name']),array('action' => 'view_emp', $e['Employee']['id'])); ?></td>
			<?php foreach ($range as $day): ?>
			<?php $dw = date( "w", $day);
				if ($dw == '0') 
	            {$dw = '7';}
				$emps = $this->requestAction('Employees/findDaySched/' .$day  . '/'.  $e['Employee']['id'] .'/'); 
				
				if ($emps != null)
				{
					$over = $this->requestAction('Scheduleoverrides/getOverride/' .$day  . '/'.  $e['Employee']['id'] .'/'); 
					$biometric = 0;
					$biometric = $emps['Employee']['userinfo_id'];
					if ($over != null)
					{		
						if ($over['Scheduleoverride']['scheduleoverride_type_id'] == '3') 
						{
							$s_i2 = NULL;
							$remark = 'VL';
						}
						else if ($over['Scheduleoverride']['scheduleoverride_type_id'] == '4')
						{
							$s_i2 = NULL;
							$remark = 'SL';
						}
						else if ($over['Scheduleoverride']['scheduleoverride_type_id'] == '8')
						{
							$s_i2 = NULL;
						}
						else
						{
							$s_i = $over['Scheduleoverride']['time_in'];
							$s_i2 = date('H:i',strtotime($over['Scheduleoverride']['time_in'])); 
							$s_o = $over['Scheduleoverride']['time_out'];
							$s_o2 = date('H:i',strtotime($over['Scheduleoverride']['time_out'])); 
						}
					}
					else
					{ 
					if (strpos($emps['Schedule']['days'], $dw) !== FALSE) 
						{ 
							$s_i = $emps['Schedule']['time_in']; 
							$s_i2 = date('H:i',strtotime($emps['Schedule']['time_in'])); 
							$s_o = $emps['Schedule']['time_out']; 
							$s_o2 = date('H:i',strtotime($emps['Schedule']['time_out'])); 
							
							$bio = $biometric + 0; 
							$l_i = $this->requestAction('Employees/getLogInAccess/' .$day  . '/'.  $bio . '' .'/'); 

							if ($s_i2 == null and $s_o2 == null)
							{
								$l_i = null;
								$l_i2 = null;
							}
							else if ($l_i != null)
							{ 
								$l_i2 = date('H:i',strtotime($l_i)); 
							} 
		
							if ($s_i2 >= '21:00')
							{
								$l_o = $this->requestAction('Employees/getLogOutAccess/'.strtotime('+1 day', $day)  . '/'.  $bio .'/');
							}
							else
							{
								$l_o = $this->requestAction('Employees/getLogOutAccess/'.$day  . '/'.  $bio .'/');
							}
		
							if ($s_i2 == null and $s_o2 == null)
							{
								$l_o = null;
								$l_o2 = null;
							}
							else if ($l_o != null)
							{ 
								$l_o2 = date('H:i',strtotime($l_o)); 
							} 
							$late =  floor((strtotime($l_i) - strtotime((date('Y-m-d',$day) . ' '. $s_i))) / 60);
							$late = $late < 0 ? 0: $late; 
							if ($s_i2 == null and $s_o2 == null and($l_i != null and $l_o == null))
							{
								$late = 0;
							}
							else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o != null))
							{
								$late = 0;
							}
							else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o == null))
							{
								$late = null;
								$remark = 'RD';
							}
							else
							{
								$late; 
							}
		
							$under =  floor((strtotime($l_o) - strtotime((date('Y-m-d',$day) . ' '. $s_o))) / 60); 
							$under = $under > 0 ? 0: $under*-1; 
							if ($s_i2 == null and $s_o2 == null and($l_i != null and $l_o == null))
							{
								$under = 0;
							}
							else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o != null))
							{
								$under = 0;
							}
							else if ($s_i2 == null and $s_o2 == null and($l_i == null and $l_o == null))
							{
								$under = null;
								$remark = 'RD';
							}
				
							if ($s_o == null and $l_o == null) 
							{
								$under = null;
							} 
		
							if ($s_i2 != null and $s_o2 != null and $l_i == null and $l_o == null) 
							{
								$remark = 'A'; 
								$late = null;
								$under = null;
								$absent = 1; 
							}
				
							if ($s_i2 != null and $s_o2 != null and($l_i != null and $l_o == null))
							{
								$remark = 'E';
								$all_error = $all_error + 1;
							}
							else if ($s_i2 != null and $s_o2 != null and($l_i == null and $l_o != null))
							{
								$remark = 'E';
								$all_error = $all_error + 1;
							}
							else
							{	
								$all_late = $all_late + $late;
								$all_abs = $all_abs + $absent;
								$all_ut = $all_ut + $under;
							}
							
						$hd = null;
						$hd = $this->requestAction('Employees/getSpecHoliday/' .$day  . '/');
						if ($hd != null and  ($emps['Employee']['subgroup_id'] != '3' and $emps['Employee']['subgroup_id'] != '4' and $emps['Employee']['subgroup_id'] != '16' and $emps['Employee']['subgroup_id'] != '17'))
						{
							$remark = $hd;
							$late = null;
							$under = null;
							$hd = null; 
						}
						
						} 
						else
						{
							$s_i2 = NULL;
							$s_o2 = NULL;
							$late = null;
							$under = null;
							$hd = null;
							$remark = 'RD';
						}
					}	
				}
				else
				{
					$late = null;
					$under = null;
					$remark = 'NS';
				}
				if ($remark == 'E'){
					$tdstyle=" style='background-color:pink;'";
				}else{
					$tdstyle="";
				}
			?>
			<td class="numeric"><center><div <?php echo $tdstyle;?> > <?php echo $late; ?></div></td>
			<td class="numeric"><center><div <?php echo $tdstyle;?> > <?php echo $under; ?><div></td>
			<td class="numeric"><center><div <?php echo $tdstyle;?> > <?php echo $remark; ?></div></td>
			<?php
							
				$late = null;
				$absent = null;
				$under = null;
				$over = null;
				$remark = null;
				$l_i2 = null;
				$l_o2 = null;
				$s_i2 = null;
				$s_o2 = null;	
			?>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div><div class="clear"></div>
</div>
</div><div class="clear"></div>
<br>
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
 <table>
			<tr>
			<th>Symbol</th>
			<th>Description</th>
			</tr>
			<tr>
			<td>A</td>
			<td>Absent</td>
			</tr>
			<tr>
			<td>RD</td>
			<td>Restday</td>
			</tr>
			<tr>
			<td>SL</td>
			<td>Sick leave</td>
			</tr>
			<tr>
			<td>VL</td>
			<td>Vacation Leave</td>
			</tr>
			<tr>
			<td>RH</td>
			<td>Regular Holiday</td>
			</tr>
			<tr>
			<td>SH</td>
			<td>Special Holiday</td>
			</tr>
			<tr>
			<td>NS</td>
			<td>No Schedule</td>
			</tr>
			<tr>
			<td>E</td>
			<td>Error</td>
			</tr>
		</tr>
		</table>

<br>

