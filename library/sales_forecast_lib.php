<?php

class sales_forecast_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
		$this->maxEmployeeId = $this->getLatestSaleId();
		
		
		// Default values from business plan table (Mother table) using sessions
		$this->defaultEmployeeType = "";
		$this->defaultCurrency = $_SESSION['bpcurrency'];
		$this->startMonth = date('M',strtotime($_SESSION['bpFinancialStartDate'])); // This will always start from April to March
		$this->startFinancialYear = date('Y',strtotime($_SESSION['bpFinancialStartDate']));
		$this->currencySetting =  $_SESSION['bpcurrency'];
		$this->relatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
		$this->numberOfFinancialYrForcasting = $_SESSION['bpNumberOfFinancialForecastYr']; // 3 or 5
		$this->numberOfYrsOfMonthlyFinancialDetails = $_SESSION['bpYrsOfMonthlyFinancialDetails']; // 1 or 2 or 3 or 4 or 5 cannot be greater than numberOfFinancialYrForcasting above 
		
		
	}
	
	
	
	/*---------------------------------------------------------------------------------------------------------------------
		Start the process of creating sales product data by saving data to the necessary tables and calling other functions 
	-----------------------------------------------------------------------------------------------------------------------*/
	public function createNewSale($s_name)
	{
		//$get_startYear = $this->startFinancialYear;
		//$get_startMonth = $this->startMonth;
		
		$prepDBquery = new FormData();
		$prepDBquery->SaleFormData('register');
		
		$table = SALES_FORECAST_TB;
		$query = $prepDBquery->queryStringSaleTable;
		$where = "";
		if($this->db->insert_advance($table, $query))
		{
			$getMaxSaleId = $this->db->select("MAX(sf_id)", $table, $where, "", "");
			if(count($getMaxSaleId) > 0)
			{	$this->maxSaleId = $getMaxSaleId[0]['MAX(sf_id)'];
				$saleForecastId = $getMaxSaleId[0]['MAX(sf_id)'];
				$financialYr = $this->startFinancialYear;
				
				// call function to save 12 months sale forecast
				$_save12MonthSaleForecast =   $this->save12MonthSaleForecast($saleForecastId);
				
				// save sale product forecast 3 or 5 years forecast
				$e_financialForcast = $this->saveSaleFinancialForecast($saleForecastId, $financialYr);
			}
			
			if(($_save12MonthSaleForecast == true) && ($e_financialForcast == true))
			{
				$isOk = true;	
			}
			else
			{
				$isOk = false;
			}
		}
		return $isOk;
	}
	
	
	/*-------------------------------------------------------------
		save sales Forecast's 12 month plan yearly
	---------------------------------------------------------------*/
	private function save12MonthSaleForecast($saleForecastId)
	{
		$isOk = false;
		$table = SALES_12_MONTH_F_TB;
		$query = "(sales_forecast_id) VALUES ('$saleForecastId')";
		if($this->db->insert_advance($table, $query))
		{
			$isOk = true;
		}
		return $isOk;
	}
	
	
	/*-------------------------------------------------------------
		save sales Forecast's Financial Forecast yealy
	---------------------------------------------------------------*/
	private function saveSaleFinancialForecast($saleForecastId, $financialYr)
	{
		$isOK = false;
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$_startFinancialYear = $financialYr;
		$table = SALES_FINANCIAL_FORECAST_TB;
		
		
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			// ie 2000 + 1;
			$_startFinancialYear = "yr_".$x;
			$query = "(financial_year, total_per_yr, sales_forecast_id) VALUES ('$_startFinancialYear', '0', '$saleForecastId')";
			
			if($this->db->insert_advance($table, $query))
			{
				$defaultPayPerYear = 0;
				$isOK = true;
			}
		}	
		return $isOK;
	}
	
	
	
	
	/*-------------------------------------------------------------
		 Better one, get emplyee data from first 2 tables and use 
	---------------------------------------------------------------*/
	public function getAllSales($where, $orderDesc, $limit)
	{
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
		}
		else
		{
			$businessPlanId = 0;
			return false;
		}
		
		$table = SALES_FORECAST_TB.', '.SALES_12_MONTH_F_TB;
		
		if(!empty($where)){$where .= " AND  sales_forecast.sales_forecast_bp_id = '$businessPlanId' AND  sales_forecast.sf_id =  sales_12_month_forecast.sales_forecast_id GROUP BY  sales_forecast.sf_id";}
		else{$where = " 					sales_forecast.sales_forecast_bp_id = '$businessPlanId' AND  sales_forecast.sf_id =  sales_12_month_forecast.sales_forecast_id GROUP BY  sales_forecast.sf_id";}
		
		$_getSales = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		(int)$numberOfSales = count($_getSales);
		if($numberOfSales >0)
		{
			$salesData = $_getSales ;
			//print_r($salesData);
			return $this->FinancialForecast($salesData, $numberOfSales);
		}
		else
		{
			return false;
		}
	}
	/*-------------------------------------------------------------
		Internal function Financial forecast
	---------------------------------------------------------------*/
	private function FinancialForecast($salesData, $numberOfSales)
	{
		$financialTable	= SALES_FINANCIAL_FORECAST_TB;
		
		for( $i=0; $i< $numberOfSales; $i++)
		{
			$whereFin =  $salesData[$i]['sf_id']. " =  sales_financial_forecast.sales_forecast_id ";
			$_getSalesFinancials = $this->db->select("*", $financialTable, $whereFin, "", "", "");
			$salesData[$i]['financial_status'] = $_getSalesFinancials;
		}
		return $salesData;
	}
	
	
	/*-------------------------------------------------------------
		Get Financial Year 
	---------------------------------------------------------------*/
	public function financialYear()
	{
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		
		$_startFinancialYear  = $this->startFinancialYear;
		for ($x=0; $x  < $n0FinancialForecast; $x++) 	
		{
			// ie 2000 + 1;
			$listofYears[$x] = $_startFinancialYear = (int)( $_startFinancialYear + 1 );
		}
		return  $listofYears;
	}
	
	/*-------------------------------------------------------------
		 Financial start month **** THIS FUNCTION MIGHT BE REDUNDANT
	---------------------------------------------------------------*/
	private function getFinancialStardtMonth($startMonth)
	{
		$startMonth = (int)$startMonth;
		if(empty($startMonth))
		{
			// month in number
			$month = date('n');
		}
		
		$_month = date("M", mktime(0, 0, 0, $month, 1));
		return $_month;	
	}
	
	/*-------------------------------------------------------------
		 Get the latest inserted Employee id
	---------------------------------------------------------------*/
	public function getLatestSaleId()
	{
		$latestEmployeeId = 0;
		$table = SALES_FORECAST_TB;
		$where = "";
		
		$getMaxSaleId = $this->db->select("MAX(sf_id)", $table, $where, "", "");
		if(count($getMaxSaleId) > 0)
		{	
			$latestEmployeeId = $getMaxSaleId[0]['MAX(sf_id)'];
		}
		return $latestEmployeeId;
	}
	
	/*-------------------------------------------------------------
		 Setting currency
	---------------------------------------------------------------*/
	public function getSettingCurrency()
	{
		// select data from settings databse and get the currency variable  
		return "&pound;";	
	}
	
	/*-------------------------------------------------------------
		 12 month loop per year
	---------------------------------------------------------------*/
	public function twelveMonths($startYear, $startMonth)
	{
		if($startYear == ""){$startYear = $this->startFinancialYear;}
		if($startMonth == ""){$startMonth = $this->startMonth;}
		
		$listofMonths = array();
		//echo date("Y-M" . "-01");
		for ($x=0; $x < 12; $x++) 
		{															
			$time = strtotime("+" . $x . " months", strtotime( $startYear . "-" . $startMonth . "-01"));
			
			$key = date('m', $time);
			$name = date('M Y', $time);
			$months[$key] = $name;
	
			$listofMonths[$x] = $months[$key];
		}
		return  $listofMonths;
	}
	
		/*-------------------------------------------------------------
		 12 month loop per year
	---------------------------------------------------------------*/
	public function twelveMonthsSetting($startYear, $startMonth)
	{
		if($startYear == ""){$startYear = $this->startFinancialYear;}
		if($startMonth == ""){$startMonth = $this->startMonth;}
		
		$listofMonths = array();
		//echo date("Y-M" . "-01");
		for ($x=0; $x < 12; $x++) 
		{															
			$time = strtotime("+" . $x . " months", strtotime( $startYear . "-" . $startMonth . "-01"));
			
			$key = date('m', $time);
			$name = date('F', $time);
			$months[$key] = $name;
	
			$listofMonths[$x] = $months[$key];
		}
		return  $listofMonths;
	}

	
	
	
	
	/*--------------------------------------------------------------------
		Update All Sales table (Forecast, 12_month.. and sales_forecast)
	--------------------------------------------------------------------*/
	public function updateSales($saleForecastId)
	{
		$isOK = false;
		$column = "";
	 	
		
		
		$postedSaleForecastName = htmlentities(addslashes($_POST['sales_forecast_name']),ENT_COMPAT, "UTF-8");
		//$postedEmployeeType = $_POST['employ_type'];
		$howYouPay = $_POST['how_you_pay'];
		$amountPosted = $_POST['personnel:j_id266:sameAmount'];
 		$amounts = $this->calculateExpenditurePayment($howYouPay, $amountPosted);
		
		
		// Update tables
		$employeeTbOK = $this->updateSalesForecastTable($saleForecastId, $postedSaleForecastName, $selectedStartingDate);
		$monthsTbOK = $this->updateMonthsTable($saleForecastId, $amounts, $selectedStartingDate);
		$forecastTbOK = $this->updateFinancialForecastTable($saleForecastId, $amounts, $selectedStartingDate);
		
		if(($employeeTbOK == true) and ($monthsTbOK == true) and ($forecastTbOK == true)) 
		{
			$isOK = true;	
		}
		return $isOK; 	
	}
	
	
	/*--------------------------------------------------------------- ------
		ONCE I ADVANCE THIS SOFTWARE THEN I NEED TO CONSIDER THIS FUNCTION -
	- ------ ------ ------ ------ ------ ------ ------ ------ ------ ------*/
	private function calculateExpenditurePayment($howYouPay, $amountPosted)
	{
		$amounts = array();
		
		// Calculation for years
		if($howYouPay == "per_year" )
		{
			$perMonthOrPerYear = 1;
			
			(int)$yearlyAmount = round($amountPosted, 0);
			
			$monthlyAmount = round(($yearlyAmount / 12),2);
			
		}
		// Calculation in for months
		else if($howYouPay == "per_month" )
		{
			$perMonthOrPerYear = 0;
			
			$monthlyAmount = round($amountPosted, 2);
			
			(int)$yearlyAmount = round(($monthlyAmount * 12), 0);	
		}
		
		$amounts[0] = $monthlyAmount;
		$amounts[1] = $yearlyAmount;
		$amounts[2] = $perMonthOrPerYear;
		
		return $amounts;
			
	}
	
	/*-------------------------------------------------------------
		Update tables 12 months and financial forecast
	---------------------------------------------------------------*/
	public function _updateTables($saleForecastId)
	{
		$isOK = false;
		
		$updateTbs = new FormData();
		$updateTbs->prepareSaleFormData12OmonthsForecast();
		
		$monthsUpdateQuery = $updateTbs->monthsUpdateQueryString;
		$financialForecastArray = $updateTbs->financialForecastUpdateDataInArray;
		
		if($this->updateMonthsTable($saleForecastId, $monthsUpdateQuery))
		{
			if($this->updateFinancialForecastTable($saleForecastId, $financialForecastArray))
			{
				$isOK = true;
			}	 
		}
		
		return $isOK;
	}
	/*-------------------------------------------------------------
		Update tables 12 months and financial forecast
	---------------------------------------------------------------*/
	public function _update12MonthData($saleForecastId, $updateQuery)
	{
		return $this->updateMonthsTable($saleForecastId, $updateQuery);
	}
	/*-------------------------------------------------------------
		Update month table
	---------------------------------------------------------------*/
	private function updateMonthsTable($saleForecastId, $updateQuery)
	{
		$isOK = false;
		$setMonthlyColumns = $updateQuery;
		$table12Months = SALES_12_MONTH_F_TB;
		
		$where = "sales_forecast_id = '$saleForecastId'";
		
		if($this->db->update($table12Months, $setMonthlyColumns, $where))
		{
			$isOK = true;
		}
		return $isOK;
	}
	
	
	/*-------------------------------------------------------------
		Update financial forecast table for expenditure
	---------------------------------------------------------------*/
	private function updateFinancialForecastTable($saleForecastId, $financialForecastArray)
	{
		$isOK = false;
		
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$table = SALES_FINANCIAL_FORECAST_TB;
	
		
			$counter = 0;
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			
			
			$financialYear = "yr_".$x;
			$financialYearData = $financialForecastArray[$counter];
			$setYearColumn = "total_per_yr = '$financialYearData'";
			$where = " sales_financial_forecast.sales_forecast_id = '$saleForecastId' and sales_financial_forecast.financial_year = '$financialYear'";
			
			
			if($this->db->update($table, $setYearColumn, $where))
			{
				$isOK = true;
			}
			else
			{
				$isOK = false;
				return $isOK; // break out if one updates from the loop fails	
			}
			$counter = $counter + 1;
		}	
		
		return $isOK;
	}
	
	private function updateSalesForecastTable($saleForecastId, $saleForecastName)
	{
		$isOK = false;
		$table = SALES_FORECAST_TB;
		$where = "sf_id = '$saleForecastId'";
		$setColumn = "sales_forecast_name = '$saleForecastName'";
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
		}
		return $isOK;	
	}
	
	/*--------------------------------------------------------------------
		Public function to access Update Sales forecast table 
	--------------------------------------------------------------------*/
	public function _updateSalesForecastTable($saleForecastId, $saleForecastName)
	{
		$postedSaleForecastName = htmlentities(addslashes($_POST['sales_forecast_name']),ENT_COMPAT, "UTF-8");	
		
		return $this->updateSalesForecastTable($saleForecastId, $saleForecastName);
	}
	
	/*-----------------------------------------------------------------
		Public Delete function to delete al assosiated sales Forecast
	------------------------------------------------------------------*/
	public function deleteSaleForecast($saleForecastId)
	{
		$isOK = false;
		
		$table01 = SALES_FORECAST_TB;  $queryString01 = " sf_id = ".$saleForecastId;
		$table02 = SALES_12_MONTH_F_TB; $queryString02 = " sales_forecast_id = ".$saleForecastId;
		$table03 = SALES_FINANCIAL_FORECAST_TB; $queryString03 = " sales_forecast_id = ".$saleForecastId;
		
		if($this->db->delelet($table03, $queryString03))
		{
			if($this->db->delelet($table02, $queryString02))
			{
				if($this->db->delelet($table01, $queryString01))
				{
					$isOK = true;	
				}	
			}	
		}
		return $isOK; 	
	}
	
	
	
	
	
	
	/*-------------------------------------------------------------
		Get difference in Business start date and selected date
	---------------------------------------------------------------*/
	private function getMonthsDifference($selectedYear, $selectedNmonth)
	{
		$startYear = $this->startFinancialYear;
		$startMonth = $this->startMonth;
		$startNmonth = date("n", strtotime($startMonth));
		
		// Difference in months	
		$delete_date =  "$startYear-$startNmonth-28 00:00:01";
		$selected_date = "$selectedYear-$selectedNmonth-28 00:00:01";
		$date_format = 'Y-m-d H:i:s';
		$diff = date_diff(date_create_from_format($date_format, $delete_date), date_create($selected_date));
		$diffInMonths = $diff->m;
		
		
		return 	$diffInMonths;
	}
	
	
	
		
	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
}// end of class
?>