<?php #debug($holi);?>
	<h2>Holidays</h2>
	<div class="actions">
		<?php
		echo $this->Form->create("Holiday", array('action'=>'search'));
		echo $this->Form->input("search",array('class'=>'input-medium search-query','label'=>'Search','type'=>'text'));
		echo $this->Form->end("Search");
		?>
	</div>
			<table style="width:90%">
			<tr>
			<td>
			<table width=1183>
			<thead>
			<tr>
					<th width=63>ID</th>
					<th width=340>Name</th>
					<th width=321>Date</th>
					<th width=171>Status</th>	
					<th width=185></th>
			</tr>
			</thead>
			</table>
			</td>
			</tr>
			<tr>
			<td>
			<div class="span3">
			<table width=1183>
			<?php foreach ($holi as $h):?>
			<tbody>
				<tr>
					<td width=63><?php echo $h['Holiday']['id'];?></td>
					<?php #echo $this->Html->link($h['Holiday']['name'],array('action'=>'view',$h['Holiday']['id']));?>
					<td width=340><?php echo $h['Holiday']['name'];?></td>
					<td width=321><?php echo date('M d, Y',strtotime($h['Holiday']['date']));?></td>
				  <td width=171><?php  if  ($h['Holiday']['authorize'] == '1'){echo 'Valid';} else {echo 'Invalid';} ?></td>
					<td width=185><div class="colorw"><div class="btn btn-info" ><?php echo $this->Html->link('Edit',array('action'=>'edit',$h['Holiday']['id']));?></div></td>
				</tr>		</tbody>
			<?php endforeach;?>
  </table></div></td></tr></table>
