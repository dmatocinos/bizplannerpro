<?php
class login_lib
{
	public $allmsgs = array();
	var $color = array();
	var $global = '';
	var $DONE = false;
	
	function __construct(){
		
		$this->db = new Database();
		$this->global_func = new global_lib();
		
	}
	
	
	public function login($user, $p_word, $table)
	{
		$p_word = md5($p_word);
		if($table == PLAYER_TABLE)
		{
			$this->verifyPlayer($table, $user, $p_word);
		}
		else if($table == COACH_TABLE)
		{
			$this->verifyCoach($table, $user, $p_word);
		}
		else if($table == ADMIN_TABLE)
		{
			$this->verifyAdmin($table, $user, $p_word);
		}
		else
		{
			$this->allmsgs[] = "No table was selected"; 
			$this->color [] = "red";
		}
		
		//return $this->displayAllMsgs($this->allmsgs, $this->color);
	}

	
	private function verifyAdmin($table, $user, $p_word)
	{
		$where =  " email = '$user' and a_password  = '$p_word'";	
		$checkIfAdminExist = $this->db->select("*", $table, $where, "", "");
		if(count($checkIfAdminExist) > 0)
		{
			$user_id = $checkIfAdminExist[0]['a_id'];
			echo $isActivated = $checkIfAdminExist[0]['activate'];
			if($isActivated == 1)
			{
				$this->allmsgs[] = "this Administrator can Now be logged"; $this->color = "blue";
	
				// At this point this user is verified and has been activated	
				$this->global_func->startNewSession(10, $table);
				
				$_SESSION['verifiedTable'] = $table;
				$_SESSION['verifiedUser'] = $user;	
				$_SESSION['verifiedUserId']  = $user_id; 
				$_SESSION['verifiedUserMod']  = 'Admin'; 
				header("Location:".URL_SUCCESS_PAGE);
			}
			else
			{
				//$reActivate = new activate_lib();
				//$reActivate->reActivate($user, $table);
				$this->allmsgs[] = "Adminstrator is not yet activated. <br /> Contact your developer";
			}
		}
		else
		{
			$this->allmsgs[] = "Your details does not match."; $this->color =  "red";
		}
	}
	
	private function verifyPlayer($table, $user, $p_word)
	{
		$where =  " email = '$user' and password  = '$p_word'";	
		$checkIfPlayerExist = $this->db->select("*", $table, $where, "", "");
		if(count($checkIfPlayerExist) > 0)
		{
			$user_id = $checkIfPlayerExist[0]['p_id'];
			$isActivated = $checkIfPlayerExist[0]['activate'];
			if($isActivated == 1)
			{
				$this->allmsgs[] = "this person can be logged in as a Player now"; $this->color = "blue";
	
				// At this point this user is verified and has been activated	
				$this->global_func->startNewSession(10, $table);
				
				$_SESSION['verifiedTable'] = $table;
				$_SESSION['verifiedUser'] = $user;	
				$_SESSION['verifiedUserId']  = $user_id; 
				header("Location:".URL_SUCCESS_PAGE);
			}
			else
			{
				$reActivate = new activate_lib();
				$reActivate->reActivate($user, $table);
			}
		}
		else
		{
			$this->allmsgs[] = "Your details does not match."; $this->color =  "red";
		}
	}
	
	private function verifyCoach($table, $user, $p_word)
	{
		$where =  " email = '$user' and pass_word   = '$p_word'";	
		$checkIfCoachExist = $this->db->select("*", $table, $where, "", "");
		if(count($checkIfCoachExist) > 0)
		{
			$user_id = $checkIfCoachExist[0]['c_id'];
			$levelOneIsActivated = $checkIfCoachExist[0]['initial_activate'];
			$levelTwoIsActivated = $checkIfCoachExist[0]['final_activate'];
			
			if($levelOneIsActivated == 1 && $levelTwoIsActivated == 1)
			{
				$this->allmsgs[] = "this person can be logged in as a Player now"; $this->color = "blue";
	
				// At this point this user is verified and has been activated	
				$this->global_func->startNewSession(10, $table);
				
				$_SESSION['verifiedTable'] = $table;
				$_SESSION['verifiedUser'] = $user;	
				$_SESSION['verifiedUserId']  = $user_id; 
				header("Location:".URL_SUCCESS_PAGE);
			}
			else if($levelOneIsActivated == 1)
			{
				$this->global_func->startNewSession(10, $table);
				$_SESSION['verifiedTable'] = $table;
				$_SESSION['verifiedUser'] = $user;	
				$_SESSION['verifiedUserId']  = $user_id; 
				$_SESSION['PatiallyVerifiedUser']  = 'Coach is Not Fully Verified and therefore cannot create sessions / events'; 
				header("Location:".URL_SUCCESS_PAGE);
				
			}
			
			else
			{
				echo "No verification";
				$reActivate = new activate_lib();
				$reActivate->reActivate($user, $table);
			}
			$_SESSION['levelTwoIsActivated'] = $levelTwoIsActivated;
		}
		else
		{
			$this->allmsgs[] = "Your details does not match."; $this->color =  "red";
		}
	}
	
	

	public function returnMsg($msg, $color)
	{
		if(!empty($this->allmsgs))
		{
			$msg = $this->allmsgs;
		}
		if(!empty($this->color))
		{
			$color = $this->color;
		}
		return $this->global_func->DisplayAllMessages($msg, $color);
	}
	
	public function displayAllMsgs($arg1, $arg2)
	{
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
	
	
	
	
	
}
?>