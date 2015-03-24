<?php
/*
 * Business Plan Settings 
 *
 */ 
//class containing business plan settings 

/*-------------------------------------------------------------------------------------------------------------------------------------------- 
 *																																			  *
 * This Section has been incoporated in the database (business_plan table), in order for user to able to update it based on  their preference *
 *	 																																		  *
 -------------------------------------------------------------------------------------------------------------------------------------------  */
 
class Settings {

	//public $bp_financial_start_start_date = date('M Y');
	
	public $bp_no_financial_forecast_yr = 3; // i.e 3 or 5
	
	public $bp_releated_expenses_in_percentage = 20; //Burden Rate in percentage
	
	public $bp_income_tax_in_percentage = 0; //in percentage
	
	public $bp_yr_of_monthly_financial_details = 1; // i.e 1,2,3,4 or 5 not more than "bp_no_financial_forecast_yr"
	
	public $bp_currency = "&pound;";
}
?>