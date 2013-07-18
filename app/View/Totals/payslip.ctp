<a href="javascript:window.history.back()"><b>Back</b></a>
<br>
<br>
<div style="font-size:10px">
<?php
function formatAmount($amount)
{
	return number_format($amount, 2, '.', ',');
}
?>
<?php foreach($empSal as $empS):?>
<?php
$mth= date('n', strtotime( $empS['Cutoff']['end_date']));
$yr= date('Y', strtotime( $empS['Cutoff']['end_date']));
$d= date('j', strtotime( $empS['Cutoff']['end_date']));
$num = cal_days_in_month(CAL_GREGORIAN, $mth, $yr); 
	if ($d == 25){
		echo date('F', strtotime( $empS['Cutoff']['end_date'])).' 15-'.$num.' '.date('Y', strtotime( $empS['Cutoff']['end_date']));
		}
	else{
		echo date('F', strtotime( $empS['Cutoff']['end_date'])).' 1-15 '.date('Y', strtotime( $empS['Cutoff']['end_date']));
		}
#echo date('F j',strtotime($empS['Cutoff']['start_date'])).date('-j Y', strtotime($empS['Cutoff']['end_date']));
?>
<table>
<tr><td valign="top">
	<table >
<tr><hr>
	<td>Name</td>
	<td><?php echo $empS['Employee']['last_name'].', '.$empS['Employee']['first_name']?></td>
</tr>
<tr>
	<td>Position</td>
	<td><?php echo $empS['Group']['name']?></td>
</tr>
<tr>
	<td>Tax Code</td>
	<td><?php echo $empS['Govstat']['name']?></td>
</tr>
<tr>
	<td colspan=2><center> Salary Details</td>
</tr>
<tr>
	<td>Basic Salary</td><td><?php $basics= Security::cipher($empS['Employee']['monthly'], 'my_key'); echo formatAmount($basics/2);?></td>
</tr>
<tr>
	<td>Night Differential</td><td><?php echo formatAmount($empS['Total']['night_diff']);?></td>
</tr>
<tr>
	<td>Over Time</td><td><?php echo formatAmount($empS['Total']['OT']);?></td>
</tr>
<tr>
	<td>Attendance Bonus</td><td><?php echo formatAmount($empS['Total']['att_bonus']);?></td>
</tr>
<tr>
	<td>Holiday Pay</td><td><?php echo  formatAmount($empS['Total']['holiday']);?></td>
</tr>
	</table>
</td><td valign="top">
	<table >
<tr><hr>
	<td>Deduction</td><td><?php echo  formatAmount($empS['Total']['deductions']+$empS['Total']['sss']+$empS['Total']['phil_health']+$empS['Total']['pagibig']+$empS['Total']['tax'])?></td>
</tr>
<tr>
	<td>Absences / Tardiness</td><td><?php echo  formatAmount($empS['Total']['deductions']);?></td>
</tr>
<tr>
	<td>Company Advances</td><td><?php echo  formatAmount($empS['Total']['att_bonus'])?></td>
</tr>
<tr>
	<td>SSS</td><td><?php echo  formatAmount($empS['Total']['sss'])?></td>
</tr>
<tr>
	<td>PhilHealth</td><td><?php echo  formatAmount($empS['Total']['phil_health'])?></td>
</tr>
<tr>
	<td>Home Mutual Dev't Fund</td><td><?php echo  formatAmount($empS['Total']['pagibig'])?></td>
</tr>
<tr>
	<td>Witholding Tax</td><td><?php echo  formatAmount($empS['Total']['tax'])?></td>
</tr>
<tr>
	<td>SSS Loan</td><td><?php echo formatAmount($empS['Total']['sss_loan'])?></td>
</tr>
<tr>
	<td>HMDF (Pagibig) Load</td><td><?php echo formatAmount($empS['Total']['hmdf_loan'])?></td>
</tr>
<?php

	/*Code for additional pays and deductions*/
		foreach ($others as $other):
		if ($other['Retro']['status']==1){
		echo "<tr>";
			if ($empS['Employee']['id'] == $other['Retro']['emp_id']){
				echo "<td>".$other['Retro']['type']."</td>";
				if ($other['Retro']['pay_type']=='deduct'){
					echo "<td>-".$other['Retro']['retropay']."</td>";
				}else{
				$taxa= $other['Retro']['taxable'];
				
					if (strtolower($taxa) == 1){
						$perc='.'.$other['Retro']['percent'];
						$retropay=$other['Retro']['retropay'];
						$retropay=$retropay-($retropay * ($perc));
					}else{
						$retropay=$other['Retro']['retropay'];
					}
					
					echo "<td>".$retropay."</td>";
				}
			}
		echo "</tr>";
		}
		endforeach;
	/*end of code for additions and deductions*/
	
?>
	</table>
</td></tr>
<tr>
<?php #debug($empS['Total']['net_pay'], 'my_key');
		$netpay=$empS['Total']['net_pay']; ?>
<td></td><td ><u>Net Pay: <?php echo 'P '.formatAmount($netpay); ?><td>
</tr>
</table>
</center>
<hr>
<?php endforeach; ?>
</div>
<br>
<a href="javascript:window.history.back()"><b>Back</b></a>