<?php

class ValidateForm{
	
	
	
	
	
	public function v_email($email)
	{
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$emailInput))
		{
			return false;
		}
		else 
		{
			return true;
		}	
		
	}
	
	public function v_name($name)
	{
		if (!empty($_POST['name']))			{$name = $_POST['name'];}
		
	}
	
	
	
	
}// end of class
	
	
	
?>