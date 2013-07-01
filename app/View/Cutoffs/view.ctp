  <h3>Generate Cutoff</h3>
<div class="formstyle">
<?php //debug($groups);
 ?>

<?php
  echo $this->Form->create('CutOff', array( 'onsubmit' => 'alert("Generating... Press OK");'));
  echo $this->Form->input('cut_use',array('label'=>'Cut off end date','type' => 'select','selected' => 0, 'options' => $total));
  echo "<br><center>";
?>
<?php echo $this->Form->end('Generate'); ?>
</div>
<br>
<div class="btn" style="margin-left:40px;">
<a href="javascript:window.history.back()"><b><--Back</b></a>
</div>	
