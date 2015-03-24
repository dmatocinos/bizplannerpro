<?php

class employee_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
		$this->maxEmployeeId = $this->getLatestEmployeeId();
		
		
		// If seesion exsit
		if(isset($_SESSION['bpcurrency']))
		{
			// Default values from business plan table (Mother table) using sessions
			$this->defaultEmployeeType = "";
			$this->defaultCurrency = $_SESSION['bpcurrency'];
			$this->startMonth = date('M',strtotime($_SESSION['bpFinancialStartDate']));; // This will always start from April to March
			$this->startFinancialYear = date('Y',strtotime($_SESSION['bpFinancialStartDate']));;
			$this->currencySetting =  $_SESSION['bpcurrency'];
			$this->relatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
			$this->numberOfFinancialYrForcasting = $_SESSION['bpNumberOfFinancialForecastYr']; // 3 or 5
			$this->numberOfYrsOfMonthlyFinancialDetails = $_SESSION['bpYrsOfMonthlyFinancialDetails']; // 1 or 2 or 3 or 4 or 5 cannot be greater than numberOfFinancialYrForcasting above 
		
		}
	}
	
	
	
	/*---------------------------------------------------------------------------------------------------------------
		Start the process of creating employee data by saving data to the necessary tables and calling other functions 
	-----------------------------------------------------------------------------------------------------------------*/
	public function createNewEmployee($e_name)
	{
		//$get_startYear = $this->startFinancialYear;
		//$get_startMonth = $this->startMonth;
		
		$prepDBquery = new FormData();
		$prepDBquery->EmployeeFormData('register');
		
		$table = EMPLOYEE_TB;
		$query = $prepDBquery->queryStringEmployeeTable;
		$where = "";
		if($this->db->insert_advance($table, $query))
		{
			$getMaxEmployeeId = $this->db->select("MAX(emplye_id)", $table, $where, "", "");
			if(count($getMaxEmployeeId) > 0)
			{	$this->maxEmployeeId = $getMaxEmployeeId[0]['MAX(emplye_id)'];
				$employeeId = $getMaxEmployeeId[0]['MAX(emplye_id)'];
				$financialYr = $this->startFinancialYear;
				
				// Controller
				if($this->numberOfYrsOfMonthlyFinancialDetails > 1)
				{
					for ($x=1; $x <= $this->numberOfYrsOfMonthlyFinancialDetails; $x++) 	
					{
						$financialYr = (int)($financialYr+1);
						$_save12MonthE_PlanData =   $this->save12MonthE_PlanData($employeeId, $financialYr);
					}	
				}
				else
				{
					$_save12MonthE_PlanData =   $this->save12MonthE_PlanData($employeeId, $financialYr);
				}
				
				
				// save emplyee forecast 3 or 5 years forecast
				$e_financialForcast = $this->saveEmployeeFinancialForecast($employeeId, $financialYr);
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
	private function save12MonthE_PlanData($employeeId, $financialYr)
	{
		$isOk = false;
		$table = _12_MONTH_EP_TB;
		$query = "(employee_id, financial_yr_forecast) VALUES ('$employeeId', '$financialYr')";
		if($this->db->insert_advance($table, $query))
		{
			$isOk = true;
		}
		return $isOk;
	}
	
	
	/*-------------------------------------------------------------
		save emplyee's Financial Forecast yealy
	---------------------------------------------------------------*/
	private function saveEmployeeFinancialForecast($employeeId, $financialYr)
	{
		$isOK = false;
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$_startFinancialYear = $financialYr;
		$table = E_FINANCIAL_FORECAST_TB;
		
		(int)$defaultPayPerYear = 1; // this set the default value as per years
		
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			// ie 2000 + 1;
			$_startFinancialYear = (int)( $_startFinancialYear + 1 );
			$query = "(financial_year, total_per_yr, related_expenses, employee_id, pay_per_year) VALUES ('$_startFinancialYear', '0', '$this->relatedExpenses', '$employeeId', $defaultPayPerYear)";
			
			if($this->db->insert_advance($table, $query))
			{
				$defaultPayPerYear = 0;
				$isOK = true;
			}
		}	
		return $isOK;
	}
	
	/*-------------------------------------------------------------
		 Select all from the 3 table (REDUNDANT *******************)
	---------------------------------------------------------------*/
	public function getAllEmployeeDetails($where, $orderDesc, $limit)
	{
		$table = EMPLOYEE_TB.', '._12_MONTH_EP_TB. ', '.E_FINANCIAL_FORECAST_TB;
		
		if(!empty($where)){$where .= ' AND employee.emplye_id = employee_12_month_plan.employee_id AND employee.emplye_id = employee_financial_forecast.employee_id';}
		else{$where = 'employee.emplye_id = employee_12_month_plan.employee_id and employee.emplye_id = employee_financial_forecast.employee_id';}
		
		$_getEmployee = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		
		return $_getEmployee;
	}
	
	
	/*-------------------------------------------------------------
		 Better one, get emplyee data from first 2 tables and use 
	---------------------------------------------------------------*/
	public function getAllEmployeeDetails2($where, $orderDesc, $limit)
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
		$table = EMPLOYEE_TB.', '._12_MONTH_EP_TB;
		
		if(!empty($where)){$where .= " AND  employee.employee_bp_id = '$businessPlanId' AND employee.emplye_id = employee_12_month_plan.employee_id GROUP BY employee.emplye_id";}
		else{$where = "employee.employee_bp_id = '$businessPlanId' AND employee.emplye_id = employee_12_month_plan.employee_id GROUP BY employee.emplye_id ";}
		
		$_getEmployee = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		(int)$numberOfEmployee = count($_getEmployee);
		if($numberOfEmployee >0)
		{
			$employeeData = $_getEmployee ;
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
		$financialTable	= E_FINANCIAL_FORECAST_TB;
		
		for( $i=0; $i< $numberOfEmployee; $i++)
		{
			$whereFin =  $employeeData[$i]['emplye_id']. " = employee_financial_forecast.employee_id ";
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
		$table = EMPLOYEE_TB;
		$where = "";
		
		$getMaxEmployeeId = $this->db->select("MAX(emplye_id)", $table, $where, "", "");
		if(count($getMaxEmployeeId) > 0)
		{	
			$latestEmployeeId = $getMaxEmployeeId[0]['MAX(emplye_id)'];
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

	
	/*-------------------------------------------------------------
		Update Employee
	---------------------------------------------------------------*/
	public function updateEmployee($employeeId)
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
		$postedEmployeeType = $_POST['employ_type'];
		$howYouPay = $_POST['how_you_pay'];
		$amountPosted = $_POST['personnel:j_id266:sameAmount'];
 		$amounts = $this->calculateEmployeePayment($howYouPay, $amountPosted);
		
		// Update tables
		$employeeTbOK = $this->updateEmplyeeTable($employeeId, $postedEmployeeName, $postedEmployeeType, $selectedStartingDate);
		$monthsTbOK = $this->updateMonthsTable($employeeId, $amounts, $selectedStartingDate);
		$forecastTbOK = $this->updateFinancialForecastTable($employeeId, $amounts, $selectedStartingDate);
		
		if(($employeeTbOK == true) and ($monthsTbOK == true) and ($forecastTbOK == true)) 
		{
			$isOK = true;	
		}
		return $isOK; 	
	}
	
	
	/*--------------------------------------------------------------- ------
		ONCE I ADVANCE THIS SOFTWARE THEN I NEED TO CONSIDER THIS FUNCTION -
	- ------ ------ ------ ------ ------ ------ ------ ------ ------ ------*/
	private function calculateEmployeePayment($howYouPay, $amountPosted)
	{
		$amounts = array();
		
		// Calculation for years
		if($howYouPay == "per_year" )
		{
			$perMonthOrPerYear = 1;
			
			(int)$yearlyAmount = round($amountPosted, 0);
			
			$monthlyAmount = round(($yearlyAmount / 12),2);
			
			
			
			// Multiply by 12 to get yearly amount
			//echo $amountYearly = $result * 12;
			//$resultArray = (explode(".",($amountYearly),2));
			
			/*
			$CheckIfDigitIsSignificant = substr($resultArray[1], -2, 1);
			
			if($CheckIfDigitIsSignificant < 1)
			{
				echo $amountYearly = $resultArray[0]."." ."00";		
			}
			else
			{
				echo $amountYearly = $amountYearly; 	
			}
			*/
			
			
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
		Update Employee monthly table
	---------------------------------------------------------------*/
	private function updateMonthsTable($employeeId, $amounts, $selectedStartingDate)
	{
		$isOK = false;
		$setMonthlyColumns = "";
		$table12Months = _12_MONTH_EP_TB;
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
		
		$where = "employee_12_month_plan.employee_id = '$employeeId' and financial_yr_forecast = '$startYear'";
		
		if($this->db->update($table12Months, $setMonthlyColumns, $where))
		{
			$isOK = true;
		}
		return $isOK;
	}
	
	
	/*-------------------------------------------------------------
		Update financial forecast table
	---------------------------------------------------------------*/
	private function updateFinancialForecastTable($employeeId, $amounts, $selectedStartingDate)
	{
		$isOK = false;
		
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$financialYear = $this->startFinancialYear;
		$table = E_FINANCIAL_FORECAST_TB;
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
				// Set the exact amount for the remaining years
				$setYearColumn = "total_per_yr = '$amountsYearly'";
			}
			
			// ie 2000 + 1; add 1 to it before using it (financialYear)
			$financialYear = (int)( $financialYear + 1 );
			$where = "employee_financial_forecast.employee_id = '$employeeId' and employee_financial_forecast.financial_year = '$financialYear'";
			
			
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
	
	private function updateEmplyeeTable($employeeId, $employeeName, $employeeType, $selectedStartingDate)
	{
		$isOK = false;
		$table = EMPLOYEE_TB;
		$where = "emplye_id = '$employeeId'";
		$setColumn = "emplye_name = '$employeeName', employee_start_date = '$selectedStartingDate' ,emplye_type = '$employeeType'";
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
		}
		return $isOK;	
	}
	
	/*-------------------------------------------------------------
		BispokeUpdateBizPlan to update Biz plan  table
	---------------------------------------------------------------*/
	public function BispokeUpdateBizPlan($employeeBurdenRate, $bizPlanId)
	{
		$isOK = false;
		$table = BUSINESS_PLAN;
		$setColumn = "bp_related_expenses_in_percentage = '$employeeBurdenRate'";
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
		$start_date =  "$startYear-$startNmonth-28 00:00:01"; // date to delete from selected
		$selected_date = "$selectedYear-$selectedNmonth-28 00:00:01";
		
		/*
		$date_format = 'Y-m-d H:i:s';
		$diff = date_diff(date_create_from_format($date_format, $start_date), date_create($selected_date));
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


	
	
	
	private function date_diff($start, $end="NOW")
	{
        $sdate = strtotime($start);
        $edate = strtotime($end);

        $time = $edate - $sdate;
        if($time>=0 && $time<=59) {
                // Seconds
                $timeshift = $time.' seconds ';

        } elseif($time>=60 && $time<=3599) {
                // Minutes + Seconds
                $pmin = ($edate - $sdate) / 60;
                $premin = explode('.', $pmin);
                
                $presec = $pmin-$premin[0];
                $sec = $presec*60;
                
                $timeshift = $premin[0].' min '.round($sec,0).' sec ';

        } elseif($time>=3600 && $time<=86399) {
                // Hours + Minutes
                $phour = ($edate - $sdate) / 3600;
                $prehour = explode('.',$phour);
                
                $premin = $phour-$prehour[0];
                $min = explode('.',$premin*60);
                
                $presec = '0.'.$min[1];
                $sec = $presec*60;

                $timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';

        } elseif($time>=86400) {
                // Days + Hours + Minutes
                $pday = ($edate - $sdate) / 86400;
                $preday = explode('.',$pday);

                $phour = $pday-$preday[0];
                $prehour = explode('.',$phour*24); 

                $premin = ($phour*24)-$prehour[0];
                $min = explode('.',$premin*60);
                
                $presec = '0.'.$min[1];
                $sec = $presec*60;
                
                $timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec ';

        }
        return $timeshift;
}

	
	/*-------------------------------------------------------------
		Delete Employee
	---------------------------------------------------------------*/
	public function deleteEmployee($employeeId)
	{
		$isOK = false;
		
		$table01 = EMPLOYEE_TB;  $queryString01 = "employee.emplye_id = ".$employeeId;
		$table02 = _12_MONTH_EP_TB; $queryString02 = "employee_12_month_plan.employee_id = ".$employeeId;
		$table03 = E_FINANCIAL_FORECAST_TB; $queryString03 = "employee_financial_forecast.employee_id = ".$employeeId;
		
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
}// end of class
?>