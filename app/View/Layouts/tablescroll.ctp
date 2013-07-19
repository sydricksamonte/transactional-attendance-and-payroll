<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'Imperium Attendance System');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('960');
		echo $this->Html->css('defaultTheme');
		echo $this->Html->css('myTheme.css');
		echo $this->Html->script('bootstrap-dropdown');
		echo $this->Html->script('jquery.min.js');
		echo $this->Html->script('jquery.fixedheadertable.js');
		echo $this->Html->script('demo.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>
<body>
	<div class="progress">
		<div class="bar" style="padding:12px;width: 100%;margin-top:0;text-align:left"><?php echo $this->Html->image('logo.png',array('alt'=>'CAKEPHP'))?>
		</div>
	</div>
	
	<div class="navbar">
        <div class="navbar-inner">
            <ul class="nav">
                  <li><div style="padding:10px;"><?php echo $this->Html->image('users.png')."  ";?><?php echo $this->Html->link('Employees',array('controller'=>'Employees','action' => 'index'))?>
				  <ul>
					<li><?php echo $this->Html->link('Add Employee',array('controller' => 'Employees','action' => 'add_employee'))?></li>
				  </ul>
					</div></li>
				  <li class="divider-vertical"><div style="padding:10px;"><?php echo $this->Html->image('calendar-search-result.png')."  ";?><?php echo $this->Html->link('Shifts',array('controller' => 'Schedules', 'action' => 'index'))?>
				  				  <ul>
					<li><?php echo $this->Html->link('Shifting Rules',array('controller' => 'Schedules','action' => 'rule'))?></li>
					<li>-</li>
					<li><?php echo $this->Html->link('Add Schedules',array('controller' => 'Schedules','action' => 'add'))?></li>
				  </ul>
				  </div></li>
                  <li><div style="padding:10px;"><?php echo $this->Html->image('clipboard--plus.png')."  ";?><?php echo $this->Html->link('Holidays',array('controller'=>'Holidays','action'=>'index'))?>
				  <ul>
					<li><?php echo $this->Html->link('Add Holiday', array('controller'=>'Holidays', 'action'=>'add'))?></li>
				  </ul>
					</div></li>
                  <li class="divider-vertical"><div style="padding:10px;"><?php echo $this->Html->image('application-search-result.png')."  ";?><?php echo $this->Html->link('Groups',array('controller' => 'Groups', 'action' => 'index'))?>
				  <ul>
					<li><?php echo $this->Html->link('Add Group',array('controller' => 'Groups', 'action' => 'add'))?></li>
				  </ul>
					</div></li>
                  <li><div style="padding:10px;"><?php echo $this->Html->image('users.png')."  ";?><?php echo $this->Html->link('Users', array('controller'=>'Users', 'action'=>'index'))?>
				  <ul>
					<li><?php echo $this->Html->link('Add User', array('controller'=>'Users', 'action'=>'add'))?></li>
				  </ul>
					</div></li>
                  <li class="divider-vertical"><div style="padding:10px;"><?php echo $this->Html->image('calendar.png')."  ";?><?php echo $this->Html->link('Cutoffs', array('controller'=>'Cutoffs', 'action'=>'view'))?></div></li>
				  <li><div style="padding:10px;"><?php echo $this->Html->image('address-book--plus.png')."  ";?><?php echo $this->Html->link('Update Logsheet',array('controller'=>'Employees','action'=>'upload'))?></div></li>
				  <li class="divider-vertical"><div style="padding:10px;"><?php echo $this->Html->image('address-book.png')."  ";?><?php echo $this->Html->link('Loan', array('controller'=>'Loans', 'action'=>'emp_loan'))?></div></li>
				  <li><div style="padding:10px;"><?php echo $this->Html->image('payslip.png')."  ";?><?php echo $this->Html->link('Payslip',array('controller' => 'Totals', 'action' => 'gotopayslip'))?></div></li>
				  <li class="divider-vertical"><div style="padding:10px;"><?php echo $this->Html->image('manpower.png')."  ";?><?php echo $this->Html->link('ManPower',array('controller' => 'Employees', 'action' => 'select_manpower'))?>
				  <ul>
					<li><?php echo $this->Html->link('Manpower Range',array('controller' => 'Employees','action' => 'select_manpower_range'))?></li>
				  </ul>
				  </div></li>
			</ul>
			<div style="padding:10px;" align="right"><?php echo $this->Html->image('out.png',array('url'=>array('controller' => 'Users', 'action' => 'logout')))."  ";?><?php echo $this->Html->link('Logout',array('controller' => 'Users', 'action' => 'logout'))?></div>
        </div>
    </div>
	<div id="content" style="width:100%;">
		<div style="width:98%"><?php echo $this->Session->flash(); ?></div>
		<?php echo $this->fetch('content'); ?>
	</div>
	<div id="footer">
		<?php /*echo $this->Html->link(
			$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
				*/
			?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
