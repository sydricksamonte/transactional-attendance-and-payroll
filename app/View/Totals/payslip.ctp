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

<?php foreach($empSal as $empS): ?>
<div class="classdate">
<?php

$mth= date('n', strtotime( $empS['CO']['end_date']));
$yr= date('Y', strtotime( $empS['CO']['end_date']));
$d= date('j', strtotime( $empS['CO']['end_date']));
$num = cal_days_in_month(CAL_GREGORIAN, $mth, $yr); 
	if ($d == 25){
		$cutoffdate= date('F', strtotime( $empS['CO']['end_date'])).' 15-'.$num.' '.date('Y', strtotime( $empS['CO']['end_date']));
		}
	else{
		$cutoffdate= date('F', strtotime( $empS['CO']['end_date'])).' 1-15 '.date('Y', strtotime( $empS['CO']['end_date']));
		}
#echo date('F j',strtotime($empS['CO']['start_date'])).date('-j Y', strtotime($empS['CO']['end_date']));
?>

<table class='tablei'>
<tr><td>
<div class="bar" style="margin-top:0;text-align:left"><?php echo $this->Html->image('nameimper.png',array('alt'=>'CAKEPHP'))?></div>
	<table cellpadding=3 class='tableinfo'>
	<tr>
		<td colspan=2 style="border:1px solid black; background-color:rgb(187, 180, 180)">Date: <?php echo $cutoffdate;?></td>
	</tr>
	<tr >
		<td style="border:1px solid black;background-color:rgb(187, 180, 180)">Name: <?php echo $empS['Emp']['last_name'].', '.$empS['Emp']['first_name']?></td>
		<td style="border:1px solid black;background-color:rgb(187, 180, 180)">Position: </td>
	</tr>
	<tr>
		<td style="border-right:1px solid black">Tax Code: <?php echo $empS['Gov']['name']?></td>
		<td>Deduction: <?php echo  formatAmount($empS['Total']['deductions']+$empS['Total']['sss']+$empS['Total']['phil_health']+$empS['Total']['pagibig']+$empS['Total']['tax'])?></td>
	</tr>
	<tr>
		<td style="border-right:1px solid black">Basic Salary: <?php $basics= Security::cipher($empS['Emp']['monthly'], 'my_key'); echo formatAmount($basics/2);?></td>
		<td>Absences/Tardiness: <?php echo  formatAmount($empS['Total']['deductions']);?></td>
	</tr>	
	<tr>
		<td style="border-right:1px solid black">Night Differential: <?php echo formatAmount($empS['Total']['night_diff']);?></td>
		<td>Company Advances: <?php echo  formatAmount($empS['Total']['att_bonus'])?></td>
	</tr>
	<tr>
		<td style="border-right:1px solid black">Over Time: <?php echo formatAmount($empS['Total']['OT']);?></td>
		<td>PhilHealth: <?php echo  formatAmount($empS['Total']['phil_health'])?></td>
	</tr>
	<tr>
		<td style="border-right:1px solid black">Attendance Bonus: <?php echo formatAmount($empS['Total']['att_bonus']);?></td>
		<td>Home Mutual Dev't Fund: <?php echo  formatAmount($empS['Total']['pagibig'])?></td>
	</tr>
	<tr>
		<td style="border-bottom:1px solid black;border-right:1px solid black;">Holiday Pay: <?php echo  formatAmount($empS['Total']['holiday']);?></td>
		<td>SSS: <?php echo  formatAmount($empS['Total']['sss'])?></td>
	</tr>
	<tr>
		<td style="border-right:1px solid black;border-bottom:1px solid black;background-color:rgb(187, 180, 180)" rowspan=3>NETPAY: <?php $netpay=$empS['Total']['net_pay']; echo 'P '.formatAmount($netpay); ?></td>
		<td style="border-bottom:1px solid black">Witholding Tax: <?php echo  formatAmount($empS['Total']['tax'])?></td>
	</tr>
	<tr>
		<td>SSS Loan: <?php echo formatAmount($empS['Total']['sss_loan'])?></td>
	</tr>
	<tr>
		<td  style="border-bottom:1px solid black">HMDF Loan: <?php echo formatAmount($empS['Total']['hmdf_loan'])?></td>
	</tr>
	<?php

	/*Code for additional pays and deductions*/
		foreach ($others as $other):
		if ($other['Retro']['status']==1){
		echo "";
			if ($empS['Emp']['id'] == $other['Retro']['emp_id']){
				echo "<td></td><td style='border-left:1px solid black;border-bottom:1px solid black'>".$other['Retro']['type'].": ";
				if ($other['Retro']['pay_type']=='deduct'){
					echo "-".$other['Retro']['retropay']."</td>";
				}else{
				$taxa= $other['Retro']['taxable'];
				
					if (strtolower($taxa) == 1){
						$perc='.'.$other['Retro']['percent'];
						$retropay=$other['Retro']['retropay'];
						$retropay=$retropay-($retropay * ($perc));
					}else{
						$retropay=$other['Retro']['retropay'];
					}
					
					echo "".$retropay."</td>";
				}
			}
		echo "</tr>";
		}
		endforeach;
	/*end of code for additions and deductions*/
	
?>
	</table>
</td>
</tr>
</table>
<br>
<?php endforeach; ?>
</div>
<br>
<a href="javascript:window.history.back()"><b>Back</b></a>