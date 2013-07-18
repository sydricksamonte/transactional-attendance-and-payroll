<h2>Bonus Pay</h2>
<br>
<div class="formstyle" style="width:25%;">
<?php
echo $this->Form->create('Retro');
#echo $this->Form->input('Employee',array('type'=>'text','value'=>$employee['Employee']['first_name'].' '.$employee['Employee']['last_name']));
echo '<h4><u>'.$employee['Employee']['first_name'].' '.$employee['Employee']['last_name'].'</h4></u><br>';
echo $this->Form->input('pay_type',array('type'=>'hidden','label'=>'','default'=>'add'));
echo $this->Form->input('Retro Pay',array('type'=>'text','label'=>'Amount'));
echo $this->Form->input('type',array('type'=>'text','label'=>'Type'));
echo $this->Form->input('taxable',array('type'=>'checkbox','label'=>'Taxable'));
echo $this->Form->input('percent',array('type'=>'text','label'=>'Percentage','default'=>0));
echo $this->Form->input('status',array('type'=>'checkbox','label'=>'Valid'));
echo "<br><br>";
echo $this->Form->end("Save");
?>
<div class="btn"><a href="javascript:window.history.back()"><b>Back</b></a></div>
</div>