<?php #echo $this->Session->flash('auth'); ?>
<div class="formstyle" style="width:28%;margin-left:275px;">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php #echo __('Please enter your username and password');
											echo __('Login'); ?></legend>
        <?php echo $this->Form->input('username');
        echo $this->Form->input('password');
		
    ?>
    </fieldset>
<div class="actions">
<?php echo $this->Form->end(__('Login')); ?>

</div>
</div>