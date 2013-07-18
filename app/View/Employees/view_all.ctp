<input type="button" value="Back" onclick="window.history.back()">
<table><tr style="height:0px"><td style="height:0px">
<?php
$i = 0;
echo 'CUT OFF PERIOD FROM '. $startCut.' TO '.$endCut;
foreach($employees as $data):{
				$id = $data['Employee']['id'];
				$tr  .= "<td colspan=-1><iframe frameborder=0 type=hidden width=0% height=0 src=/taps/Employees/view_all2/$id/$dateId>test</iframe> <td>";

				}endforeach;
?>
</td></tr><tr><td>
<table>
	<?php if ($redir_in == '1'){ ?> 
	<tr>
	
	<?=$tr;
		$all = "<tr><td colspan=16><iframe frameborder=0  height=2048 width=1750 src=/taps/Totals/index/$dateId>test</iframe> <td></tr>";
		echo $all;
	?>
	<?php } 
	else { ?>
	<?=$tr;
		$all = "<tr><td colspan=16><iframe frameborder=0  height=2048 width=1750 src=/taps/Totals/payslip/$dateId>test</iframe> <td></tr>";
		echo $all;
	?>
	<?php } ?>
</table>
</td></tr></table>