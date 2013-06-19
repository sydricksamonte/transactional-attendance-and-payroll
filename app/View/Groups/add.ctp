<h3>New Group</h3>
<div class="formstyle">

<?php
echo $this->Form->create('Group');
echo $this->Form->input('name');
echo $this->Form->input('authorize', array('value' => '1', 'type' => 'hidden'));
echo $this->Form->end('Save Group');
?>
</div>
<!--<a href="javascript:window.history.back()">Back</a>-->
