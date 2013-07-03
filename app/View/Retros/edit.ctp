<?php
$taxval=false;
$vals=false;
echo $this->Form->create('Retro');
#echo $this->Form->input('Employee',array('type'=>'text','value'=>$employee['Employee']['first_name'].' '.$employee['Employee']['last_name']));
echo '<h4><u>'.$employee['Employee']['first_name'].' '.$employee['Employee']['last_name'].'</h4></u><br>';
echo $this->Form->input('retropay',array('type'=>'text','label'=>'Amount','value'=>$pays['Retro']['retropay']));
echo $this->Form->input('type',array('type'=>'text','label'=>'Type','value'=>$pays['Retro']['type']));
	if($pays['Retro']['taxable'] == 1){
		$taxval=true;
	}
	else{
		$taxval=false;
	}
	
echo $this->Form->input('taxable',array('type'=>'checkbox','label'=>'Taxable','checked'=>$taxval));
echo $this->Form->input('percent',array('type'=>'text','label'=>'Percentage','value'=>$pays['Retro']['percent']));
	if($pays['Retro']['status']==1){
		$vals=true;
	}
	else{
		$vals=false;
	}
echo $this->Form->input('status',array('type'=>'checkbox','label'=>'Valid','checked'=>$vals));
echo $this->Form->end("Save");
?>
