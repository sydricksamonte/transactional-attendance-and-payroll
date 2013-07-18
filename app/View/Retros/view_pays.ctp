<?php
		function formatAmount($amount)
		{
			return number_format($amount, 2, '.', ',');  
		}
?>
<div class="btn"><h4>
<?php #echo $employee['Employee']['last_name'].', '.$employee['Employee']['first_name']; ?>
<?php echo $this->Html->link( $employee['Employee']['last_name'].', '.$employee['Employee']['first_name'], array('controller'=>'Employees','action' => 'view_emp', $employee['Employee']['id'])) ?>
</h4></div>
<br><br>
<table style="width:98%">
<thead>
<tr>
	<th>Type of Pay</th>
	<th>Taxable</th>
	<th>Percentage</th>
	<th>Amount</th>
	<th>Total Amount</th>
	<th>Bonus/Deduction</th>
	<th>Status</th>
	<th></th>
</tr>
</thead>
<tbody>
  <?php
	$c=0;
	foreach ($pays as $p): 
	$c++;
  ?>
	<tr>
		<td style="vertical-align:middle;">
			<?php echo $c.'. '.$p['Retro']['type'];?>
		</td>
		<td style="vertical-align:middle;">
			<?php 
				if ($p['Retro']['pay_type']=="add"){
					if ($p['Retro']['taxable'] == 1){
						echo "Taxable";
					}
					else{
						echo "Non-Taxable";
					}
				}else{
					echo "-";
				}
				
			?>
		</td>
		<td style="vertical-align:middle;">
			<?php 
			if ($p['Retro']['pay_type']=="add"){
				echo $p['Retro']['percent'].'%';
			}else{
				echo "-";
			}
			?>
		</td>
		<td style="vertical-align:middle;">
			<?php echo 'P '.formatAmount($p['Retro']['retropay']);?>
		</td>
		<?php
		$taxa= $p['Retro']['taxable'];
		
		if (strtolower($taxa) == 1){
			$perc='.'.$p['Retro']['percent'];
			$retropay=$p['Retro']['retropay'];
			$retropay=$retropay-($retropay * ($perc));
		}else{
			$retropay=$p['Retro']['retropay'];
		}
		?>
		<td style="vertical-align:middle;">
			<?php echo 'P '.formatAmount($retropay);?>
		</td>
		<td style="vertical-align:middle;">
			<?php
				if ($p['Retro']['pay_type'] == "add"){
					echo "Bonus";
				}
				else{
					echo "Deduction";
				}
			?>
		</td>
		<td style="vertical-align:middle;">
			<?php
				if($p['Retro']['status']==0){
					echo "Invalid";
				}
				else{
					echo "Valid";
				}
			?>
		</td>
		<td style="vertical-align:middle;">
		<div class="btn">
			<?php echo $this->Html->link('Edit',array('action' => 'edit',$p['Retro']['emp_id'], $p['Retro']['id'],$p['Retro']['cutoff_id']));?>
		</div>
		</td>

	</tr>
<?php endforeach;?>
</tbody>
</table>


