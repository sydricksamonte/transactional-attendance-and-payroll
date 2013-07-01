<h3>Update Logsheet</h3>
<div class="formstyle">
<?php 
echo $this->Form->create('Upload', array('type' => 'file'));
echo $this->Form->input('file', array('type' => 'file', 'label' => false));
echo "<br><center>";
echo $this->Form->end("Upload file");
?>
</div>
<br>
<div class="btn" style="margin-left:40px;">
<a href="javascript:window.history.back()"><b><--Back</b></a>
</div>	