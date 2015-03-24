<?php
class update_lib
{
	public $allmsgs = array();
	var $color;
	var $DONE = false;
	
	var $columns =''; 
	var $table = '';
	var $where = '';
	
	function __construct(){
		
		$this->db = new Database();
		$this->global_func = new global_lib();
	}
	
	public function getTableData($table, $userId)
	{
		if($table == PLAYER_TABLE)
		{
			$where = 'p_id = '. $userId;	
		}
		else if($table == COACH_TABLE)
		{
			$where = 'c_id = '. $userId;		
		}
		else if($table == ADMIN_TABLE)
		{
			$where = 'a_id = '. $userId;		
		}
		
		else
		{
			$allmsgs[] = 'No table is selected'; $color = 'red';
			return 	$this->DisplayAllMsgs($allmsgs, $color);
		}
		
		$checkIfPlayerExist = $this->db->select("*", $table, $where, "", "");
		if(count($checkIfPlayerExist) > 0)
		{
			return $checkIfPlayerExist;
		}
		
	}
	
	
	
	public function updateTable($table, $id, $section)
	{
		if($table == PLAYER_TABLE)
		{
			$getPlayerData = new FormData();
			if(!empty($section))
			{
				// update each column as seleted for player
				$getPlayerData->PlayerEachFormSection($section);
			}
			else
			{
				// update all columns in one go for player
				$getPlayerData->PlayerFormData('update');	
			}
			$setColumns = $getPlayerData->queryString;
			 $where = 'p_id = '. $id;
		}
		
		else if($table == COACH_TABLE)
		{
			$getCoachData = new FormData();
			if(!empty($section))
			{
				// update each column as seleted for coach
				$getCoachData->CoachEachFormSection($section);
			}
			else
			{	//update all columns in one go for coach
				$getCoachData->CoachFormData('update');
			}
			$setColumns = $getCoachData->queryString;
			$where = 'c_id = '. $id;
		}
		else if($table == SESSION_TABLE)
		{
			/*
			$getCoachData = new FormData();
			if(!empty($section))
			{
				// update each column as seleted for coach
				$getCoachData->CoachEachFormSection($section);
			}
			else
			{	//update all columns in one go for coach
				$getCoachData->CoachFormData('update');
			}
			$setColumns = $getCoachData->queryString;
			$where = 'c_id = '. $id;
			*/
		}
		
		
		
		else
		{
			$allmsgs[] = 'No table is selected'; $color = 'red';
			return 	$this->DisplayAllMsgs($allmsgs, $color);
		}
		
		
		
		
		if($this->db->update($table, $setColumns, $where))
		{
		 	$this->allmsgs = 'Update was Successful';
			$this->color = 'blue';	
			return true;
		}
		else 
		{
			$this->allmsgs = 'Error! Could NOT update your details';
			$this->color = 'red';	
			return false;
		}
			
		//$this->DisplayAllMsgs($this->allmsgs, $this->color);		
	}
	
	
	
	
	
	
	
	
	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
	
	private function sendEmailToPlayer($email_db, $password_db, $act_code)
	{
		$activ_message = "Your activation code  is ".$act_code;
		$usename_message = "\nYour username  is ".$email_db;
		$password_message = "\nYour password  is ".$password_db;
		
		$to = $email_db;
		$from = 'RegisterPlayer@oneraceonefinishline.org';
		$subject = "Thank you for registering with One Race One Finish Line";
		$message = $activ_message.$usename_message.$password_message;
		return $this->global_func->sendEmail($to, $from, '', $subject, $message);
	}
	
	
	
}
?>