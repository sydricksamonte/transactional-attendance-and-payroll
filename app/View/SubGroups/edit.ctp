<h3>SubGroup</h3>
<div class="formstyle">
<?php
    echo $this->Form->create('SubGroup', array('action' => 'edit'));
		echo $this->Form->input('name');
		echo $this->Form->input('authorize', array('label' => 'Valid'));
		echo $this->Form->input('id', array('type' => 'hidden'));
		echo $this->Form->end('Save', array('action' => 'edit'));
?>
</div>

