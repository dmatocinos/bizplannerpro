<?php
class register_lib
{
	public $allmsgs = array();
	public $color = array();
	var $DONE = false;
	
	var $columns =''; 
	var $table = '';
	var $where = '';
	
	function __construct(){
		
		$this->db = new Database();
		$this->global_func = new global_lib();
		
	}
	
	public function startRegProcess($table)
	{
		if($table == BUSINESS_PLAN)
		{
			$getBusinessPlanData = new FormData();
			$getBusinessPlanData->BusinessFormData('register');
			
			$queryString = $getBusinessPlanData->queryString;
			 
			$checkIfExist = $getBusinessPlanData->doesPlanExistAlready;
			// $email = 		$getBusinessPlanData->email_db;
			// $password = 	$getBusinessPlanData->password_db;
			// $act_code = 	$getBusinessPlanData->act_code;
		}
		else
		{
			$this->allmsgs[] = "Table is NOT selected"; $this->color = "red";
			//return $this->DisplayAllMsgs($this->allmsgs, $this->color);	
		}
		
		return $this->registrationProcess($table, $queryString, $checkIfExist);
	}

	public function registrationProcess($table, $queryString, $checkIfExist)
	{
		
		if($this->saveData($table, $queryString,  $checkIfExist))
		{
			
			if($table == BUSINESS_PLAN)
			{
				$where = "";
				$getMaxBusinessPlanId = $this->db->select("MAX(bp_id)", $table, $where, "", "");
				if(count($getMaxBusinessPlanId) > 0)
				{
					$this->businessPlanId = $getMaxBusinessPlanId[0]['MAX(bp_id)'];
				}
				
//------------------------------------------------------------------
				$user_id = $_SESSION['bp_user_id'];
				$user = $this->db->select('*', 'users', "id = {$user_id}");
				$user = $user[0];
				if (in_array($user['subscription'], array('standard_monthly', 'standard_annually'))) {
					$where = 'pageid NOT IN (56, 57, 58, 59, 65, 66, 67)';
				}

				$pages = $this->db->select('*', PAGE_TB, $where);
				//var_dump($user); die();
				$columns = '(bp_id, pageid)';
				foreach ($pages as $page) {
					$query = "{$columns} VALUES ({$this->businessPlanId}, {$page['pageid']})";
					$this->db->insert_advance(BP_PAGES_TB, $query);
				}

				$page_sections = $this->db->select('*', SECTION_TB);
				$columns = '(bp_id, section_id)';
				foreach ($page_sections as $section) {
					$query = "{$columns} VALUES ({$this->businessPlanId}, {$section['section_id']})";
					$this->db->insert_advance(BP_SECTION_TB, $query);
				}
//------------------------------------------------------------------
				
				$this->allmsgs[] = "Business Plan was successfully created";
				$this->color = 'blue';
				//header("Location:".URL_ACTIVATE_PLAYER);
			}				
			else
			{
				$this->allmsgs[] = "There is an error, please contact your admistartor";
				$this->color = 'orange';
			}
		}
		else
		{
			$this->allmsgs[] = "Business Plan cannot be created"; 
			$this->color = 'red';  	
		}
		return true;
	}
	
	//return bool
	public function saveData($table,  $queryString, $checkIfExist)	
	{	
		// Business Plan
		$checkIfPlanAlreadyExist = $this->db->select("*", $table , "$checkIfExist", "","");
		if(count($checkIfPlanAlreadyExist) > 0)
		{
			if($table == BUSINESS_PLAN)
			{
				$this->allmsgs[] = "Business Plan already exist.<br />Please try another name.";  
				$this->color = 'orange';	
				$this->DONE = false;
			}
		}
		else
		{
			if($this->db->insert($table, $queryString))
			{
				if($table == BUSINESS_PLAN)
				{
					$this->DONE = true;	
				}
			}
			else{$this->allmsgs[] = "There was an error. Try again"; $this->color = 'red'; $this->DONE = false;}
		}
		
		 //$this->DisplayAllMsgs($this->allmsgs, $this->color);
		 return $this->DONE;
	}
	
	//return bool
	public function updateData($table,  $queryString)
	{
		if($table == BUSINESS_PLAN)
		{
			$getBusinessPlanData = new FormData();
			$getBusinessPlanData->BusinessFormData('updatedetail');
				
			$queryString = $getBusinessPlanData->queryString;
		
			//$checkIfExist = $getBusinessPlanData->doesPlanExistAlready;
			// $email = 		$getBusinessPlanData->email_db;
			// $password = 	$getBusinessPlanData->password_db;
			// $act_code = 	$getBusinessPlanData->act_code;
		}
		else
		{
			$this->allmsgs[] = "Table is NOT selected"; $this->color = "red";
			//return $this->DisplayAllMsgs($this->allmsgs, $this->color);
		}
		
		$businessPlanId = $_SESSION['bpId'];		
		$where = "bp_id = $businessPlanId";
		$orderDesc = "";
		
		
		// Business Plan
		if($this->db->update($table, $queryString, $where))
		{
			if($table == BUSINESS_PLAN)
			{
				$this->DONE = true;	
			}
			
		}
		else {
			
			$this->allmsgs[] = "There was an error. Try again"; $this->color = 'red'; $this->DONE = false;
		}
	
		//$this->DisplayAllMsgs($this->allmsgs, $this->color);
		return $this->DONE;
	}
	
	public function getBusinessPlan()
	{
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			$table = BUSINESS_PLAN;
			$where = "business_plan.bp_id = '$businessPlanId'";
			$orderDesc = "";
			$limit ="";
			
			//echo $where;
			
			$_getPlan = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
			(int)$numberOfPlan = count($_getPlan);
			
			
			
			
			if($numberOfPlan >0)
			{
				$planData = $_getPlan[0] ;				
				return $planData;
			}
			else
			{
				
				return false;
			}
			
			
			
		}
		else
		{
			$businessPlanId = 0;			
			return false;
		}
	
	}
	
	
	
	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
	
	
	
	
	private function sendEmailToCoach($email_db, $msg)
	{
		$to = $email_db;
		$from = 'event@oneraceonefinishline.org';
		$subject = "News event on One Race One Finish Line website";
		$message = $msg;
		return $this->global_func->sendEmail($to, $from, '', $subject, $message);
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
	
	public function getLongMonth($shortmonth) {
		
		$months = array("Jan" => "January", "Feb"=>"February", "Mar" => "March",
				"Apr" => "April", "May" => "May", "Jun" => "June", "Jul" => "July", "Aug" => "August",
				"Sep" => "September", "Oct" => "October", "Nov" => "November", "Dec" => "December");
		
		return $months[$shortmonth];
		
		
	}
	
}
?>
