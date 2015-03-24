<?php

class expenditure_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
		$this->maxEmployeeId = $this->getLatestEmployeeId();
		$this->maxMajorPurchaseId = $this->getLatestMajorPurchaseId();
		
		
		// Default values from business plan table (Mother table) using sessions
		$this->defaultEmployeeType = "";
		$this->defaultCurrency = $_SESSION['bpcurrency'];
		$this->startMonth = date('M',strtotime($_SESSION['bpFinancialStartDate']));; // This will always start from April to March
		$this->startFinancialYear = date('Y',strtotime($_SESSION['bpFinancialStartDate']));;
		$this->currencySetting =  $_SESSION['bpcurrency'];
		$this->relatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
		$this->incomeTaxRate =  $_SESSION['bpIncomeTaxInPercentage'];
		$this->numberOfFinancialYrForcasting = $_SESSION['bpNumberOfFinancialForecastYr']; // 3 or 5
		$this->numberOfYrsOfMonthlyFinancialDetails = $_SESSION['bpYrsOfMonthlyFinancialDetails']; // 1 or 2 or 3 or 4 or 5 cannot be greater than numberOfFinancialYrForcasting above 
		
		
	}
	
	/*---------------------------------------------------------------------------------------------------------------
		Start the process of creating new major purchase data by saving data to the necessary tables and calling other functions 
	-----------------------------------------------------------------------------------------------------------------*/
	public function createNewMajorPurchase($e_name)
	{
		$isOk = false;
		$prepDBquery = new FormData();
		
		
		$table = MAJOR_PURCHASE_TB;
		$query = $prepDBquery->MajorPurchaseFormData('register');
		$where = "";
		
		if($this->db->insert_advance($table, $query))
		{
			$isOk = true;
		}
		
		return $isOk;	
	}
	
	/*---------------------------------------------------------------------------------------------------------------
		Start the process of creating employee data by saving data to the necessary tables and calling other functions 
	-----------------------------------------------------------------------------------------------------------------*/
	public function createNewExpenditure($e_name)
	{
		//$get_startYear = $this->startFinancialYear;
		//$get_startMonth = $this->startMonth;
		
		$prepDBquery = new FormData();
		$prepDBquery->ExpenditureFormData('register');
		
		$table = EXPENDITURE_TB;
		$query = $prepDBquery->queryStringEmployeeTable;
		$where = "";
		if($this->db->insert_advance($table, $query))
		{
			$getMaxExpenditureId = $this->db->select("MAX(exp_id)", $table, $where, "", "");
			if(count($getMaxExpenditureId) > 0)
			{	$this->maxEmployeeId = $getMaxExpenditureId[0]['MAX(exp_id)'];
				$expenditureId = $getMaxExpenditureId[0]['MAX(exp_id)'];
				$financialYr = $this->startFinancialYear;
				
				// Controller
				if($this->numberOfYrsOfMonthlyFinancialDetails > 1)
				{
					for ($x=1; $x <= $this->numberOfYrsOfMonthlyFinancialDetails; $x++) 	
					{
						$financialYr = (int)($financialYr+1);
						$_save12MonthE_PlanData =   $this->save12MonthE_PlanData($expenditureId, $financialYr);
					}	
				}
				else
				{
					$_save12MonthE_PlanData =   $this->save12MonthE_PlanData($expenditureId, $financialYr);
				}
				
				
				// save expenditure forecast 3 or 5 years forecast
				$e_financialForcast = $this->saveExpenditureFinancialForecast($expenditureId, $financialYr);
			}
			
			if(($_save12MonthE_PlanData == true) && ($e_financialForcast == true))
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
		save emplyee's 12 month plan yearly
	---------------------------------------------------------------*/
	private function save12MonthE_PlanData($expenditureId, $financialYr)
	{
		$isOk = false;
		$table = EX_12_MONTH_P_TB;
		$query = "(expenditure_id, financial_yr_forecast) VALUES ('$expenditureId', '$financialYr')";
		if($this->db->insert_advance($table, $query))
		{
			$isOk = true;
		}
		return $isOk;
	}
	
	
	/*-------------------------------------------------------------
		save emplyee's Financial Forecast yealy
	---------------------------------------------------------------*/
	private function saveExpenditureFinancialForecast($expenditureId, $financialYr)
	{
		$isOK = false;
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$_startFinancialYear = $financialYr;
		$table = EX_FINANCIAL_FORECAST_TB;
		
		(int)$defaultPayPerYear = 1; // this set the default value as per years
		
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			// ie 2000 + 1;
			$_startFinancialYear = (int)( $_startFinancialYear + 1 );
			$query = "(financial_year, total_per_yr, related_expenses, expenditure_id, pay_per_year) VALUES ('$_startFinancialYear', '0', '$this->relatedExpenses', '$expenditureId', $defaultPayPerYear)";
			
			if($this->db->insert_advance($table, $query))
			{
				$defaultPayPerYear = 0;
				$isOK = true;
			}
		}	
		return $isOK;
	}
	
	
	
	
	/*-------------------------------------------------------------
		 Get Major Pruchases data from first the table 
	---------------------------------------------------------------*/
	public function getAllMajorPurchaseDetails($where, $orderDesc, $limit)
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
		
		
		
		$table = MAJOR_PURCHASE_TB;
		
		if(!empty($where)){$where .= " AND   major_purchases.mp_bpid = '$businessPlanId' GROUP BY  major_purchases.mp_id ";}
		else{$where = " major_purchases.mp_bpid = '$businessPlanId' GROUP BY  major_purchases.mp_id ";}
		
				
		//echo "including expenses " . $sql; die();
		
			$_getMajorPurchase = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		
		
		
		
		
		
		
		(int)$numberOfMajorPurchase = count($_getMajorPurchase);
		if($numberOfMajorPurchase >0)
		{
			$majorPurchaseData = $_getMajorPurchase ;
			
			return $_getMajorPurchase;
		}
		else
		{
			return false;
		}
	}
	/*-------------------------------------------------------------
		 Better one, get emplyee data from first 2 tables and use 
	---------------------------------------------------------------*/
	public function getAllExpenditureDetails($where, $orderDesc, $limit)
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
		$table = EXPENDITURE_TB.', '.EX_12_MONTH_P_TB;
		
		if(!empty($where)){$where .= " AND  expenditure.expenditure_bp_id = '$businessPlanId' AND  expenditure.exp_id = expenditure_12_month_plan.expenditure_id GROUP BY  expenditure.exp_id";}
		else{$where = " expenditure.expenditure_bp_id = '$businessPlanId' AND  expenditure.exp_id = expenditure_12_month_plan.expenditure_id GROUP BY  expenditure.exp_id ";}
		
		$_getEmployee = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		(int)$numberOfEmployee = count($_getEmployee);
		if($numberOfEmployee >0)
		{
			$employeeData = $_getEmployee ;
			//print_r($employeeData);
			return $this->FinancialForecast($employeeData, $numberOfEmployee);
		}
		else
		{
			return false;
		}
	}
	/*-------------------------------------------------------------
		Internal function Financial forecast
	---------------------------------------------------------------*/
	private function FinancialForecast($employeeData, $numberOfEmployee)
	{
		$financialTable	= EX_FINANCIAL_FORECAST_TB;
		
		for( $i=0; $i< $numberOfEmployee; $i++)
		{
			$whereFin =  $employeeData[$i]['exp_id']. " =  expenditure_financial_forecast.expenditure_id ";
			$_getEmployeeFinancials = $this->db->select("*", $financialTable, $whereFin, "", "", "");
			$employeeData[$i]['financial_status'] = $_getEmployeeFinancials;
		}
		return $employeeData;
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
	public function getLatestEmployeeId()
	{
		$latestEmployeeId = 0;
		$table = EXPENDITURE_TB;
		$where = "";
		
		$getMaxExpenditureId = $this->db->select("MAX(exp_id)", $table, $where, "", "");
		if(count($getMaxExpenditureId) > 0)
		{	
			$latestEmployeeId = $getMaxExpenditureId[0]['MAX(exp_id)'];
		}
		return $latestEmployeeId;
	}
	
	/*-------------------------------------------------------------
		 Get the latest inserted Major Expenses id
	---------------------------------------------------------------*/
	public function getLatestMajorPurchaseId()
	{
		$latestMpId = 0;
		$table = MAJOR_PURCHASE_TB;
		$where = "";
		
		$getMaxMpId = $this->db->select("MAX(mp_id)", $table, $where, "", "");
		if(count($getMaxMpId) > 0)
		{	
			$latestMpId = $getMaxMpId[0]['MAX(mp_id)'];
		}
		return $latestMpId;
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
		 14 month loop for 12months and 2 years
	---------------------------------------------------------------*/
	public function twelveMonthsPlusTwoYrs($startYear, $startMonth)
	{
		if($startYear == ""){$startYear = $this->startFinancialYear;}
		if($startMonth == ""){$startMonth = $this->startMonth;}
		$twoMoreYrs = 2;
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
		
		$strtOfMonth = $startMonth;
		
		$endOfMonth = $listofMonths[11];
		$endOfMonth   = substr($endOfMonth, 0, 3); 
		$nxtOneYr = $startYear + 1;
		$nxtTwoYrs = $nxtOneYr + 1;
		$nxtThreeYrs = $nxtTwoYrs + 1;
		
		$listofMonths[$x] = $strtOfMonth . " " . $nxtOneYr . "-". $endOfMonth . " " . $nxtTwoYrs . " (Year 2)";
		$listofMonths[$x+1] = $strtOfMonth . " " . $nxtTwoYrs . "-". $endOfMonth . " " . $nxtThreeYrs . " (Year 3)";
		
		//print_r($listofMonths);
		
		return  $listofMonths;
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

	
	/*-------------------------------------------------------------
		Update Expenditure table
	---------------------------------------------------------------*/
	public function updateExpenditure($expenditureId)
	{
		$isOK = false;
		$column = "";
	 	
		//  GET START DATE
		if(isset($_POST['month_year_date']))
		{
			if(!empty($_POST['month_year_date']))
			{
				// use the latest selected start date
				$selectedStartingDate = trim($_POST['month_year_date']);
			}
			else
			{
				// use the old existing start date
				$selectedStartingDate = trim($_POST['selectedStartDate']);	
			}
		}
		
		$postedEmployeeName = htmlentities(addslashes($_POST['latestEmplyeeName']),ENT_COMPAT, "UTF-8");
        $expected_change    = $_POST['expected_change'];
        $percentage_of_change  = intval(trim($_POST['percentage_of_change']));
        $percentage_of_change  = $percentage_of_change > 100 ? 100 : $percentage_of_change;
		//$postedEmployeeType = $_POST['employ_type'];
		$howYouPay = $_POST['how_you_pay'];
		$amountPosted = $_POST['personnel:j_id266:sameAmount'];
 		$amounts = $this->calculateExpenditurePayment($howYouPay, $amountPosted);
		
		// Update tables
		$employeeTbOK = $this->updateExpenditureTable($expenditureId, $postedEmployeeName, $selectedStartingDate, $expected_change, $percentage_of_change);
		$monthsTbOK = $this->updateMonthsTable($expenditureId, $amounts, $selectedStartingDate);
		$forecastTbOK = $this->updateFinancialForecastTable($expenditureId, $amounts, $selectedStartingDate, $expected_change, $percentage_of_change);
		
		if(($employeeTbOK == true) and ($monthsTbOK == true) and ($forecastTbOK == true)) 
		{
			$isOK = true;	
		}
		return $isOK; 	
	}

	/*-------------------------------------------------------------
		Update Major Purchase Table
	---------------------------------------------------------------*/
	public function updateMajorPurchase($majorPurchaseId)
	{
		$isOK = false;
		$column = "";
	 	
		//  GET START DATE
		if(isset($_POST['month_year_date']))
		{
			if(!empty($_POST['month_year_date']))
			{
				// use the latest selected start date
				$selectedStartingDate = trim($_POST['month_year_date']);
			}
			else
			{
				// use the old existing start date
				$selectedStartingDate = trim($_POST['selectedStartDate']);	
			}
		}
		
		$majorPurchaseName = htmlentities(addslashes($_POST['latestMajorPurchaseName']),ENT_COMPAT, "UTF-8");
		//$postedEmployeeType = $_POST['employ_type'];
		$howYouPay = "per_year"; // delete later;
		
		$amountPosted = $_POST['mpPrice'];
		
		$depreciate_type = $_POST['depreciate_type'];
 		
		//$amounts = $this->calculateExpenditurePayment($howYouPay, $amountPosted);
		
		// Update tables
		$majorPurchaseTbOK = $this->updateMajorPurchaseTable($majorPurchaseId, $majorPurchaseName, $amountPosted,  $selectedStartingDate, $depreciate_type);
		
		
		
		if($majorPurchaseTbOK == true) 
		{
			$isOK = true;	
		}
		return $isOK; 	
	}
	private function updateMajorPurchaseTable($majorPurchaseId, $majorPurchaseName, $amountPosted, $selectedStartingDate, $depreciate_type)
	{
		$isOK = false;
		$table = MAJOR_PURCHASE_TB;
		$where = "mp_id = '$majorPurchaseId'";
		$setColumn = "mp_name = '$majorPurchaseName', mp_price = '$amountPosted', mp_date = '$selectedStartingDate', mp_depreciate = '$depreciate_type' ";
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
		}
		return $isOK;	
	}
	

	
	
	public function updateMajorPurchaseDates($oldstartdate, $newstartdate) {
	
		$tmp	= explode(' ', $oldstartdate);				
		$oldmo	= $tmp[0];
		$oldyr	= intval($tmp[1]);
			
		$tmp	= explode(' ', $newstartdate);
		$newmo	= $tmp[0];
		$newyr	= intval($tmp[1]);
	
	
		$allPurDetails      = $this->getAllMajorPurchaseDetails("", "", ""); // All Expenditures
		
		
		
		foreach ($allPurDetails as $purDetail) {
		
			$mjId	= $purDetail['mp_id'];
			$mjDate = $this->getTranslatedMonth($oldmo, $oldyr, $newmo, $newyr, $purDetail['mp_date']);			
					
			$table = MAJOR_PURCHASE_TB;
			$where = "mp_id = '$mjId'";
			$setColumn = "mp_date = '$mjDate'";
			
			
			
			$this->db->update($table, $setColumn, $where);
		}
		
		
	}
	
	public function getIntMonth($shortmonth) {
		$months = array("Jan" => 1, "Feb"=>2, "Mar" => 3,
				"Apr" => 4, "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8,
				"Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12);
		
		return $months[$shortmonth];
	}
	
	public function getShortMonth($ishortmonth) {
		$months = array("Jan", "Feb", "Mar",
				"Apr", "May", "Jun", "Jul", "Aug",
				"Sep", "Oct", "Nov", "Dec");
		
		return $months[$ishortmonth-1];
	}
	
	
	
	public function getTranslatedMonth($shortmonth, $year, $startmo, $startyr, $mjdate ) {
		
		$newsetofdates = array();
		$oldsetofdates = array();
		
		
		$iMo = $this->getIntMonth($shortmonth);
		$iYr = intval($year);
		
		$iStartMo = $this->getIntMonth($startmo);
		$iStartYr = intval($startyr);
		
		$tyr = $iYr;
		$tyr1 = $iStartYr;
		
		$j = $iMo;
		$k = $iStartMo;
		
		for($i=0; $i < 12; $i++) {
			
			if ($j > 12 ) { $j = 1; $tyr++; }			
			$oldsetofdates[] = $this->getShortMonth($j) . " " . $tyr;
			$j += 1;
						
			if ($k > 12 ) { $k = 1; $tyr1++; }			
			$newsetofdates[] = $this->getShortMonth($k) . " " . $tyr1;
			$k += 1;
			
		}
		
		$pos = array_search($mjdate, $oldsetofdates);
		
		$newDate = $newsetofdates[$pos];

		if (strpos($mjdate, '(Year 2)') !== FALSE) {
			
			$tmp1 = ($iStartMo - 1) == 0 ? 12 : ($iStartMo - 1);
			$tmp2 = $iStartYr + 1;
			$tmp3 = $tmp2 + 1;
			
			$newDate = $this->getShortMonth($iStartMo) . " " . $tmp2;
			$newDate .= "-" . $this->getShortMonth($iStartMo) . " " . $tmp3 . " (Year 2)";
			
		} else if (strpos($mjdate, '(Year 3)') !== FALSE) {
			
			$tmp1 = ($iStartMo - 1) == 0 ? 12 : ($iStartMo - 1);
			$tmp2 = $iStartYr + 2;
			$tmp3 = $tmp2 + 1;
			
			$newDate = $this->getShortMonth($iStartMo) . " " . $tmp2;
			$newDate .= "-" . $this->getShortMonth($iStartMo) . " " . $tmp3 . " (Year 2)";
		}
		

		return $newDate;
		
	
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
		else
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
		Update Expenditure monthly table
	---------------------------------------------------------------*/
	private function updateMonthsTable($expenditureId, $amounts, $selectedStartingDate)
	{
		$isOK = false;
		$setMonthlyColumns = "";
		$table12Months = EX_12_MONTH_P_TB;
		$startYear = $this->startFinancialYear;
		$startMonth = $this->startMonth;
		
		$selectedNmonth = date("n", strtotime($selectedStartingDate));
	 	$selectedYear = date("Y", strtotime($selectedStartingDate));
		$monthsArray = array("month_01", "month_02",	"month_03",	"month_04",	"month_05",	"month_06",	"month_07",	"month_08",	"month_09",	"month_10",	"month_11",	"month_12");
		//$nmonth = date("M Y", strtotime($selectedStartingDate));  can be useful someday, convert to month from year and month
		
		$diffInMonths = $this->getMonthsDifference($selectedYear, $selectedNmonth);
		for ($x=0; $x < 12; $x++) 
		{
			// decide what months to set to 0 due to selected start date
			if($diffInMonths > 0 )
			{
				$diffInMonths = ($diffInMonths - 1);
				$amountsMounthly = 0;
			}
			else
			{
				// Get monthly Payment from the array ($amounts)
				$amountsMounthly = $amounts[0];
			}
			
			
			if($x == 11)
			{
				// if it's the last month, don't add comma at the end of the update query
				$setMonthlyColumns .=  "$monthsArray[$x] = '$amountsMounthly' ";
			}
			else
			{
				// build monthly string for updates
				$setMonthlyColumns .=  "$monthsArray[$x] = '$amountsMounthly', ";
			}
			
		}
		
		$where = "expenditure_id = '$expenditureId' and financial_yr_forecast = '$startYear'";
		
		if($this->db->update($table12Months, $setMonthlyColumns, $where))
		{
			$isOK = true;
		}
		return $isOK;
	}
	
	
	/*-------------------------------------------------------------
		Update financial forecast table for expenditure
	---------------------------------------------------------------*/
	private function updateFinancialForecastTable($expenditureId, $amounts, $selectedStartingDate, $expected_change, $percentage_of_change)
	{
		$isOK = false;
		
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$financialYear = $this->startFinancialYear;
		$table = EX_FINANCIAL_FORECAST_TB;
		$amountsYearly = $amounts[1];
		$howAreYouPaying = $amounts[2];
	
		$selectedYear = date("Y", strtotime($selectedStartingDate));
		$selectedNmonth = date("n", strtotime($selectedStartingDate));
	 	$diffInMonths = $this->getMonthsDifference($selectedYear, $selectedNmonth);
		
		// calculate the fist year payment based on difference in months
		if($diffInMonths > 0)
		{
			$amountsMonthly = $amounts[0];
			$lostMonthPay = ($amountsMonthly * $diffInMonths);
			$firstYearAnmount = round(($amountsYearly - $lostMonthPay), 0);	
		}
		else
		{
			$firstYearAnmount = $amounts[1];
		}
		
			
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			
			if($x==1)
			{
				// Set the exact amount for the first year
				$setYearColumn = "total_per_yr = '$firstYearAnmount', pay_per_year = '$howAreYouPaying'";
			}
			else
			{
                $amountsYearly = $amountsYearly + (($expected_change == 'decrease' ? -1 : 1) * ($percentage_of_change/100) * $amountsYearly);
				// Set the exact amount for the remaining years
				$setYearColumn = "total_per_yr = '$amountsYearly'";
			}
			
			// ie 2000 + 1; add 1 to it before using it (financialYear)
			$financialYear = (int)( $financialYear + 1 );
			$where = "expenditure_financial_forecast.expenditure_id = '$expenditureId' and expenditure_financial_forecast.financial_year = '$financialYear'";
			
			
			if($this->db->update($table, $setYearColumn, $where))
			{
				$isOK = true;
			}
			else
			{
				$isOK = false;
				return $isOK; // break out if one updates from the loop fails	
			}
		}	
		
		return $isOK;
	}
	
	private function updateExpenditureTable($expenditureId, $expenditureName, $selectedStartingDate, $expected_change, $percentage_of_change)
	{
		$isOK = false;
		$table = EXPENDITURE_TB;
		$where = "exp_id = '$expenditureId'";
		$setColumn = "expenditure_name = '$expenditureName', expenditure_start_date = '$selectedStartingDate', expected_change = '$expected_change', percentage_of_change = '$percentage_of_change'";
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
		}
		return $isOK;	
	}
	
	/*-------------------------------------------------------------
		BispokeUpdateBizPlan to update Biz plan table
	---------------------------------------------------------------*/
	public function BispokeUpdateBizPlan($budgetIncomeTaxRate, $bizPlanId)
	{
		$isOK = false;
		$table = BUSINESS_PLAN;
		$setColumn = "bp_income_tax_in_percentage = '$budgetIncomeTaxRate'";
		$where = "bp_id = '$bizPlanId'";
		
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
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
		$start_date =  "$startYear-$startNmonth-28 00:00:01";
		$selected_date = "$selectedYear-$selectedNmonth-28 00:00:01";
		/*
		$date_format = 'Y-m-d H:i:s';
		$diff = date_diff(date_create_from_format($date_format, $delete_date), date_create($selected_date));
		$diffInMonths = $diff->m;
		*/
		
		return 	$this->new_getMonthsDifference($selected_date, $start_date);
	}
	
	
	private function new_getMonthsDifference($selected_date, $start_date)
	{
		// Change month to number
		$startMonth =  date("n", strtotime("$start_date"));
		
		// Do the difference by subtracting selected date from the Start Date 
		$M_diff = date("n", strtotime("- $startMonth month, $selected_date"));
		
		// Make sure it's resert bact to 0 if it gets to 12
		if ($M_diff == 12){$M_diff = 0;}
		
		return $M_diff;	
	}
	
	/*-------------------------------------------------------------
		Delete Expenditure
	---------------------------------------------------------------*/
	public function deleteExpenditure($expenditureId)
	{
		$isOK = false;
		
		$table01 = EXPENDITURE_TB;  $queryString01 = "exp_id = ".$expenditureId;
		$table02 = EX_12_MONTH_P_TB; $queryString02 = "expenditure_12_month_plan.expenditure_id = ".$expenditureId;
		$table03 = EX_FINANCIAL_FORECAST_TB; $queryString03 = "expenditure_financial_forecast.expenditure_id = ".$expenditureId;
		
		if($this->db->delelet($table01, $queryString01))
		{
			if($this->db->delelet($table02, $queryString02))
			{
				if($this->db->delelet($table03, $queryString03))
				{
					$isOK = true;	
				}	
			}	
		}
		return $isOK; 	
	}
	
		
	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
	
	public function calculateMajorPurchases() {
		$sales_forecast_lib = new sales_forecast_lib();
		$start_month        = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$start_years        = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$months             = $sales_forecast_lib->twelveMonths($start_years, $start_month);
		$currency           = $sales_forecast_lib->defaultCurrency;
		$allPurDetails      = $this->getAllMajorPurchaseDetails("", "", ""); // All Expenditures
		
		$monthlymajorpurchasesrows    = array();
		$monthlymajorpurchasesindexed = array();
		$monthlytotalmajorpurchases   = array();
		$monthlytotaldepreciation     = array();
		$yearlymajorpurchases         = array();
		$yearlytotalmajorpurchases    = array();
		$monthlytotalmajorpurchases_display = array();
		$yearlytotalmajorpurchases_display  = array();
		
		$monthlytotalmajorpurchases_display[0] = 'Total Major Purchases';
		$yearlytotalmajorpurchases[1] = 0;
		$yearlytotalmajorpurchases[2] = 0;
		$yearlytotalmajorpurchases[3] = 0;
		$yearlytotalmajorpurchases_display[0] = 'Total Major Purchases';
		$yearlytotalmajorpurchases_display[1] = 0;
		$yearlytotalmajorpurchases_display[2] = 0;
		$yearlytotalmajorpurchases_display[3] = 0;
		
		foreach ($allPurDetails as $purDetail) {
			$majorpurchaserows = array($purDetail['mp_name']);
			$majorpurchaseindexed = array();
			$yearlymajorpurchase[0] = $purDetail['mp_name'];
			$yearlytotal = 0;
			
			if (strpos($purDetail['mp_date'], '(Year 2)') !== FALSE) {
				for ($i = 0; $i < 12; $i++) {
					$majorpurchaserows[$i + 1] = global_lib::formatDisplayWithBrackets(0, $currency);
					$majorpurchaseindexed[$i] = 0;
				}
				
				$yearlytotal = $purDetail['mp_price'];
				$yearly_total_index = 2;
			}
			else if (strpos($purDetail['mp_date'], '(Year 3)') !== FALSE) {
				for ($i = 0; $i < 12; $i++) {
					$majorpurchaserows[$i + 1] = global_lib::formatDisplayWithBrackets(0, $currency);
					$majorpurchaseindexed[$i] = 0;
				}
				
				$yearlytotal = $purDetail['mp_price'];
				$yearly_total_index = 3;
			}
			else {
				for ($i = 0; $i < 12; $i++) {
					$value = $months[$i] == $purDetail['mp_date'] ? $purDetail['mp_price'] : 0;
					$majorpurchaserows[$i + 1] = global_lib::formatDisplayWithBrackets($value, $currency);
					$majorpurchaseindexed[$i] = $value;
					if(isset($monthlytotalmajorpurchases[$i])){
						$monthlytotalmajorpurchases[$i] += $value;
					} else {
						$monthlytotalmajorpurchases[$i] = $value;
					}
					
					
					$monthlytotalmajorpurchases_display[$i + 1] = global_lib::formatDisplayWithBrackets($monthlytotalmajorpurchases[$i], $currency);
					$yearlytotal += $value;
					
					// TODO: Where did 20% came from?
					if(isset($monthlytotaldepreciation[$i])){
						$monthlytotaldepreciation[$i] += ($purDetail['mp_depreciate'] == 1) ? (($value * 0.2) / 12) : 0;
					} else {
						$monthlytotaldepreciation[$i] = ($purDetail['mp_depreciate'] == 1) ? (($value * 0.2) / 12) : 0;
					}
					
				}
				
				$yearly_total_index = 1;
			}
			
			$yearlymajorpurchases_data = array();
			$yearlymajorpurchasesraw_data = array();
			
			for ($i = 0; $i < 3; $i++) {
				if ($i == ($yearly_total_index - 1)) {
					$yearlymajorpurchases_data[$i] = global_lib::formatDisplayWithBrackets($yearlytotal, $currency);
					$yearlymajorpurchasesraw_data[$i] = $yearlytotal;
				}
				else {
					$yearlymajorpurchases_data[$i] = global_lib::formatDisplayWithBrackets(0, $currency);
					$yearlymajorpurchasesraw_data[$i] = 0;
				}
			}
			
			if(isset($yearlytotalmajorpurchases[$yearly_total_index - 1])){
				$yearlytotalmajorpurchases[$yearly_total_index - 1]     += $yearlytotal;
			} else {
				$yearlytotalmajorpurchases[$yearly_total_index - 1]     = $yearlytotal;
			}
				
			
			$yearlytotalmajorpurchases_display[$yearly_total_index]  = global_lib::formatDisplayWithBrackets($yearlytotalmajorpurchases[$yearly_total_index - 1], $currency);
			
			
						
				
			$yearlymajorpurchases[] = array_merge(
				array($purDetail['mp_name']),
				$yearlymajorpurchases_data
			);
			
			$yearlymajorpurchasesraw[] = array_merge(
				array($purDetail['mp_name']),
				$yearlymajorpurchasesraw_data
			);
			
			$monthlymajorpurchasesrows[] = $majorpurchaserows;
			$monthlymajorpurchasesindexed[$purDetail['mp_name']] = $majorpurchaseindexed;
		}
		
		$data = array();
		
		$data['monthlymajorpurchasesrows'] 		= $monthlymajorpurchasesrows;
		$data['monthlymajorpurchasesindexed'] 	= $monthlymajorpurchasesindexed;
		$data['monthlytotalmajorpurchasesrows'] 	= $monthlytotalmajorpurchases_display;
		$data['monthlytotalmajorpurchases']      = $monthlytotalmajorpurchases;
		
		$data['yearlymajorpurchasesraw']        = $yearlymajorpurchasesraw;
		$data['yearlymajorpurchases']           = $yearlymajorpurchases;
		$data['yearlytotalmajorpurchases']      = $yearlytotalmajorpurchases;
		$data['yearlytotalmajorpurchasesrows']  = $yearlytotalmajorpurchases_display;
		
		$data['monthlytotaldepreciation']       = $monthlytotaldepreciation;
		
		$monthly_accudepreciation     = array();
		$monthly_balaccudepreciation  = array();
		$monthlytotaldepreciationrows = array('Depreciation and Amortization');
		
		$total_depreciation1 = 0;
		
		for($i = 0; $i < 12; $i++) {
			for($j = 0; $j <= $i; $j++) {
				
				if (isset($monthly_accudepreciation[$i])){
					$monthly_accudepreciation[$i] += $monthlytotaldepreciation[$j];
				} else {
					$monthly_accudepreciation[$i] = $monthlytotaldepreciation[$j];
				}			
				
			}
						
			$total_depreciation1     += $monthly_accudepreciation[$i];
			
			if (isset($monthlytotaldepreciationrows[$i])) {
				$monthlytotaldepreciationrows  = global_lib::formatDisplayWithBrackets(round($monthlytotaldepreciationrows[$i]), $currency);
			}
				
			$monthly_balaccudepreciation[$i] = $i > 0 ? ($monthly_balaccudepreciation[$i-1] - $monthly_accudepreciation[$i]) : ($monthly_balaccudepreciation[$i] = -$monthly_accudepreciation[$i]);
		
		}
		
		$data['monthly_accudepreciation']    = $monthly_accudepreciation;
		$data['monthly_balaccudepreciation'] = $monthly_balaccudepreciation;
		$data['monthlytotaldepreciationrows']     = $monthlytotaldepreciationrows;
		
		$yearly_total_depreciation      = array($total_depreciation1);
		$yearly_total_depreciation_rows = array("Depreciation and Amortization", global_lib::formatDisplayWithBrackets($total_depreciation1, $currency));
		
		$total_depreciation2 = ($yearlytotalmajorpurchases[0] + $yearlytotalmajorpurchases[1] - $total_depreciation1) * 0.2;
		$yearly_total_depreciation[]      = $total_depreciation2;
		$yearly_total_depreciation_rows[] = global_lib::formatDisplayWithBrackets($total_depreciation2, $currency);
		
		$total_depreciation3 = (
			$yearlytotalmajorpurchases[0] + 
			$yearlytotalmajorpurchases[1] +
			$yearlytotalmajorpurchases[2] - 
			$total_depreciation1 - 
			$total_depreciation2
		) * 0.2;
		
		$yearly_total_depreciation[] =      $total_depreciation3;
		$yearly_total_depreciation_rows[] = global_lib::formatDisplayWithBrackets($total_depreciation3);
		
		
		//global_lib::log($this->profitlossdata);
		
		//highlight_string(var_export($monthly_accudepreciation, true));

		$data['yearlydepreciation']     = $yearly_total_depreciation;
		$data['yearlydepreciationrows'] = $yearly_total_depreciation_rows;

		return $data;
	}
}// end of class
?>
