<?php
	$today = strtotime(date("M.d, Y"));
?>
	<table class="table table-bordered" style="width:98%">
	<tr><th>Week NO.</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th><th>Generated</th></tr>
		<?php
			foreach ($wk as $wk):
			$outmonday=""; $outtuesday=""; $outwednesday=""; $outthursday=""; $outfriday=""; $outsaturday=""; $outsunday="";
			$monday = date("M.d, Y", strtotime($wk['Week']['monday']));
			$tuesday = date("M.d, Y", strtotime($wk['Week']['tuesday']));
			$wednesday = date("M.d, Y", strtotime($wk['Week']['wednesday']));
			$thursday = date("M.d, Y", strtotime($wk['Week']['thursday']));
			$friday = date("M.d, Y", strtotime($wk['Week']['friday']));
			$saturday = date("M.d, Y", strtotime($wk['Week']['saturday']));
			$sunday = date("M.d, Y", strtotime($wk['Week']['sunday']));
			
				if (strtotime($monday) <= $today){ $outmonday="<font color='red'>"; }
				if (strtotime($tuesday) <= $today){ $outtuesday="<font color='red'>"; }
				if (strtotime($wednesday) <= $today){ $outwednesday="<font color='red'>"; }
				if (strtotime($thursday) <= $today){ $outthursday="<font color='red'>"; }
				if (strtotime($friday) <= $today){ $outfriday="<font color='red'>"; }
				if (strtotime($saturday) <= $today){ $outsaturday="<font color='red'>"; }
				if (strtotime($sunday) <= $today){ $outsunday="<font color='red'>"; }
				
				echo '<tr><td>'.$wk['Week']['week_no'].'</td>
						<td>'.$outmonday.$monday.'</td>
						<td>'.$outtuesday.$tuesday.'</td>
						<td>'.$outwednesday.$wednesday.'</td>
						<td>'.$outthursday.$thursday.'</td>
						<td>'.$outfriday.$friday.'</td>
						<td>'.$outsaturday.$saturday.'</td>
						<td>'.$outsunday.$sunday.'</td>
						<td>'.$wk['Week']['generated'].'</td></tr>';
				
			endforeach;
			?>
</table>
