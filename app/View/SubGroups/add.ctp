<h3>SubGroup</h3>
<div class="formstyle">
<?php
    echo $this->Form->create('SubGroup', array('action' => 'edit'));
    echo $this->Form->input('name');
    echo $this->Form->input('authorize', array('value' => '1', 'type' => 'hidden'));
	echo $this->Form->input('group_id', array('value' => $group_id, 'type' => 'hidden'));   
	echo $this->Form->input('id', array('type' => 'hidden'));
	echo "<br><center>";
    echo $this->Form->end('Add', array('action' => 'edit'));
?>
</div>

