<?php
if (!$form_validation) { 

}
/***********************************************************************************************
*
*	Validate New Employee Form
*
************************************************************************************************/
else if($form_validation == "validate_new_employee_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['new_employee_name']))							{$new_employee_name = $_POST['new_employee_name'];}
		
		if($new_employee_name == $dummy_new_employee_name)					{$outputMsg[] = "Please enter employee name."; 	$color = 'red';}
		
	
}

/***********************************************************************************************
*
*	Validate New Expenditure Form
*
************************************************************************************************/
else if($form_validation == "validate_new_expenditure_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['new_expenditure_name']))							{$new_expenditure_name = $_POST['new_expenditure_name'];}
		
		if($new_expenditure_name == $dummy_new_expenditure_name)			{$outputMsg[] = "Please enter an expenditure name."; 	$color = 'red';}
		
	
}
/***********************************************************************************************
*
*	Validate New Major Purchase Form
*
************************************************************************************************/
else if($form_validation == "validate_new_major_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['new_major_purchase_name']))							{$new_major_purchase_name = $_POST['new_major_purchase_name'];}
		
		if($new_major_purchase_name == $dummy_new_major_purchase_name)			{$outputMsg[] = "Please enter Major Purchase name."; 	$color = 'red';}
		
	
}
/***********************************************************************************************
*
*	Validate New Loan / Investment
*
************************************************************************************************/
else if($form_validation == "validate_new_loanInvest_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['new_loanInvest_name']))							{$new_loanInvest_name = $_POST['new_loanInvest_name'];}
		
		if($new_loanInvest_name == $dummy_new_loanInvest_name)				{$outputMsg[] = "Please enter an Loan / Investment name."; 	$color = 'red';}
		
	
}

/***********************************************************************************************
*
*	Validate New New sale Forecast Form
*
************************************************************************************************/
else if($form_validation == "validate_new_sale_form")
{
		$outputMsg = array();
		$color = array();
		
		if (!empty($_POST['new_sale_name']))				{$new_sale_name = $_POST['new_sale_name'];}
		
		if($new_sale_name == $dummy_new_sale_name)			{$outputMsg[] = "Please enter an item name."; 	$color = 'red';}
		
	
}

else{ ?>Form validation undefined<?php }
?>