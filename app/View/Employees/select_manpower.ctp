  <h3>Select Day</h3>
<div class="formstyle">
<?php //debug($groups);
 ?>

<?php
  echo $this->Form->create('CutOff');
  echo $this->Form->input('cut_use',array('label'=>'Cut off date','type' => 'date'));
  echo "<br><center>";
?>
<?php echo $this->Form->end('Go'); ?>
</div>
<br>
<div class="btn" style="margin-left:40px;">
<a href="javascript:window.history.back()"><b><--Back</b></a>
</div>	
