
	Employee ID: &nbsp;&nbsp;&nbsp;
			<b><?php echo $empname['Employee']['id']; ?></b>
		<?php echo '<br><br>Name: &nbsp;&nbsp;&nbsp;<b>'.$empname['Employee']['last_name'].', '.$empname['Employee']['first_name'].'</b>';?>
		<br><br>Group: &nbsp;&nbsp;&nbsp;
			<b><?php echo $empname['SubGroup']['name']; ?></b>
		<br><br>Status: &nbsp;&nbsp;&nbsp;
			<b><?php if ($empname['Employee']['employed'] == 'true'){echo 'Employed';} else {echo 'Resigned';}?></b>

			<br><br>
	<table style="width:50%">
	<tr>
	<td>
	<table>
	<thead>
	<tr>
			  <th width=200>Loan Type</th>
			  <th width=250>Amount</th>
			  <th width=200>Start Date</th>
			  <th width=200>End Date</th>
  		      <th width=200></th>
			</tr>
			</thead>
			</table>
			</td>
			</tr>
			<tr>
			<td>
			<div class="span3">
			<table style="width:100%">
			<?php
				foreach ($emploans as $loan):
				$loanType= $loan['Loan']['loan_type'];
				echo "<tr>";
				if($loanType==0){
					echo '<td style="width:20%">SSS</td>
						 <td>'.$loan['Loan']['amount'].
						 '</td><td style="width:30%;text-align:right">'.$loan['Loan']['start_date'].
						 '</td><td style="width:30%;text-align:center">'.$loan['Loan']['end_date'].'</td>';
				}else if($loanType==1){
					echo '<td style="width:20%">HMDF </td><td>'.$loan['Loan']['amount'].
						 '</td><td style="width:30%;text-align:right">'.$loan['Loan']['start_date'].
						 '</td><td style="width:30%;text-align:center">'.$loan['Loan']['end_date'].'</td>';
			}
echo '<td style="width:20%;text-align:center"><div class="colorw"><div class="btn btn-info" style="height:15px">'.$this->Html->link('Edit',array('controller'=>'Loans','action' => 'edit_emp_loan_loan', $empname['Employee']['id'],$loan['Loan']['id'])).'</div></div></td>';
echo '</tr>';
	endforeach;
?>
</table>
</table>