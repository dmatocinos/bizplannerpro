<?php
class FetchDB
{
	public $allmsgs = array();
	var $color = array();
	var $global = '';
	var $numberOfBookingsForThisEvent = 0;
	var $booking_status;
	var $bookingDetails;
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->fe = new format_FrontEndFormat();
		$this->booking_status = 0;
	}
	
	
	public function coachDB($coach_id)
	{
		$where = '';
		$where .= "coaches.email = photos.user_email AND photos.user_table = '".COACH_TABLE."'";
		if(!empty($coach_id)) {$where .= ' and coaches.c_id = '.$coach_id;}
		
		$column = 'coaches.c_id, coaches.name, coaches.email, coaches.address, coaches.dob, 
		coaches.reg_date, coaches.initial_activate, coaches.final_activate, photos.img_name, photos.img_id';
		
		$table = COACH_TABLE.' , '. PHOTO_TABLE;
		$_getAllCoaches = $this->db->select($column, $table, $where, "coaches.name","");
		
		if(count($_getAllCoaches) > 0)
		{
			return $_getAllCoaches;
		}
		else
		{
			return false;
		}
	}
	
	public function playerDB($player_id)
	{
		$where = '';
		
		$where .= "players.email = photos.user_email AND photos.user_table = '".PLAYER_TABLE."'";
		if(!empty($player_id)) {$where .= ' and players.p_id = '.$player_id;}
		
		$column = 'players.p_id, players.name, players.email, players.address, players.dob, 
		players.reg_date, players.activate, photos.img_name, photos.img_id';
		
		$table = PLAYER_TABLE.' , '. PHOTO_TABLE;
		$_getAllPlayers = $this->db->select($column, $table, $where, "players.name","");
		
		if(count($_getAllPlayers) > 0)
		{
			return $_getAllPlayers;
		}
		else
		{
			return false;
		}
	}
	
	public function sessionDB($ession_id, $userLoggedInId, $usersTableName)
	{
		$where = "";
		$column = '*';
		$table = SESSION_TABLE.' , '. COACH_TABLE;
		$order = 'sessions.session_publish_date';
		
		if(!empty($ession_id)) {$where .= "sessions.session_id = '".$ession_id."' and";}
		
	 	$where.= " sessions.coach_id = coaches.c_id";
		
		$_getAllsessions = $this->db->select($column, $table, $where, $order,"", "");
		
		if(count($_getAllsessions) > 0)
		{
			//If session exist then get amount of bookngs 
			$sessionId = $_getAllsessions[0]['session_id'];
			$this->getBookings($sessionId, $userLoggedInId, $usersTableName);
			
			return $_getAllsessions;
		}
		else
		{
			return false;
		}
	}
	

	/*******************************************************************
		Get Bookings details from booking table
	*******************************************************************/
	public function getBookings($currentSessionId, $userLoggedInId, $usersTableName)
	{
		$whereBooking = "";
		$column = '*';  

		//$table = BOOKING_TABLE.' , '. PLAYER_TABLE;
		$table = BOOKING_TABLE. ' sb';
		//$orderAsc = 'players.name';
		$coachTable = COACH_TABLE;
	
		if(empty($currentSessionId))
		{
			$msg = 'There is an error, session has no ID. Please contact your developer';
			$color = 'red';
			return $this->returnMsg($msg ,$color);	
		} 
		else
		{
			$session_id = $currentSessionId;
			/*$whereBooking = "sessions_booking.booking_session_id = '".$session_id."' and sessions_booking.booking_player_id = 
													players.p_id  and sessions_booking.attending = 1";*/
													
			$whereBooking = "sb.booking_session_id = '".$session_id."' and sb.attending = 1";
			
			$queryBookings = $this->db->select("*", $table, $whereBooking, "", "", "");
			
			$this->getAttendantDetails($queryBookings);
			
			//Get booking status
			$this->booking_status = $this->getBookingStatus($userLoggedInId, $session_id, $usersTableName);
		}
	}
	
	
	/**************************************************************************
		Get amount of bookngs for the session and players / coach that booked 
	**************************************************************************/
	public function getAttendantDetails($queryBookings)
	{
			$playersIDSQL = '';
			$coachesIDSQL = '';
			$checkIfAnyPlayerBooked = false;
			$checkIfAnyCoachBooked = false;
			$this->namesOfBookers = '';
			$countALLBookers = 0;
			
			foreach($queryBookings as $eachBooking)
			{
				if($eachBooking['attenders_table'] == PLAYER_TABLE)
				{
					
					$checkIfAnyPlayerBooked = true;
					$playersIDSQL .= $eachBooking['booking_player_id']. ' OR p_id = ';
					
				}
				elseif($eachBooking['attenders_table'] == COACH_TABLE)
				{
					$checkIfAnyCoachBooked = true;
					
					$coachesIDSQL .= $eachBooking['booking_player_id']. ' OR c_id = ';
				}
			}
			 	
			//if any player / coach booked query each table to get players  / coach details
			if($checkIfAnyPlayerBooked)
			{
				$playerWhere = " p_id = ".$playersIDSQL .' null' ;
				$queryPlayerAttenders = $this->db->select("*", PLAYER_TABLE, $playerWhere, "", "", "");
				
				foreach($queryPlayerAttenders as $playerDetails)
				{
					//Set number of players that have booke
					$countALLBookers++;
					$this->namesOfBookers .= $playerDetails['name'].'<br />';
				}
			}
			
			if($checkIfAnyCoachBooked)
			{
				$coachWhere = " c_id = ".$coachesIDSQL .' null' ;
				$queryCoachAttenders = $this->db->select("*", COACH_TABLE, $coachWhere, "", "", "");
				
				foreach($queryCoachAttenders as $coachDetails)
				{
					//Set number of coaches that have booked and add it to players count
					$countALLBookers++;
					$this->namesOfBookers .= $coachDetails['name'].'  &nbsp; (C)<br />';
				}
			}
			
			
			$this->numberOfBookingsForThisEvent = $countALLBookers;
	}
	
	public function getBookingStatus($userLoggedInId, $session_id, $usersTableName)
	{
		// User is not logged in
		if($userLoggedInId == 0)
		{
			//User need to sign in to check if they have book
			return 1;
		}
		else
		{	// check if player has book for the current session
			$where = "booking_session_id = '".$session_id."' and booking_player_id = '".$userLoggedInId."'  and attending = 1 
						and attenders_table = '".$usersTableName."'";
			if($this->checkIfUserHasBookedSession(BOOKING_TABLE, $where))
			{
				//User has booked session
				return 2;
			}
			else
			{
				//User  has not booked and can book
				return 3;
			}
		}
	
	}
	
	public function Book($session_id, $session_name, $loggedInUser_id, $amountPaid, $usersTableName)
	{
		// Check if session is already being booked by player
		$where = "booking_session_id = '".$session_id."' and booking_player_id = '".$loggedInUser_id."' 
					and attenders_table = '".$usersTableName."'";
		if($this->checkIfUserHasBookedSession(BOOKING_TABLE, $where))
		{
			$setColumns = "attending = 1";
			if($this->db->update(BOOKING_TABLE, $setColumns, $where))
			{
				$this->allmsgs = "Your booking has been updated";
				$this->color = "blue";
				$this->global_func->Redirect($_SERVER['REQUEST_URI']);
				return true;
			}
		}
		else
		{	// Book for session
			$queryString = "(NULL,'$session_id', '$session_name', '$loggedInUser_id', '$amountPaid', 1, '$usersTableName')";
			if($this->db->insert(BOOKING_TABLE, $queryString))
			{
				$this->global_func->Redirect($_SERVER['REQUEST_URI']);
				return true;	
			}
			else
			{
				return false;
			}
		}
	}
	public function CancelBookings($session_id, $loggedInUser_id, $loggedInUser_table)
	{
		$setColumns = "attending = 0";
		$where = "booking_session_id = '".$session_id."' and booking_player_id = '".$loggedInUser_id."'
					and attenders_table = '".$loggedInUser_table."'";
		if($this->db->update(BOOKING_TABLE, $setColumns, $where))
		{
			$this->allmsgs = "Your booking for this session has been cancelled";
			$this->color = "orange";
			$this->global_func->Redirect($_SERVER['REQUEST_URI']);
			return true;
		}
	}
	
	public function checkIfUserHasBookedSession($table, $where)
	{
		
		$query = $this->db->select("*", $table, $where, "", "");
		
		if(count($query) > 0)
		{
			return true;
		}
		else{return false;}
	}
	
	public function getSessionByMonth($month)
	{
		$column = '*';
		$table = SESSION_TABLE.' , '. COACH_TABLE;
		$where = "month(sessions.session_start_time)   = '".$month."' and sessions.coach_id = coaches.c_id";
		$orderDesc = 'sessions.session_start_time';
	
		
		
		$_getmonthlySessions = $this->db->select($column, $table, $where, '', $orderDesc,"");
		
		if(count($_getmonthlySessions) > 0)
		{
			return $_getmonthlySessions;
		}
		else
		{
			return false;
		}
	}
	
	
	public function getImage($id, $table)
	{
		
		return '';
	}


	public function getSessionByYear($currentYear)
	{	
		/*
		$column = '*, COUNT(session_start_time)';
		$table = SESSION_TABLE;
		$where = "year(session_start_time)   = '".$currentYear." ' GROUP BY month(session_start_time)";
		$order = 'session_start_time';
		*/
			
		$column = 'session_start_time, COUNT(session_start_time)';
		$table = SESSION_TABLE;
		$where = "year(session_start_time)   = '".$currentYear."' GROUP BY month(session_start_time)";
		$orderDesc = 'session_start_time';
	
		
		
		$_getAllsessions = $this->db->select($column, $table, $where, '', $orderDesc,"");
		
		if(count($_getAllsessions) > 0)
		{
			return $_getAllsessions;
		}
		else
		{
			return false;
		}
	}

	
	
	// gets earliest article in the database
    public function getEarliestEvent()
    {
		$temp1 = $this->db->select('MIN(session_start_time)', 'sessions', '', '');
        $earliest_date = array_pop($temp1);
        if(empty($earliest_date['MIN(session_start_time)']))
        {
            throw new DisplayException("No Event exist for archive");
            return;
        }
        return $earliest_date;
    }
	
	
	// get a list of months where articles exist
	// find earliest article and loops through the months and years to the current time
	// returns array containing months and years list
	public function getArchiveDates($number_months, $month)
	{
		for($count=0; $count < $number_months; $count++)
		{					
			$archive_months[] = $month;
			$month++;
			if($month == 13)
			{
				$month = 1;
			}
		}
		//flip array to display most recent month first
		$archive_months = array_reverse($archive_months);
		return $archive_months;
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