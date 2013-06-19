<h3>New User</h3>
<div class="formstyle">
<?php
echo $this->Form->create('User');
echo $this->Form->input('first_name');
echo $this->Form->input('last_name');
echo $this->Form->input('username');
echo $this->Form->input('password');
echo $this->Form->input('authorize',array('type'=>'hidden', 'value' => '1'));
echo "<br><center>";
echo $this->Form->end('Add User');
?>
</div>
