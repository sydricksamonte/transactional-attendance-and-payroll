<h3>Update Logsheet</h3>
<div class="formstyle">
<?php 
echo $this->Form->create('Upload', array('type' => 'file'));
echo $this->Form->input('file', array('type' => 'file', 'label' => false));
echo "<br><center>";
echo $this->Form->end("Upload file");
?>
</div>