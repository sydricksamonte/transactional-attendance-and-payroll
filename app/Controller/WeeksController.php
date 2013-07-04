<?php
class WeeksController extends AppController{
				public function index(){
								$date_string = date("D Y-m-d", time());
								$week_no=date("W", strtotime($date_string));
								$wkplus1=$date_string;
								$yr=date('Y',strtotime($date_string));
								$totalwk=1;
								do{
												$mond=date('Y-m-d',strtotime('monday this week',strtotime($wkplus1)));
												$tuesd=date('Y-m-d',strtotime('tuesday this week',strtotime($wkplus1)));
												$wedn=date('Y-m-d',strtotime('wednesday this week',strtotime($wkplus1)));
												$thur=date('Y-m-d',strtotime('thursday this week',strtotime($wkplus1)));
												$frid=date('Y-m-d',strtotime('friday this week',strtotime($wkplus1)));
												$satu=date('Y-m-d',strtotime('saturday this week',strtotime($wkplus1)));
												$sund=date('Y-m-d',strtotime('sunday this week',strtotime($wkplus1)));

												$this->Week->create();
												$this->Week->set('week_no',$week_no);
												$this->Week->set('monday',$mond);
												$this->Week->set('tuesday',$tuesd);
												$this->Week->set('wednesday',$wedn);
												$this->Week->set('thursday',$thur);
												$this->Week->set('friday',$frid);
												$this->Week->set('saturday',$satu);
												$this->Week->set('sunday',$sund);
												$this->Week->set('start_date',$mond);
												$this->Week->set('end_date',$sund);
												$this->Week->set('year',$yr);
												$this->Week->save();
												$wkplus1=date('Y-m-d', strtotime('+1 week',strtotime($wkplus1)));
												$week_no=date("W", strtotime($wkplus1));
								}
								while ($week_no!=01);
								$wks=$this->Week->find('all',
								array(
									'order'=>'Week.end_date ASC'
			)
								);
								$this->set(compact('wks'));


				}
				
		public function showwk(){
				
				$wks=$this->Week->find('all', array( 'order'=>'Week.end_date ASC' ));
				$this->set(compact('wks'));
				
					$today = strtotime(date("M.d, Y"));
			
			   foreach ($wks as $wk):
					$gen= 0; $outmonday="<font color='black'>"; $outtuesday="<font color='black'>"; $outwednesday="<font color='black'>"; $outthursday="<font color='black'>"; $outfriday="<font color='black'>"; $outsaturday="<font color='black'>"; $outsunday="<font color='black'>";
					$monday = date("M.d, Y", strtotime($wk['Week']['monday']));
					$tuesday = date("M.d, Y", strtotime($wk['Week']['tuesday']));
					$wednesday = date("M.d, Y", strtotime($wk['Week']['wednesday']));
					$thursday = date("M.d, Y", strtotime($wk['Week']['thursday']));
					$friday = date("M.d, Y", strtotime($wk['Week']['friday']));
					$saturday = date("M.d, Y", strtotime($wk['Week']['saturday']));
					$sunday = date("M.d, Y", strtotime($wk['Week']['sunday']));
			
				if (strtotime($monday) <= $today){ $outmonday="<font color='red'>"; $gen=1;}
				if (strtotime($tuesday) <= $today){ $outtuesday="<font color='red'>"; $gen=1;}
				if (strtotime($wednesday) <= $today){ $outwednesday="<font color='red'>"; $gen=1;}
				if (strtotime($thursday) <= $today){ $outthursday="<font color='red'>"; $gen=1;}
				if (strtotime($friday) <= $today){ $outfriday="<font color='red'>"; $gen=1;}
				if (strtotime($saturday) <= $today){ $outsaturday="<font color='red'>"; $gen=1;}
				if (strtotime($sunday) <= $today){ $outsunday="<font color='red'>"; $gen=1;}
				
					$wkid=$wk['Week']['id'];
					
					$this->Week->read(null, $wkid);
					$this->Week->set('generated', $gen);
					$this->Week->save();
									
			endforeach;
			
				$wk=$this->Week->find('all', array( 'order'=>'Week.end_date ASC' ));
				$this->set(compact('wk'));
				
		}
}
?>

