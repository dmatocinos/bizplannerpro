<?php
class activate_lib
{
	public $allmsgs = array();
	var $color =  array();
	var $global = '';
	var $DONE = false;
	
	function __construct(){
		
		$this->db = new Database();
		$this->global_func = new global_lib();
	}
	
	public function activateProcess($table, $setColumns, $where, $s_user)
	{	
		
		$updateTable =	$this->db->update($table, $setColumns, $where);
		if($updateTable)
		{
			//fully verified !!!
			if($this->sendSuccessfulActivationEmailToPlayer($s_user))
			{
				
				
				if($table == PLAYER_TABLE)
				{
					$getUserData = $this->db->select("*", $table, $where, "", "");
					// At this point this user is verified and has been activae	
					$_SESSION['verifiedTable'] = 	$table;
					$_SESSION['verifiedUser'] = 	$getUserData[0]['email'];
					$_SESSION['verifiedUserId']  = 	$getUserData[0]['p_id'];
					$redirectTo = URL_ACTIVATE_PLAYER; 
				}
				else if($table == COACH_TABLE)
				{
					$getUserData = $this->db->select("*", $table, $where, "", "");
					// At this point this user is verified and has been activae	
					$_SESSION['verifiedTable'] =	$table;
					$_SESSION['verifiedUser'] = 	$getUserData[0]['email'];
					$_SESSION['verifiedUserId']  = 	$getUserData[0]['c_id'];
					$redirectTo = URL_ACTIVATE_COACH; 
				}
				else
				{ 
				   $this->allmsgs[] = 'CANNOT ACTIVATE USER. <br />TABLE IS NOT SPEICIFIED.'; $this->color[] = 'red'; 
				   return $this->displayAllMsgs($this->allmsgs, $this->color);
					exit();
				}
				
				header("Location:".URL_SUCCESS_PAGE);
			}
			else
			{
				$allmsgs[] = "Could not send Successful email"; $color = "red";
			}
		}
		else
		{
			 $allmsgs[] = '<p>The system could not activate you. Please contact your Administrator</p>'; $color = "red";
		}
		
		return $this->DisplayAllMsgs($allmsgs, $color);
	}
	
	
	public function reActivate($user, $table)
	{
		$re_act_date = $this->global_func->getLondonDateTimeZone();
		$re_act_code = $this->global_func->generateMd5Code($user, $re_act_date);
				
		if($table == PLAYER_TABLE)
		{
			$setColumns = "activate_code = '$re_act_code'";
			$where = " email = '$user'";
		}
		else if($table ==  COACH_TABLE)
		{
			$setColumns = "activate_code = '$re_act_code'";
			$where = " email = '$user'";
		}
		/*else if($table == ADMIN_TABLE)
		{
			$setColumns = "activate_code = '$re_act_code'";
			$where = " email = '$user'";
		}
		*/
		else
		{
			$this->allmsgs = "There is an error in selecting table."; $this->color =  "red";
			return $this->displayAllMsgs($this->allmsgs, $this->color);
			//exit();
		}
		
		$updateTable =	$this->db->update($table, $setColumns, $where);
		if($updateTable)
		{
			if($this->sendEmailToUser($user, $re_act_code))
			{
				$redirectTo ='';
				// SET THE AMOUNT OF TIME IT WILL TAKE FOR THE SESSION TO TIME OUT FROM RE-ACTIVATING
				$this->global_func->startNewSession(10, $table);
				$_SESSION['act_code'] = $re_act_code;
				$_SESSION['useremail'] = $user;
				$_SESSION['table'] = $table;
				

				if($table == 'players'){$redirectTo = URL_ACTIVATE_PLAYER; }
				else if($table == 'coaches'){$redirectTo = URL_ACTIVATE_COACH; }
				else
				{ 
				   $this->allmsgs = 'CANNOT RE-ACTIVATE USER. <br />TABLE IS NOT SPEICIFIED.'; $color = 'red'; 
				   return $this->displayAllMsgs($this->allmsgs, $color);
				}
				header("Location:".$redirectTo);
			}
			else 
			{
				$this->allmsgs = "Could not send re-activate email."; $this->color =  "red";
				//exit();
			}
		}
		return $this->displayAllMsgs($this->allmsgs, $this->color);
	}
	
	
	public function activateCoachFinalStage($table, $userId, $activate)
	{
		$setColumns = "final_activate = ". $activate ;
		$where = 'c_id = '. $userId;	
		if($table == COACH_TABLE)
		{
			$updateTable =	$this->db->update($table, $setColumns, $where);
			if($updateTable)
			{
				return true;
			}
			else
			{
				$this->allmsgs[] = 'The system could not complete a final activation for Useryou.<br /> Please contact your Administrator.'; 
				$this->color = "red";
			}
		}
		else
		{
			$this->allmsgs[] = "Table cannot be selected."; $this->color =  "red";
		}
		return $this->displayAllMsgs($this->allmsgs, $this->color);
	}
	
	
	
	public function DisplayAllMsgs($arg1, $arg2)
	{
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
	
	private function sendEmailToUser($verified_email,  $act_code)
	{
	
		$activ_message = "Your activation code  is ".$act_code;
		$usename_message = "\nYour username  is ".$verified_email;
		
		$to = $verified_email;
		$from = 'RegisterPlayer@oneraceonefinishline.org';
		$subject = "Activate your account";
		$message = $activ_message.$usename_message;
		
		 return $this->global_func->sendEmail($to, $from, '', $subject, $message);
	}
	
	private function sendSuccessfulActivationEmailToPlayer($email)
	{
		$message1 = 'You have succesfully registered with One Race One Finsih Line ';
		$message2 =	"\nTo upadete your information please use this link http://www.oneraceonefinishline.org/update/";
		
		$to = $email;
		$from = 'RegisterPlayer@oneraceonefinishline.org';
		$subject = "Successful Registeration with One Race One Finish Line";
		$message = $message1. $message2;
		
		return $this->global_func->sendEmail($to, $from, '', $subject, $message);
	}
	
}
?>