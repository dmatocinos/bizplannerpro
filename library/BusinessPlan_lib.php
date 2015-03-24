<?php
/*
 * Business Plan Settings 
 *
 */ 
 
//class containing business plan setting retrieved from business plan table
class BusinessPlan {

	public $bp_id;
	
	function __construct(){
		
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->fe = new format_FrontEndFormat();
		
		
		
			
		// Check user login
		if(isset($_SESSION['bp_user_id']))
		{
			// good
			$this->user_id = $_SESSION['bp_user_id'];
		}
		else
		{
			$this->global_func->redirect("plan?msg=NO USER ID");	 	
		}
		
		
		global $newPlanHome;	// from business plan page
		
		// If business plan id does not exist redirect to plan page
		if(isset($_GET['bp'])>0)
		{
			// good
			
			$this->bp_id = (int)$_GET['bp'];
			
			// Set the sessions
			$this->OneBusinessPlan();
		}
		elseif(isset($_SESSION['bpId']))
		{
			// good
			$this->bp_id = $_SESSION['bpId'];
			$this->OneBusinessPlan();
		}
		// if plan page, don't redirect
		elseif(isset($newPlanHome))
		{
			
		}
		else
		{
			$this->global_func->redirect(plan);	
		}
	}
	
	
	
	public function getOneBusinessPlan()
	{
		if($this->OneBusinessPlan()){}
		else
		{
			$this->global_func->redirect(plan."?msg=verified but no USER ID");
		}
	}
	
	
	
	/*---------------------------------------------
		All business Plan details in the database
	----------------------------------------------*/
	public function allBusinessPlans()
	{
		return $this->BpDetails("", $this->user_id);
	}
	
	
	private function BpDetails($bp_id, $user_id)
	{
		$getBpDetails = false;
		$table = BUSINESS_PLAN;
		
		if(empty($bp_id))
		{
			$where = "bp_user_id = '$user_id'";
		}
		else
		{
			$where = "bp_id = '$bp_id' and bp_user_id = '$user_id'";	
		}
		
		$getBpDetails = $this->db->select("*", $table, $where, "", "");
	
		return $getBpDetails;
	}
	
	public function updateIncomeTaxRate($taxrate=12) {
			$table = BUSINESS_PLAN;
			$where = "bp_id = '$this->bp_id'";
			$setColumn = "bp_income_tax_in_percentage = '$taxrate'";			
			
			$this->db->update($table, $setColumn, $where);
	}
	
	/*---------------------------------------------------------------------
		This function load selected busniness plan's data into sessions
	----------------------------------------------------------------------*/
	private function OneBusinessPlan()
	{
		$isOK = false;
		$bp_id = $this->bp_id;
		$user_id = $this->user_id;
		
		$getBpDetails = $this->BpDetails($bp_id, $user_id);
	
		if(count($getBpDetails) > 0)
		{	
			
			$_SESSION['bpId'] = 							$getBpDetails[0]['bp_id'];
			$_SESSION['bpName'] = 							$getBpDetails[0]['bp_name'];
			$_SESSION['bpcurrency'] = 						$getBpDetails[0]['currency'];
			$_SESSION['bpFinancialStartDate']	= 			$getBpDetails[0]['bp_financial_start_date'];
			$_SESSION['bpYrsOfMonthlyFinancialDetails'] = 	$getBpDetails[0]['bp_yrs_of_monthly_financial_details'];
			$_SESSION['bpRelatedExpensesInPercentage'] = 	$getBpDetails[0]['bp_related_expenses_in_percentage'];
			$_SESSION['bpIncomeTaxInPercentage'] = 			$getBpDetails[0]['bp_income_tax_in_percentage'];
			$_SESSION['bpNumberOfFinancialForecastYr'] = 	$getBpDetails[0]['bp_number_of_financial_forecast_yr'];
			
			
			
			
			$this->bp_id = $_SESSION['bpId'];
			$this->bpName = $_SESSION['bpName'];
			$this->bpCurrency = $_SESSION['bpcurrency'];
			$this->bpFinancialStartDate = $_SESSION['bpFinancialStartDate'];
			$this->bpYrsOfMonthlyFinancialDetails = $_SESSION['bpYrsOfMonthlyFinancialDetails'];
			$this->bpRelatedExpensesInPercentage = $_SESSION['bpRelatedExpensesInPercentage'];
			$this->bpIncomeTaxInPercentage = $_SESSION['bpIncomeTaxInPercentage'];
			$this->bpNumberOfFinancialForecastYr = $_SESSION['bpNumberOfFinancialForecastYr'];
	
			$isOK = true;
		}
		else
		{
			$isOK = false;
		}
		
		return $isOK;	
	}
	
	
	
	
}
?>
