<?php
if (!$form_validation) { 

}
/***********************************************************************************************
*
*	Register Player form validation
*
************************************************************************************************/
else if($form_validation == "validate_business_plan_register_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['newPlanForm:plan_name']))				{$bp_name = $_POST['newPlanForm:plan_name'];}
		if (!empty($_POST['businessStageRadioButtonGroupgroup']))	{$bp_strategy = $_POST['businessStageRadioButtonGroupgroup'];}
		if (!empty($_POST['newPlanForm:start-month']))				{$bp_generic = $_POST['newPlanForm:start-month'];}
		if (!empty($_POST['newPlanForm:start-year']))				{$bp_generic = $_POST['newPlanForm:start-year'];}
		
		
		
		if($bp_name == 		$dummy_bp_name)		{$outputMsg[] = "Sorry you forgot the Business name."; 	$color = 'red';}
		
		
}

/***********************************************************************************************
*
*	Register Coach form validation
*
************************************************************************************************/
else if($form_validation == "validate_coach_register_form")
{
	$outputMsg = array();
	$color = array();
	
	if (!empty($_POST['name']))			{$name = $_POST['name'];}
	if (!empty($_POST['email']))		{$email = $_POST['email'];}
	if (!empty($_POST['email2']))		{$email2 = $_POST['email2'];}
	if (!empty($_POST['address']))		{$address = $_POST['address'];}
	if (!empty($_POST['password']))		{$password = $_POST['password'];}
	if (!empty($_POST['sportInt']))		
	{
			 if ($_POST['sportInt'] == 'basketball')	{$selectBasketBall = "selected='selected'";}
		else if ($_POST['sportInt'] == 'football')		{$selectFootBall = "selected='selected'";}
		else if ($_POST['sportInt'] == 'art')			{$selectArt = "selected='selected'";}
		else if ($_POST['sportInt'] == 'entertainment')	{$selectEntertainment = "selected='selected'";}
		else if ($_POST['sportInt'] == 'faith')			{$selectFaith = "selected='selected'";}
		else if ($_POST['sportInt'] == 'music')			{$selectMusic = "selected='selected'";}
		else if ($_POST['sportInt'] == 'business')		{$selectBusiness = "selected='selected'";}
		else if ($_POST['sportInt'] == 'media')			{$selectMedia = "selected='selected'";}
		else if ($_POST['sportInt'] == 'personaldev')	{$selectPersonalDev = "selected='selected'";}
	}
	if (!empty($_POST['sex']))			
	{
			 if ($_POST['sex'] == 'female'){$selectFemale = "selected='selected'";}
		else if ($_POST['sex'] == 'male'){$selectMale = "selected='selected'";}
	}
		
	if($name == 		$dummy_name)				{$outputMsg[] = "Sorry you forgot your name."; 				$color = 'red';}
	if($email == 		$dummy_email)				{$outputMsg[] = "Sorry you forgot your email."; 			$color = 'red';}
	else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email))
	{$outputMsg[] = "The email entered is not in the proper format!";$color = 'red';}

	if($email2 ==		$dummy_email2)				{$outputMsg[] = "Sorry you forgot to re enter your email."; $color = 'red';}
	else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email2)) 
													{$outputMsg[] = "The email re-entered is not in the proper format!";$color = 'red';}
	
	else if($email != $email2){$outputMsg[] = "Your email do not match. Please correct this"; $color = 'red';}
	
	if($address ==		$dummy_address)				{$outputMsg[] = "Enter your Address."; 					$color = 'red';}
	if($password ==		$dummy_password)			{$outputMsg[] = "Enter your Password."; 				$color = 'red';}
	if($_POST['sportInt'] == '0')					{$outputMsg[] = "Select Sport you'll like to coach."; 	$color = 'red';}
	if($_POST['sex'] == '0')						{$outputMsg[] = "Select your Gender.";	 				$color = 'red';}
	if($_POST['birthdate'] != DUMMY_BIRTHDAY) {$dob = $_POST['birthdate']; }
	else{$outputMsg[] = "Please enter your Date of birth."; $color = 'red';}
}

/***********************************************************************************************
*
*	CREATE-EVENT FORM VALIDATION
* 
************************************************************************************************/
else if($form_validation == "validate_events_create_form")
{
	$outputMsg = array();
	$color = array();
	
	
	if (!empty($_POST['session_name']))		{$session_name = 	$_POST['session_name'];}
	if (!empty($_POST['sport_cat']))		
	{
			 if ($_POST['sport_cat'] == 'basketball')		{$selectBasketBall = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'football')			{$selectFootBall = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'art')				{$selectArt = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'entertainment')	{$selectEntertainment = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'faith')			{$selectFaith = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'music')			{$selectMusic = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'business')			{$selectBusiness = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'media')			{$selectMedia = "selected='selected'";}
		else if ($_POST['sport_cat'] == 'personaldev')		{$selectPersonalDev = "selected='selected'";}
	}
	
	if (!empty($_POST['no_players']))		{$noOfPlayers = 	$_POST['no_players'];}
	if (!empty($_POST['session_start']))	{$session_start = 	$_POST['session_start'];}
	if (!empty($_POST['session_end']))		{$session_end = 	$_POST['session_end'];}
	if (!empty($_POST['cost']))				{$cost = 			$_POST['cost']; $cost = number_format($cost, 2, '.', '');}
	if (!empty($_POST['location']))			{$location = 		$_POST['location'];}
	if (!empty($_POST['description']))		{$description = 	$_POST['description'];}
	
	
	
	if($session_name == 	$dummy_session_name)	{$outputMsg[] = "Sorry you forgot the session name."; 	$color = 'red';}
	if($_POST['sport_cat'] == '0')					{$outputMsg[] = "Select Sport category."; 				$color = 'red';}
	
	if($noOfPlayers == 		$dummy_noOfPlayers)		{$outputMsg[] = "Please enter numbers of players ."; 			$color = 'red';}
	elseif(!is_numeric($noOfPlayers))				{$outputMsg[] = "Number of players must be in digit."; 	$color = 'red';}
	if($session_start ==	$dummy_session_start)	{$outputMsg[] = "Enter session start date time."; 		$color = 'red';}
	if($session_end ==		$dummy_session_end)		{$outputMsg[] = "Enter session stop date time."; 		$color = 'red';}
	
	if($cost ==			$dummy_cost)				{$outputMsg[] = "Enter session Cost."; 					$color = 'red';}
	elseif(!is_numeric($cost))						{$outputMsg[] = "Cost must be format of 0.00."; 		$color = 'red';}
	if($location ==		$dummy_location)			{$outputMsg[] = "Enter session location."; 				$color = 'red';}
	if($description ==	$dummy_description)			{$outputMsg[] = "Enter session description."; 			$color = 'red';}
	
	
	
	
}


/***********************************************************************************************
*
*	Validate Players's Update form
*
************************************************************************************************/
else if($form_validation == "validate_player_update_form")
{
		$outputMsg = array();
		$color = array();
		echo 'tosin';
		if (!empty($_POST['name']))				{$name = $_POST['name'];}
		if (!empty($_POST['email']))			{$email = $_POST['email'];}
		if (!empty($_POST['email2']))			{$email2 = $_POST['email2'];}
		if (!empty($_POST['address']))			{$address = $_POST['address'];}
		if (!empty($_POST['medCond']))			{$medCond = $_POST['medCond'];}
		if (!empty($_POST['sportInt']))		
		{
				 if ($_POST['sportInt'] == 'basketball')	{$selectBasketBall = "selected='selected'";}
			else if ($_POST['sportInt'] == 'football')		{$selectFootBall = "selected='selected'";}
			else if ($_POST['sportInt'] == 'art')			{$selectArt = "selected='selected'";}
			else if ($_POST['sportInt'] == 'entertainment')	{$selectEntertainment = "selected='selected'";}
			else if ($_POST['sportInt'] == 'faith')			{$selectFaith = "selected='selected'";}
			else if ($_POST['sportInt'] == 'music')			{$selectMusic = "selected='selected'";}
			else if ($_POST['sportInt'] == 'business')		{$selectBusiness = "selected='selected'";}
			else if ($_POST['sportInt'] == 'media')			{$selectMedia = "selected='selected'";}
			else if ($_POST['sportInt'] == 'personaldev')	{$selectPersonalDev = "selected='selected'";}
		}
		if (!empty($_POST['sex']))			
		{
				 if ($_POST['sex'] == 'female'){$selectFemale = "selected='selected'";}
			else if ($_POST['sex'] == 'male'){$selectMale = "selected='selected'";}
		}
	
		
		
		if($name == 		$dummy_name)				{$outputMsg[] = "Sorry you forgot your name."; 				$color = 'red';}
		if($email == 		$dummy_email)				{$outputMsg[] = "Sorry you forgot your email."; 			$color = 'red';}
		else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email))
		{$outputMsg[] = "The email entered is not in the proper format!";$color = 'red';}
	
		if($email2 ==		$dummy_email2)				{$outputMsg[] = "Sorry you forgot to re-enter your email."; $color = 'red';}
		else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email2)) 
														{$outputMsg[] = "The email re-entered is not in the proper format!";$color = 'red';}
		
		else if($email != $email2){$outputMsg[] = "Your email do not match. Please correct this"; $color = 'red';}
		
		if($address ==		$dummy_address)				{$outputMsg[] = "Sorry you forgot your address."; 			$color = 'red';}
		if($dob ==			$dummy_dob)					{$outputMsg[] = "Sorry you forgot your date of birth."; 	$color = 'red';}
		if($medCond ==		$dummy_medCond)				{$outputMsg[] = "Sorry you forgot your medical condition."; $color = 'red';}
		
}

/***********************************************************************************************
*
*	Update Coach's Detail form validation
*
************************************************************************************************/
else if($form_validation == "validate_coach_update_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['name']))			{$name = $_POST['name'];}
		if (!empty($_POST['email']))		{$email = $_POST['email'];}
		if (!empty($_POST['email2']))		{$email2 = $_POST['email2'];}
		if (!empty($_POST['address']))		{$address = $_POST['address'];}
		if (!empty($_POST['dob']))			{$dob = $_POST['dob'];}
		
		if($name == 		$dummy_name)				{$outputMsg[] = "Sorry you forgot your name."; 				$color = 'red';}
		if($email == 		$dummy_email)				{$outputMsg[] = "Sorry you forgot your email."; 			$color = 'red';}
		else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email))
		{$outputMsg[] = "The email entered is not in the proper format!";$color = 'red';}
	
		if($email2 ==		$dummy_email2)				{$outputMsg[] = "Sorry you forgot to re enter your email."; $color = 'red';}
		else if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email2)) 
														{$outputMsg[] = "The email re-entered is not in the proper format!";$color = 'red';}
		
		else if($email != $email2){$outputMsg[] = "Your email do not match. Please correct this"; $color = 'red';}
		
		if($address ==		$dummy_address)				{$outputMsg[] = "Sorry you forgot your address."; 			$color = 'red';}
		if($dob ==			$dummy_dob)					{$outputMsg[] = "Sorry you forgot your date of birth."; 	$color = 'red';}
}


/***********************************************************************************************
*	
*	Activate Player form validation
*
************************************************************************************************/
else if($form_validation == "validate_player_activate_form")
{
		$outputMsg = array();
		$color = array();
		
		$key_c = '';
		if(!empty($_SESSION['capt_key']))
		{
			$key_c = $_SESSION['capt_key'];	
		}
		else { $outputMsg[] = "SESSION IS NOT ACTIVE."; $color = 'red';}
		
		if (!empty($_POST['act_code']))			{$p_actCode = $_POST['act_code'];}
		if (!empty($_POST['capt_string']))		{$p_captcha = $_POST['capt_string'];}
	
		if($p_actCode == 		$dummy_actCode)	{$outputMsg[] = "Please enter the activation code."; 			$color = 'red';}
		if($p_captcha == 		$dummy_captcha)	{$outputMsg[] = "Please enter the captcha string."; 			$color = 'red';}
		else if($p_captcha != $key_c)			{$outputMsg[] = "Please enter the CAPTCHA string correctly."; 	$color = 'red';} 
		
		
		if(isset($_SESSION['act_code']))
		{
			if($_SESSION['act_code'] != $p_actCode)
			{
				$outputMsg[] = "Activation code not valid. Please check your email for the correct one."; $color = 'red';
			}	
		}
		else
		{
			$outputMsg[] = "No session is running."; $color = 'red';
		}
		// if there's a problem here check if session is being destroyed before getting here
}
/***********************************************************************************************
*	
*	Activate Coach form validation
*
************************************************************************************************/
else if($form_validation == "validate_coach_activate_form")
{
		$outputMsg = array();
		$color = array();
		
		$key_c = '';
		if(!empty($_SESSION['capt_key']))
		{
			$key_c = $_SESSION['capt_key'];	
		}
		else { $outputMsg[] = "SESSION IS NOT ACTIVE."; $color = 'red';}
		
		if (!empty($_POST['act_code']))			{$p_actCode = $_POST['act_code'];}
		if (!empty($_POST['capt_string']))		{$p_captcha = $_POST['capt_string'];}
	
		if($p_actCode == 		$dummy_actCode)	{$outputMsg[] = "Please enter the activation code."; 			$color = 'red';}
		if($p_captcha == 		$dummy_captcha)	{$outputMsg[] = "Please enter the captcha string."; 			$color = 'red';}
		else if($p_captcha != $key_c)			{$outputMsg[] = "Please enter the CAPTCHA string correctly."; 	$color = 'red';} 
		
		
		if(isset($_SESSION['act_code']))
		{
			if($_SESSION['act_code'] != $p_actCode)
			{
				$outputMsg[] = "Activation code not valid. Please check your email for the correct one."; $color = 'red';
			}	
		}
		else
		{
			$outputMsg[] = "No session is running for act_code."; $color = 'red';
		}
}

/***********************************************************************************************
*
*	Login form validation
*
************************************************************************************************/
else if($form_validation == "validate_login_form")
{
	$p_username = $dummy_lusername;
	$p_password = $dummy_lpassword;
	
	if (!empty($_POST['lusername']))		{$p_username = $_POST['lusername'];}
	if (!empty($_POST['lpassword']))		{$p_password = $_POST['lpassword'];}

	if($p_username == 	$dummy_lusername)	{$outputMsg[] = "Please enter your username."; $color = 'red';}
	if($p_password == 	$dummy_lpassword)	{$outputMsg[] = "Please enter your password."; $color = 'red';}
}






else{ ?>Form validation undefined<?php }
?>