<br><br>
<div class="alert alert-success">
  <b>Success!! Weeks are Updated.. Thank You.!</b>
</div>
<?php
	echo '<div class="colorw"><div class="btn btn-warning">'.$this->Html->link('DONE',array('controller'=>'Employees','action' => 'index')).'</div></div>';
?>
<br>
	<table class="table table-bordered">
	<tr><th>Week NO.</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
		<?php
			foreach ($wks as $wk):
			echo '<tr>
			  <td>'.$wk['Week']['week_no'].'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['monday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['tuesday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['wednesday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['thursday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['friday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['saturday'])).'</td>
				<td>'.date("M.d, Y", strtotime($wk['Week']['sunday'])).'</td>
			</tr>';
			endforeach;
			?>
</table>
