<h2>Employee List</h2>
<div class='actions'>
</div>
<div class="sp1">
 <table width=1080 class="table table-striped">
  <thead>
  <tr>
      <th width=153>Employee ID</th>
      <th width=540>Employee Name</th>
      <th width=147>Status</th>
    </tr>
  <?php foreach ($subGroupEmp as $group): ?>
  <tbody>
    <tr>
        <td><?php echo $group['Employee']['id']; ?></td>
         <td><?php echo  $this->Html->link($group['Employee']['last_name'] . ', '. $group['Employee']['first_name'],array('controller' => 'employees', 'action' => 'edit', $group['Employee']['id']));  ?></td>
        <td><?php if ($group['Employee']['employed']=='1'){echo 'Employed';} else {echo 'Resigned';}; ?></td>
   </tbody>
    </tr>
<?php endforeach; ?>
  </table></div></div>
<div class="btn" style="margin-left:40px;">
<a href="javascript:window.history.back()"><b><--Back</b></a>
</div>	
