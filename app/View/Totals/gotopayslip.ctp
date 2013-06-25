<h3>Payslip</h3>
<div class="formstyle">
<?php
echo $this->Form->create('Total');
echo $this->Form->input('Cutoff List',array('type'=>'select','options'=>$cutoffList));
echo "<br><center>";
echo $this->Form->end('View Payslip');
debug ($this->data);
?>
</div>
