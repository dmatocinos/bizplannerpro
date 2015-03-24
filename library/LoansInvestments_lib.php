<?php

include_once(BASE_PATH . "/library/PHPExcel/Classes/PHPExcel/Calculation.php");

class LoansInvestments_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
		$this->maxEmployeeId = $this->getLatestSaleId();
		$this->FirstAmountPaidBack = 0;
		$this->_monthPayment = 0;
		$this->YesThereIsALoan = 0;
		$this->nowPaymentBoxLocation = 0;
		$this->nowLoanBoxLocation = 0;
		$this->previousPaymentBoxLocation = 0; 
		$this->previousLoanBoxLocation = 0;
		$this->YesThereWasAPayment = 0;
		$this->YesThereIsALoan = 0;
		$this->allFunds = 0;
		$this->nowPayment_prof = 0;
		$this->nowLoan_prof = 0;
		$this->loopCounter1 = 0;
		$this->loopCounter2 = 0;
		$this->loopCounter3 = 0;
		
		$this->nowPayment = 0;
		$this->NewLoanBoxNumber = 0;
		$this->NewPaymentBoxNumber = 0;
		$this->loopCounter4 = 1;
		$this->loopCounter5 = array();
		$this->all_payment_madeYrly = array();
		
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
	public function createNewLoanOrInvestment($s_name)
	{
		//$get_startYear = $this->startFinancialYear;
		//$get_startMonth = $this->startMonth;
		
		$prepDBquery = new FormData();
		$prepDBquery->LoanInvestmentFormData('register');
		
		$table = LOAN_INVESTMENT_TB;
		$query = $prepDBquery->queryStringLoanInvestTable;
		$where = "";
		if($this->db->insert_advance($table, $query))
		{
			$getMaxLoanInvestId = $this->db->select("MAX(li_id)", $table, $where, "", "");
			if(count($getMaxLoanInvestId) > 0)
			{	$this->maxSaleId = $getMaxLoanInvestId[0]['MAX(li_id)'];
				$loanInvestForecastId =  $getMaxLoanInvestId[0]['MAX(li_id)'];
				$financialYr = $this->startFinancialYear;
				
				// call function to save 12 months sale forecast
				$_save12MonthSaleForecast =   $this->save12MonthForecast($loanInvestForecastId);
				
				// save sale product forecast 3 or 5 years forecast
				$e_financialForcast = $this->saveFinancialForecast($loanInvestForecastId, $financialYr);
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
		save  Forecast's 12 month plan yearly
	---------------------------------------------------------------*/
	private function save12MonthForecast($loanInvestForecastId)
	{
		$isOk = false;
		$table_receive = LOAN_INVEST_12_M_RECEIVE_TB;
		$table_payment = LOAN_INVEST_12_M_PAYMENT_TB;
		$query_receive = "(limr_loan_investment_id) VALUES ('$loanInvestForecastId')";
		$query_payment = "(limp_loan_investment_id) VALUES ('$loanInvestForecastId')";
		
		$insertReceiveTb = $this->db->insert_advance($table_receive, $query_receive);
		$insertPaymentTb = $this->db->insert_advance($table_payment, $query_payment);
		
		if(($insertReceiveTb == true) and ($insertPaymentTb == true))
		{
			$isOk = true;
		}
		return $isOk;
	}
	
	
	/*-------------------------------------------------------------
		save  Forecast's Financial Forecast yealy
	---------------------------------------------------------------*/
	private function saveFinancialForecast($loanInvestForecastId, $financialYr)
	{
		$isOK = false;
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		echo "<br/>";
		$_startFinancialYear = $financialYr;
		
		$table_receive = LOAN_INVEST_FINANCIAL_F_RECEIVE_TB;
		$table_payment = LOAN_INVEST_FINANCIAL_F_PAYMENT_TB;
		
		
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			// ie 2000 + 1;
			$_startFinancialYear = "yr_".$x;
			$query_receive = "(lir_year, lir_total_per_yr, lir_loan_investment_id) VALUES ('$_startFinancialYear', '0', '$loanInvestForecastId')";
			$query_payment = "(lip_year, lip_total_per_yr, lip_loan_investment_id) VALUES ('$_startFinancialYear', '0', '$loanInvestForecastId')";
			
			print_r($query_receive."<br><br><hr />");
			
			$insertIntoReceiveTb = $this->db->insert_advance($table_receive, $query_receive);
			$insertIntoPaymentTb = $this->db->insert_advance($table_payment, $query_payment);
			
		if(($insertIntoReceiveTb) and ($insertIntoPaymentTb))
			{
				$defaultPayPerYear = 0;
				$isOK = true;
			}
		}	
		return $isOK;
	}
	
	
	
	
	public function allData($funding)
	{
		$zeroPrefix = 0;
		$oneToTwelveCounter = 1;
		for($e_month = 12; $e_month > 0; $e_month-- )
		{
			if($oneToTwelveCounter < 10)
			{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
				$oneToTwelveCounter = $zeroPrefix.$oneToTwelveCounter;
			}
			$amountPaidBackWithCommas[$e_month] = number_format($funding["limp_month_".$oneToTwelveCounter], 0, '.', ',');
			$amountLaonWithCommas[$e_month] = number_format($funding["limr_month_".$oneToTwelveCounter], 0, '.', ',');
			
			
			$oneToTwelveCounter = $oneToTwelveCounter + 1; 
		}
		/*---	Set the payments made	---*/
		$this->all_payment_made = $amountPaidBackWithCommas;
		$this->all_Loan_taken = $amountLaonWithCommas;
		
		$this->allFunds = $funding;
	}
	
	
	/*--------------------------------------------------------------------------------------------
		Write about this HERE TOSIN
	--------------------------------------------------------------------------------------------*/
	public function loanPaymentProjection($upto)
	{
		$counter = 1;
		//$previousBoxLocation = 0;
		$dataItself = array();
	
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i > $upto; $i--)
			{
				$new_array_payment[$i] = $this->all_payment_made[$i];
				$new_array_loan[$i] = $this->all_Loan_taken[$i];
		
		
				$this->nowLoan_prof = $this->all_Loan_taken[$i];
				$this->nowPayment_prof = $this->all_payment_made[$i];
				
				//echo "<hr/>".$i."--( )-- upto = ".$upto."<hr/>"; // Array key
				$dataItself[$counter] = $i;
				
				
				$counter = ($counter + 1);
			}
		}
	
		 
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------------
		Write about this HERE TOSIN
	--------------------------------------------------------------------------------------------*/
	private function DiffFromPaid($upto)
	{
		$counter = 1;
		//$previousBoxLocation = 0;
		$dataItself = array();
	
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i >= $upto; $i--)
			{
				$new_array_payment[$i] = $this->all_payment_made[$i];
				$new_array_loan[$i] = $this->all_Loan_taken[$i];
				
				//echo "<hr/>".$new_array[$i]."<hr/>";
				/*---	If the array carry a value then give me the array key	---*/
				if($new_array_payment[$i] > 0)
				{
					$previousBoxLocation = $i;
					//echo "<hr/>".$i."--( )-- upto = ".$upto."<hr/>"; // Array key
					$dataItself[$counter] = $i;
					$counter = ($counter + 1);
					
					
				}
			}
		}
		//print_r($dataItself);
		$_01 = count($dataItself);
		
	 	$this->nowPayment = $dataItself[$_01];
		
		if($_01 > 1)
		{
			 $_02 = (count($dataItself) - 1); /*---		Request for the data before 	---*/
		}
		else
		{
			$_02 = 1;
		}
		
		
		$this->previousPayment = $dataItself[$_02];	
		/*---	Therefore Deduct the first from the second	---*/
		 $this->_diffFromPaid = ($dataItself[$_02] - $dataItself[$_01]);
		 
	}
	
	/*--------------------------------------------------------------------------------------------
		USED ON all_pojection.php page
	--------------------------------------------------------------------------------------------*/
	public function PaymentTrick($upto)
	{
		
		
		$counter = 1;
		$counter2 = 1;
		$dataItself = array();
		$dataItself2 = array();
		
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i >= $upto; $i--)
			{
				$new_array_payment[$i] = $this->all_payment_made[$i];
				$new_array_loan[$i] = $this->all_Loan_taken[$i];
				
				//echo "<hr/>".$new_array_loan[$i]."<hr/>";
				
				/*---	If the array carry a value then give me the array key	---*/
				if($new_array_payment[$i] > 0)
				{	
					//echo "<hr/>".$i."--( )-- upto = ".$upto."<hr/>"; // Array key
					$dataItself[$counter] = $i;
					$counter = ($counter + 1);
				}
				if($new_array_loan[$i] > 0)
				{
					//echo "<hr/>".$i."--( )-- loans = ".$new_array_loan[$i]."<hr/>"; // Array key
					$dataItself2[$counter2] = $i;
					$counter2 = ($counter2 + 1);
				}
			}
		}
		//print_r($dataItself); 
		$_01 = count($dataItself);
		$_02 = (count($dataItself) - 1); /*---		Request for the data before 	---*/
		
		$_01s = count($dataItself2);
		$_02s = (count($dataItself2) - 1); /*---		Request for the data before 	---*/
		
	 	$this->nowPaymentBoxLocation = $dataItself[$_01];
		$this->nowLoanBoxLocation =    $dataItself2[$_01s];
		
	 	//echo "<hr/>".$this->nowPaymentBoxLocation." ( --- ) ".$dataItself2[$_01s]."<hr/>";
		//
		
		if(count($dataItself)  > 1)
		{
			$this->previousPaymentBoxLocation = $dataItself[$_02];
		}
		else
		{
			$this->previousPaymentBoxLocation = 0;
		}
		
		if(count($dataItself2)  > 1)
		{
			$this->previousLoanBoxLocation = $dataItself2[$_02s];
		}
		else
		{
			$this->previousLoanBoxLocation = 0;
		}
		 
	}
	
	
	/*--------------------------------------------------------------------------------------------
		Payment Made up to a level
	--------------------------------------------------------------------------------------------*/
	private function MonthPayment($upto)
	{
		
		$new_array = array();
		//echo $startFrom;
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i >= $upto; $i--)
			{
				$this->all_payment_made[$i] = str_replace(",", "", $this->all_payment_made[$i]);
				$new_array[$i] = $this->all_payment_made[$i];
				$this->_monthPayment = $this->all_payment_made[$i];
			}
		}
		
		return array_sum($new_array);
	}
	
	/*--------------------------------------------------------------------------------------------
		Sum up all monthly Loan starting upto the box location passed through (arguement)
	--------------------------------------------------------------------------------------------*/
	public function LoanTakenUpto($upto)
	{
		
		$new_array = array();
		//echo $startFrom;
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i >= $upto; $i--)
			{
				$this->all_Loan_taken[$i] = str_replace(",", "", $this->all_Loan_taken[$i]);
				$new_array[$i] = $this->all_Loan_taken[$i];
				
				$this->YesThereWasAPayment = $this->all_payment_made[$i];
				$this->YesThereIsALoan = $this->all_Loan_taken[$i];
			}
		}
		
		return array_sum($new_array);
	}
	/*--------------------------------------------------------------------------------------------
		Sum up all monthly Loan starting upto the box location passed through (arguement)
	--------------------------------------------------------------------------------------------*/
	public function TotalLoanTaken($upto)
	{
		
		$new_array = array();
		//echo $startFrom;
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = 12; $i > $upto; $i--)
			{
				$this->all_Loan_taken[$i] = str_replace(",", "", $this->all_Loan_taken[$i]);
				$new_array[$i] = $this->all_Loan_taken[$i];
				
			}
		}
		
		return array_sum($new_array);
	}
	/*--------------------------------------------------------------------------------------------
		Sum up all monthly payment starting from the month loan was taken out 
	--------------------------------------------------------------------------------------------*/
	private function TotalAmountPaid($startFrom, $upto)
	{
		$new_array = array();
		//echo $startFrom;
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = $startFrom; $i > $upto; $i--)
			{
				$this->all_payment_made[$i] = str_replace(",", "", $this->all_payment_made[$i]);
				$new_array[$i] = $this->all_payment_made[$i];
			}
		}
		//echo $startFrom;
		$this->DiffFromPaid($upto);
		
		return array_sum($new_array);
	}
	
	private function TotalAmountPaid_2($startFrom, $upto)
	{
		//echo "<hr/><hr/><hr/><hr/>".$startFrom."--( < > )<hr/><hr/><hr/><hr/>";
		
		$new_array = array();
		//echo $startFrom;
		if((!empty($upto)) and ($upto > 0))
		{
			for($i = $startFrom; $i > $upto; $i--)
			{
				$this->all_payment_made[$i] = str_replace(",", "", $this->all_payment_made[$i]);
				$new_array[$i] = $this->all_payment_made[$i];
			}
		}
		//echo $startFrom;
		
		
		return array_sum($new_array);
	}
	
	/*--------------------------------------------------------------------------------------------
		Write about this HERE TOSIN
	--------------------------------------------------------------------------------------------*/
	private function IsThereANewLoanTakenAfterPaymemt($amountReceive)
	{
		$prevPaymentBoxLocation = $this->previousPayment;
		$nowPaymentBoxLocation = $this->nowPayment;
		$isOK = 0;
		
		for($each_month = $prevPaymentBoxLocation; $each_month > $nowPaymentBoxLocation; $each_month-- )
        {
			if($amountReceive[$each_month] > 0)
			{
				//echo "<hr/>".$amountReceive[$each_month]." of box location ".$each_month." and my current box location " .$nowPaymentBoxLocation." <hr/>";
				$this->NewLoanBoxNumber = $each_month;
				$this->NewPaymentBoxNumber = $nowPaymentBoxLocation;
				
				$isOK = 1;
			}
			else {}
			
		}
		return $isOK;
	}
	
	

	/*--------------------------------------------------------------------------------------------
		 Fetch and prepare values to calculate the Interest Rate per month for  Expected Payment
	--------------------------------------------------------------------------------------------*/
	public function monthlyInterestExpectedPayment($first_payment_box_location, $other_payments_box_location, $amountPaidBackWithoutCommas, $amountReceiveWithoutCommas, $interestRate)
	{
		
		$firstPaymentMadeCounter = 0;
		$zeroCounter = 0;
		$interestRateCounter = 0;
		$newZeroCounter = 0;
		$_t01_totalAmountPaidCounter = 0;
		$_countFirstSetOfPayments = 0;
		$arrayCollectionOfInterests = array();
		$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT = 0;
		$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS = 0;
		$isThereAPaymentTheSameMonthLoanAWasTaken = 0;
		$_cancelOutExcessPayment = 0;
		$allPaymenetSoFar = 0;	
		
		$addAllPaymentsToALevel = 0;
		$addUpPaymentCounter = 0;
		$addUpPayment = array();
		$thereWasANewLoan = 0;
		$diff_btwnSumOfLoansAndSumOfPayBacks = 0;
		$updateCounter = 0;
		$zeroCounter2 = array();
		
		$allPaymentSoFarTosin = 0;
		$allLoanSoFarTosin = 0;
		$reset_loan = false;		
					
		
		
		/*---	loop thru 12 to 1 to get the data and calculate from left to right	---*/
		for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
        {
		
			/*---	Give me all months where loan has been taken out ---*/
			if($amountReceiveWithoutCommas[$e_month] >= 0)
			{
				 $recieve_box_location = $e_month;
				
				$initialLoan = $amountReceiveWithoutCommas[$e_month];
				 
				/*-- get $recieve_box_location i.e loan box location 	---*/
				
				  (int)$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT = ($recieve_box_location - $first_payment_box_location);
				//echo "<hr/>".$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT."<hr/>";
	
				//echo $recieve_box_location." - ".$first_payment_box_location."= ".$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT."<hr/><hr/><hr/><hr/><hr/><hr/><hr/>";
				
				
				
				// Check if other payments was made
				if(!empty($other_payments_box_location))
				{
					(int)$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS = ($first_payment_box_location - $other_payments_box_location);
					//echo "<hr/>--( )-- ".$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS."<hr/>";
				}
				
				if(($diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS > 0))
				{
					
					
					/*--- After first payement is made, Deduct the first payment made from the first amount loaned 	---*/
					if(($recieve_box_location > 0) and ($firstPaymentMadeCounter == 0)) 
					{
						
						
						// CHECK IF THERE WAS A NEW LOAN TAKEN AFTER THE FIRST PAYMENT WAS  MADE
					
						/*---	Get total amount paid so as to deduct it from the loan before calculating the remaining interest	---*/
						$_totalAmountPaid = $this->TotalAmountPaid($recieve_box_location, $other_payments_box_location);
						
						//print "<hr/>".$_totalAmountPaid."<hr/>" ;
						/*---		BAMBO, COME BACK HERE TO CHECK SHOULD INACSE TH CALC IS WRONG	---*/
						//$amountReceiveWithoutCommas[$e_month] = ($amountReceiveWithoutCommas[$e_month] - $_totalAmountPaid);
						
						
						/*---
							Check of there's any loan taken out after the first payment to reset the counter
						---*/
						 $_isThereANewLoan = $this->IsThereANewLoanTakenAfterPaymemt($amountReceiveWithoutCommas);
						
						
						if($_isThereANewLoan == 0)
						{
							$this->loopCounter5[$this->loopCounter4] = (12 - $this->nowPayment);
							$zeroCounter = $this->nowPayment; // Alterring the loop count
						}
						elseif(($_isThereANewLoan > 0))
						{
							
							$this->loopCounter5[$this->loopCounter4] = (12 - $this->nowPayment);
							$zeroCounter = $this->nowPayment; // Altering the loop count
							$thereWasANewLoan = 1;
						}
						
						if( (isset($this->loopCounter5[6])) )
						{
							if($this->loopCounter5[5] < $this->loopCounter5[6])
							{
								$zeroCounter = $this->nowPayment; // Altering the loop count
								$this->loopCounter5[$this->loopCounter4] = (12 - $this->nowPayment);
							}
						}
						
						//echo "<hr/>->".$this->loopCounter5[$this->loopCounter4]." - ".$this->loopCounter4."<hr/>";
						
						$this->loopCounter4 = ($this->loopCounter4 + 1);
						
					}
					$firstPaymentMadeCounter = ($firstPaymentMadeCounter + 1); /*---	increase the counter	---*/
					
						
					$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS = $this->_diffFromPaid;
					
					$newLocationCalc = 0;
					/*---	If there a new loan and the this is the last loop to get the new loan data	---*/
					if(($thereWasANewLoan > 0) and ($e_month == $this->NewLoanBoxNumber))
					{	
						/*---	Get the difference between the box locations of the new loan and the payback  	
								This should change the figure of the last calculation of each loop 
						---*/
						$this->NewLoanBoxNumber." - ".$this->NewPaymentBoxNumber;
						$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS = ($this->NewLoanBoxNumber - $this->NewPaymentBoxNumber);
						
					}
					
					
					/*-------------------------------------------------------------------------------------------------------	
						Check if there was or were payments made the same month loan was taken out	
					---------------------------------------------------------------------------------------------------------*/
					
					$UnCalculatedInterest = 0;
					$allPaymenetSoFar = $this->MonthPayment($recieve_box_location);
					$isThereAPaymentTheSameMonthLoanAWasTaken =  $this->_monthPayment;
					$allLoanSoFar = $this->LoanTakenUpto($recieve_box_location);
					
					if($isThereAPaymentTheSameMonthLoanAWasTaken > 0)
					{
						//echo "<hr/><hr/><hr/>".$isThereAPaymentTheSameMonthLoanAWasTaken ." <---> ". $initialLoan."<hr/><hr/><hr/>";
						// GET SUM OF LOAN AND PAYMENT TO SUCH level
						$diff_allLoans_allPayments = ($allLoanSoFar - $allPaymenetSoFar);
						
						
						if($_countFirstSetOfPayments <=0)
						{
							//echo $e_month." TAYO";
						}
						
						$UnCalculatedInterest = (($initialLoan - $isThereAPaymentTheSameMonthLoanAWasTaken));
						
						
						if($diff_allLoans_allPayments <= 0)
						{
							// I will have to comment this out or set it to Zero ( 0 )
							$_cancelOutExcessPayment = $this->cancelOutExcessPayment($diff_allLoans_allPayments, $diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS);
							$amountLoan = $UnCalculatedInterest;
							
						}
						else
						{
							$amountLoan = $UnCalculatedInterest;
						}
						/*
						if($UnCalculatedInterest <= 0)
						{
							// GET THE SUM OF ALL PAYMENTS UP TO THE LEVEL AND DEDUC IT FROM SUM OF ALL LOANS UPTO THE LEVEL
							// IF ALLLOANS <= 0
							$amountLoan = 0;
							//$amountLoan = $UnCalculatedInterest;

						}
						else
						{
							$amountLoan = $UnCalculatedInterest;
						}
						*/
						
						 $_countFirstSetOfPayments = ($_countFirstSetOfPayments + 1);
						
					}
					else
					{
						
						$amountLoan =  $initialLoan ;
						
						// echo "<hr/><hr/><hr/><hr/>".$amountLoan."--<<( )>><hr/><hr/><hr/><hr/>";
						
						
						$_startFrom = $e_month;
						$_endHere = $this->nowPayment;
						
						$_t01_totalAmountPaid = $this->TotalAmountPaid_2($_startFrom, $_endHere);
						
						//echo "<hr/>".$_t01_totalAmountPaid."--<<( )>><hr/>";
						
						/* Control the counter
						if(($_t01_totalAmountPaid > 0) and ($_t01_totalAmountPaidCounter == 0))
						{
							
							$additonalSubtrationOFPaidAmount = (-$_t01_totalAmountPaid); // subtarct it from
						
						}
						$_t01_totalAmountPaidCounter = $_t01_totalAmountPaidCounter + 1;
						*/
					}
					
					
					/*---------------------------------------------------------------------------------------------------------*/
				
					$UnCalculatedInterest = (($initialLoan - $isThereAPaymentTheSameMonthLoanAWasTaken));
					
					
					/*
					$addUpPayment[$addUpPaymentCounter] = $allPaymenetSoFar;
					
					if($addUpPaymentCounter > 0)
					{
						$addAllPaymentsToALevel = $addUpPayment[$addUpPaymentCounter-1];
					}
					
					$getAllLoan = $this->TotalLoanTaken($e_month);
					
					
					$diff_btwnSumOfLoansAndSumOfPayBacks = ($getAllLoan - $addAllPaymentsToALevel);					
					
					
					$addUpPaymentCounter =  ($addUpPaymentCounter + 1);
					
					
					echo "<hr/><hr/><hr/><hr/> --) total loan so far --> ".$getAllLoan." for the month of ".$e_month ." Payment = ".$diff_btwnSumOfLoansAndSumOfPayBacks."<hr/><hr/><hr/><hr/>";
					
					// IF subtraction gives a -ve result... indicating that too much payback has been made.
					if($diff_btwnSumOfLoansAndSumOfPayBacks <= 0 )
					{
						$this->PaymentTrick($e_month);
						
						 $_PaymentBoxLocationNow = $this->nowPaymentBoxLocation;
						$_PaymentBoxLocationPrev = $this->previousPaymentBoxLocation;
						
						echo "<hr/>".$_PaymentBoxLocationNow."<hr/>";
						
						$this->LoanTakenUpto($_PaymentBoxLocationPrev);
						$_PrevPayment = $this->YesThereWasAPayment;
						$_PrevLoan = $this->YesThereIsALoan;
						
						//if()
						{
							
						}
					}
					
					$diff_allLoans_allPayments = ($allLoanSoFar - $allPaymenetSoFar);
					
					if($diff_allLoans_allPayments <= 0)
					{
						
						$this->PaymentTrick($e_month);
						
						$_PaymentBoxLocationNow = $this->nowPaymentBoxLocation;
						$_PaymentBoxLocationPrev = $this->previousPaymentBoxLocation;
						
						$this->LoanTakenUpto($_PaymentBoxLocationPrev);
						$_PrevPayment = $this->YesThereWasAPayment;
						$_PrevLoan = $this->YesThereIsALoan;
					
						//echo "<hr/><hr/><hr/> Previous Payments ".$_PrevPayment." for month " .$e_month. " (---<hr/><hr/><hr/>";
						//echo "<hr/><hr/><hr/> Difference = ".$diff_allLoans_allPayments." for month " .$e_month. " Payment = ".$allPaymenetSoFar ."(---<hr/><hr/><hr/>";
						//echo "<hr/><hr/><hr/><hr/> --) ".$differenceBtwnLoanAndPayBack." for the month of ".$e_month ." Payment = ".$addAllPaymentsToALevel."<hr/><hr/><hr/><hr/>";
					}
				
					*/
					/*-------------------------------------------------------------------------------------------------------	
						Check if there was or were payments made the same month loan was taken out	
					---------------------------------------------------------------------------------------------------------*/
					$UnCalculatedInterest = 0;
					
					//$amountLoan = 0;
					$this->MonthPayment($recieve_box_location);
					$isThereAPaymentTheSameMonthLoanAWasTaken =  $this->_monthPayment;
					
					$UnCalculatedInterest = (($amountReceiveWithoutCommas[$e_month]) - ($isThereAPaymentTheSameMonthLoanAWasTaken));
					
					if($isThereAPaymentTheSameMonthLoanAWasTaken > 0)
					{
						if($UnCalculatedInterest <= 0)
						{
							if($reset_loan) // One the calcutaion has began, if the difference is -ve, use the value
							{
								$amountLoan = $UnCalculatedInterest;
							}
							else
							{
								$amountLoan = 0; 
							}
						}
						else
						{
							$reset_loan = true;
							$amountLoan = $UnCalculatedInterest;
						}
					}
					else
					{
						if($UnCalculatedInterest <= 0)
						{
							if($reset_loan) // One the calcutaion has began, if the difference is -ve, use the value
							{
								$amountLoan = $UnCalculatedInterest;
							}
							else
							{
								$amountLoan = 0;
							}
						}
						else
						{
							$reset_loan = true;
							$amountLoan = $UnCalculatedInterest;
						}
					}
					
					/*---	This will loop number of months for the first set of loans 	---*/
					/*---------------------------------------------------------------------------------------------------------------
						Change calculation level as payments are made in-between other payments
					------------------------------------------------------------------------------------------------------------------*/
					$LoanBoxLocation = 0;
					$PaidBoxLocation = 0;
					$LoanBoxLocation = $e_month;
					
					$this->LoanTakenUpto($LoanBoxLocation);
					if($this->YesThereWasAPayment > 0)
					{
						$PaidBoxLocation = $e_month;
					}
					
					$this->previousPayment;
					$this->nowPayment;
		
					if($this->previousPayment > $LoanBoxLocation)
					{
						$diffResult = $LoanBoxLocation - $this->nowPayment;
						
						if($diffResult > 0)
						{
							$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS = $diffResult;
						}
					}
					
					/*----------------------------------------------------------------------------------------------------------------*/
					
					//$getAllLoan = $cashProjection->TotalLoanTaken($upto);
					$differenceBtwnLoanAndPayBack = 0;			
					$allPaymentSoFarTosin = $this->MonthPayment($recieve_box_location);
					
					$allLoanSoFarTosin = $this->LoanTakenUpto($recieve_box_location);
					
					$differenceBtwnLoanAndPayBack = (($allLoanSoFarTosin) - ($allPaymentSoFarTosin));
					
					//echo "<hr/>Month ".$e_month." Amount loan =  ".$amountLoan."*".$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS."<hr/>";
					
					
					/*---	 Call function to calculate the Interest per month	---*/
					$arrayCollectionOfInterests[$interestRateCounter] =  $this->calculateMonthlyInterest($amountLoan, $diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS);	
					
					
					/*-------------------------------------------------------------------------------------------------------
						This is to find the difference btw sum of all loan till current location and payment box location 
						(THIS HAS BEEN CANCELLED / COMMMENTED cos it affect other calculation wrongly)
					-------------------------------------------------------------------------------------------------------*/
					if($differenceBtwnLoanAndPayBack <= 0)
					{
						$_cancelOutExcessPayment = 0;
						
						/*---	Loop back and set the previos calculations to zerro	---*/
						$loopNumbers = count($arrayCollectionOfInterests);
						for($i= 0;  $i < $loopNumbers; $i++)
						{
							/*---	 UNCOMMENT THIS IF POSSIBLE,  BUT BE CAREFUL COS IT WILL AFEECT THE OTHER CALUCLATON	---*/
							//$arrayCollectionOfInterests[$i] = 0;
						}
					}
					if($amountReceiveWithoutCommas[$e_month] == 0)
					{
							
					}
					//echo "<hr/>Month ".$e_month." Amount loan =  ".$amountLoan."*".$diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS."<hr/>";
				}
				
				
				/* ---	Calculate First Payment (Do the calculation if diff is greater than 0 (Stop it from calculation loans taken out after payment made))	---*/
				elseif(($diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT > 0) and ($diff_btw_receieveBoxLocation_and_selfBoxLocationOTHER_PAYEMENTS == 0))
				{	
					/*-------------------------------------------------------------------------------------------------------	
						Check if there was or were payments made the same month loan was taken out	
					---------------------------------------------------------------------------------------------------------*/
					$UnCalculatedInterest = 0;
					$this->MonthPayment($recieve_box_location);
					$isThereAPaymentTheSameMonthLoanAWasTaken =  $this->_monthPayment;
					if($isThereAPaymentTheSameMonthLoanAWasTaken > 0)
					{
						$UnCalculatedInterest = (($amountReceiveWithoutCommas[$e_month]) - ($isThereAPaymentTheSameMonthLoanAWasTaken));
						
						if($UnCalculatedInterest <= 0)
						{
							$amountLoan = 0;
						}
						else
						{
							$amountLoan = $UnCalculatedInterest;
						}
					}
					else
					{
						
						$amountLoan = $amountReceiveWithoutCommas[$e_month];
					}
					/*---	This will loop number of months for the first set of loans 	---*/
					/*---	 Call function to calculate the Interest per month	---*/
					
					
					/*-----------------------------------------------------------------------------------------------------
						Check if there was a payment made before this payment 
						(Here, payments made earlier might have been more than the loan taken, that's why we have this bit)
					/*----------------------------------------------------------------------------------------------------*/
					
					$current_Payment = 0;
					$previous_Payment = 0;
					
					$this->DiffFromPaid($first_payment_box_location);
					
					$current_Payment  = $this->nowPayment;
					$previous_Payment = $this->previousPayment;
					$payment_Difference = ($previous_Payment - $current_Payment);
				
					if($payment_Difference == 0)
					{
					
					}
					elseif($diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT > $payment_Difference )	
					{
						$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT = $payment_Difference;
					}
					/*----------------------------------------------------------------------------------------------------*/
						
					//echo "<hr/><hr/><hr/><hr/><hr/>".$amountLoan." - ".$recieve_box_location."*".$diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT."<hr/><hr/><hr/><hr/><hr/>";
					//echo "<hr/><hr/><hr/><hr/>".$amountReceiveWithoutCommas[$e_month]."--) <hr/><hr/><hr/><hr/><hr/>";
					$arrayCollectionOfInterests[$interestRateCounter] =  $this->calculateMonthlyInterest($amountLoan, $diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT);	
				}
				else
				{
					//echo "<hr/><hr/><hr/><hr/>Check !!!<hr/><hr/><hr/><hr/><hr/>";
				} 
				$interestRateCounter = ($interestRateCounter + 1); 
				
			}
			elseif($amountReceiveWithoutCommas[$e_month] == 0) 
			{
				//echo "<hr/><hr/>No Loan<hr/><hr/>";	
			}
			
		}// End of loan months loop
		
		/*---	Add the actual amount Payback(capital repayment) to the interest Calculated	---*/
	
		$IntRate = ($interestRate / 100);
		
		$result =  (array_sum($arrayCollectionOfInterests) * $IntRate);
		
		//print_r($arrayCollectionOfInterests);
		
		$result =  ($result + $amountPaidBackWithoutCommas);
		
		if($_cancelOutExcessPayment != 0)
		{
			$result = ($result + (-$_cancelOutExcessPayment));
		}
		
		$result = number_format($result, 2, '.', '');
		return $result;
	}
	
	private function cancelOutExcessPayment($amountLoan, $diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT)
	{
		$result = 0;
		//$result = $this->calculateMonthlyInterest($amountLoan, $diff_btw_receieveBoxLocation_and_selfBoxLocationFIRST_PAYEMENT);	
		return $result;
	}
	
	/*----------------------------------------------------------------------------------------
		 Calculate Interest payable per month 
	-----------------------------------------------------------------------------------------*/
	public function calculateMonthlyInterest($amountReceived, $diff_in_loan_and_payback)
	{
		$monthsInAYear = 12;
		
		$result_01 = (($amountReceived * $diff_in_loan_and_payback) / $monthsInAYear);
		
		//echo "<hr/><hr/><hr/> RESULT ====>> &nbsp; ". $result_01 ."<hr/><hr/><hr/>";
		
		return $result_01;
	}
	
	
	
	
	/**********************************************************************************************************
	
		YEARLY INTEREST CALCULATION
	
	-------------------------------------------------------------------------
		Extract the Loans and Amount payback per year from the arrays of arguement . 
		Put the data into a new set of array for theotehr function to use. 
	-------------------------------------------------------------------------*/
	private function allYrlyloansAndPaymentBacks($yrlyLoan, $yrlyPayment, $upto)
	{
		$zeroPrefix = 0;
		$loopTime = $this->numberOfFinancialYrForcasting;
		
		if((!empty($loopTime)) and ($loopTime > 0))
		{
			for($e_year = 0; $e_year < $loopTime; $e_year++ )
			{
				$amountLoanYrly[$e_year] = $yrlyLoan[$e_year]["lir_total_per_yr"];
				$amountPaidBackYrly[$e_year] = $yrlyPayment[$e_year]["lip_total_per_yr"];
			}
		}
		return $this->TotalYrlyAmountLoanAndPaid($upto, $amountLoanYrly, $amountPaidBackYrly);
	}
	
	/*-------------------------------------------------------------------------
		Perform the trick by reducing the array key by one
		before returning the arrays back.
	-------------------------------------------------------------------------*/
	private function TotalYrlyAmountLoanAndPaid($upto, $amountLoanYrly, $yrlyPayment)
	{
		$totalLoan = array();
		$totalPayment = array();
		$result = array();
		
		/*---	
			This is where the trick is done. Deduct 1 from the the array key
			in order to get the data 1 level down and assign it to a new array	
		---*/
		$upto = ($upto - 1);
		
		if((($upto >= 0)))
		{
			for($i = 0; $i <= $upto; $i++)
			{
				$totalLoan[$i] = $amountLoanYrly[$i];
				$totalPayment[$i] = $yrlyPayment[$i];
			}
		}
		$result = array($totalLoan, $totalPayment);
		
		return $result;
	}
	
	/*-------------------------------------------------------------------------
		Request for all Loans and Payback
		You need to perform the trick for performing the calculation
	-------------------------------------------------------------------------*/
	public function CalculateYrlyInterest($interestRate, $paymentMadeWithoutInterest, $yrlyLoan, $yrlyPayment, $loan_box_location, $yrlyPayBack)
	{
		//$diffInTotalLoanAndPayment = 0;
		$IntRate = ($interestRate / 100);
		$carryForwardCalc  = 0;
		$sumOfYrly_difflyBtwLoanAndPayment = 0;
		$immediateDiffBtwLaonAndPayment = 0;
		$finalYrInterest  = 0;
		
		/*---	Create an object and assign the resuls to values below	---*/
		$arrayOfAllYrLoanAndAllYrPayment = $this->allYrlyloansAndPaymentBacks($yrlyLoan, $yrlyPayment, $loan_box_location);
		$yrTotalLoan = $arrayOfAllYrLoanAndAllYrPayment[0];
		$yrTotalPayment = $arrayOfAllYrLoanAndAllYrPayment[1];
		
		//$diffInTotalLoanAndPayment = ((array_sum($yrTotalLoan)) - (array_sum($yrTotalPayment)));
		
		/*---	loop through all years, add up the differences between the loan and payment	----*/
		$fromYr1ToImmediatePrevYrBoxLocation = ($loan_box_location - 1);
		for($i=0; $i <= $fromYr1ToImmediatePrevYrBoxLocation; $i++)
		{
			$sumOfYrly_difflyBtwLoanAndPayment += (($yrTotalLoan[$i]) - ($yrTotalPayment[$i]));
			$immediateDiffBtwLaonAndPayment = (($yrTotalLoan[$i]) - ($yrTotalPayment[$i]));
			
			/*---	This is for the system to make calculation where the summation is -ve 	---*/
			if($sumOfYrly_difflyBtwLoanAndPayment < 0)
			{
				$sumOfYrly_difflyBtwLoanAndPayment = 0;
			}
			
			//echo "<hr/>".$sumOfYrly_difflyBtwLoanAndPayment."<hr/>";
		}
		
		
		if($sumOfYrly_difflyBtwLoanAndPayment > 0) /*---	If the sum of previous year loan is more than the payment	---*/
		{
			 $carryForwardCalc = $sumOfYrly_difflyBtwLoanAndPayment;
		}
		elseif($immediateDiffBtwLaonAndPayment > 0) /*---	If the immediate year loan is more than the payment	---*/
		{
			$carryForwardCalc = $immediateDiffBtwLaonAndPayment;
		}
		else
		{
			$carryForwardCalc  = 0;
		}
			
		$finalYrInterest  = ($carryForwardCalc  * $IntRate);
		
		$calcInterestOnPaymentMadeWithoutInterest = ($paymentMadeWithoutInterest * $IntRate);
		
		$result  = ($finalYrInterest + $calcInterestOnPaymentMadeWithoutInterest + $yrlyPayBack);
		
		return	$result;
	}
	/********************************	END OF Yearly Interest Calculation	***********************************/
	
	
	
	/*-------------------------------------------------------------------------
		 Better one, get all cash projection data from first 2 tables and use 
	-------------------------------------------------------------------------*/
	public function getAllCashProjections($where, $orderDesc, $limit)
	{
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			return $this->allLoanInvestmentReceiveData($where, $orderDesc, $limit, $businessPlanId);
		}
		else
		{
			$businessPlanId = 0;
			return false;
		}
		
	}
	
	/*-------------------------------------------------------------------------
		Get all Loan / Investment Receive Cash projection data from 3 tables 
	---------------------------------------------------------------------------*/
	private function allLoanInvestmentReceiveData($where, $orderDesc, $limit, $businessPlanId)
	{
		$table = LOAN_INVESTMENT_TB.', '.LOAN_INVEST_12_M_RECEIVE_TB.', '.LOAN_INVEST_12_M_PAYMENT_TB;
		
		if(!empty($where)){$where .= " AND  loan_investment.loan_invest_bp_id = '$businessPlanId' AND  loan_investment.li_id = loan_investment_12m_received.limr_loan_investment_id 
																								  AND  loan_investment.li_id = loan_investment_12m_payment.limp_loan_investment_id GROUP BY  loan_investment.li_id";}
		else{$where = " 					loan_investment.loan_invest_bp_id = '$businessPlanId' AND  loan_investment.li_id = loan_investment_12m_received.limr_loan_investment_id 
																								  AND  loan_investment.li_id = loan_investment_12m_payment.limp_loan_investment_id GROUP BY  loan_investment.li_id";}
		
		$_getProjection = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		(int)$numberOfProjection = count($_getProjection);
		
		if($numberOfProjection >0)
		{
			$loanInvestData = $_getProjection ;
			//print_r($loanInvestData);
			return $this->FinancialForecastReceive($loanInvestData, $numberOfProjection);
		}
		else
		{
			return false;
		}
	}
	/*-------------------------------------------------------------
		Internal function Financial forecast
	---------------------------------------------------------------*/
	private function FinancialForecastReceive($loanInvestData, $numberOfProjection)
	{
		$financialTable	= LOAN_INVEST_FINANCIAL_F_RECEIVE_TB;
		
		for( $i=0; $i< $numberOfProjection; $i++)
		{
			$whereFin =  $loanInvestData[$i]['li_id']. " =  loan_investment_received_f_yrs.lir_loan_investment_id ";
			
			$_getProjectionFinancials = $this->db->select("*", $financialTable, $whereFin, "", "", "");
			
			$loanInvestData[$i]['financial_receive'] = $_getProjectionFinancials;
			
		}
		$progLoopCount = count($_getProjectionFinancials);
		$this->Projections = $loanInvestData;
	
		return $this->FinancialForecastPayment($this->Projections, $loanInvestData, $numberOfProjection, $progLoopCount);
	}
	
	/*-------------------------------------------------------------
		Internal function Financial forecast
	---------------------------------------------------------------*/
	private function FinancialForecastPayment($projections, $loanInvestData, $numberOfProjection, $progLoopCount)
	{
	
		$financialTablePayment	= LOAN_INVEST_FINANCIAL_F_PAYMENT_TB;
		
		for( $a=0; $a < $numberOfProjection; $a++)
		{
			$whereFinPayment =  $loanInvestData[$a]['li_id']. " =  loan_investment_payment_f_yrs.lip_loan_investment_id ";
			
			$_getProjectionFinancialsPayment = $this->db->select("*", $financialTablePayment, $whereFinPayment, "", "", "");
			
			$projections[$a]['financial_payment'] = $_getProjectionFinancialsPayment;
		}
		//print_r($projections);
		return $projections;
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
		$table = LOAN_INVESTMENT_TB;
		$where = "";
		
		$getMaxLoanInvestId = $this->db->select("MAX(li_id)", $table, $where, "", "");
		if(count($getMaxLoanInvestId) > 0)
		{	
			$latestEmployeeId = $getMaxLoanInvestId[0]['MAX(li_id)'];
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

	
	
	
	private function updateLoanInvestment($loanInvestId, $postedProjectionName, $type_of_funding, $loan_invest_interest_rate, $loan_invest_years_to_pay, $loan_invest_pays_per_years)
	{
		$isOK = false;
		$table = LOAN_INVESTMENT_TB;
		$where = "li_id = '$loanInvestId'";
		$setColumn = "loan_invest_name = '$postedProjectionName', type_of_funding = '$type_of_funding', loan_invest_interest_rate = '$loan_invest_interest_rate', loan_invest_years_to_pay='$loan_invest_years_to_pay', loan_invest_pays_per_years='$loan_invest_pays_per_years'";
		
		
		if($this->db->update($table, $setColumn, $where))
		{
			$isOK = true;
		}
		return $isOK;	
	}
	
	/*-------------------------------------------------------------
		Update tables 12 months and financial forecast
	---------------------------------------------------------------*/
	public function _updateProjection($loanInvestId)
	{
		$isOK = false;
		
		$postedProjectionName = htmlentities(addslashes($_POST['latestLoanInvestName']),ENT_COMPAT, "UTF-8");
		$type_of_funding = $_POST['type_of_funding'];
		$loan_invest_interest_rate = $_POST['loan_invest_interest_rate'];
		$loan_invest_years_to_pay = $_POST['loan_invest_years_to_pay'];
		$loan_invest_pays_per_years = $_POST['loan_invest_pays_per_years'];
		// Update tables
		$projectionTbOK = $this->updateLoanInvestment($loanInvestId, $postedProjectionName, $type_of_funding, $loan_invest_interest_rate, $loan_invest_years_to_pay, $loan_invest_pays_per_years);
		
		$updateTbs = new FormData();
		$updateTbs->prepare12MonthProjectionFormData();
		$monthsUpdate_receiveQuery = $updateTbs->receivedMonthsUpdateQueryString;
		//$monthsUpdate_paymentQuery = $updateTbs->paymentMonthsUpdateQueryString;
		$receive_financialYearArray = $updateTbs->projectionsReceiveUpdateDataInArray;
		//$payment_financialYearArray = $updateTbs->projectionsPaymentUpdateDataInArray;
		
		//if($this->updateMonthsProjectionTables($loanInvestId, $monthsUpdate_receiveQuery, $monthsUpdate_paymentQuery))
		if($this->updateMonthsProjectionTables($loanInvestId, $monthsUpdate_receiveQuery))
		{
			if($this->updateFinancialYearTable($loanInvestId, $receive_financialYearArray))
			{
				$isOK = true;
			}	
		}
		
		return $isOK;
	}
	
	//private function updateMonthsProjectionTables($loanInvestId, $monthsUpdate_receiveQuery, $monthsUpdate_paymentQuery)
	private function updateMonthsProjectionTables($loanInvestId, $monthsUpdate_receiveQuery)
	{
		$isOK = false;
		$receive12MonthTable = LOAN_INVEST_12_M_RECEIVE_TB;
		$whereReceive = "limr_loan_investment_id = ".$loanInvestId;
		
		//$payment12MonthTable = LOAN_INVEST_12_M_PAYMENT_TB;
		//$wherePayment = "limp_loan_investment_id = ".$loanInvestId;
		
		$update_receive12MonthTable = $this->updateMonthsTable($receive12MonthTable, $monthsUpdate_receiveQuery, $whereReceive);
		
		//$update_payment12MonthTable = $this->updateMonthsTable($payment12MonthTable, $monthsUpdate_paymentQuery, $wherePayment);
		
		//if(($update_receive12MonthTable == true) and ($update_payment12MonthTable == true))
		if($update_receive12MonthTable == true)
		{
			$isOK = true;
		}
		else
		{
			$isOK = false;	
		}
		return $isOK;
	}
	
	
	/*-------------------------------------------------------------
		Update financial forecast table for expenditure
	---------------------------------------------------------------*/
	//private function updateFinancialYearTable($loanInvestId, $receive_financialYearArray, $payment_financialYearArray)
	private function updateFinancialYearTable($loanInvestId, $receive_financialYearArray)
	{
		$isOK = false;
		
		$n0FinancialForecast = $this->numberOfFinancialYrForcasting;
		$table_receive = LOAN_INVEST_FINANCIAL_F_RECEIVE_TB;
		//$table_payment = LOAN_INVEST_FINANCIAL_F_PAYMENT_TB;	
		

		$counter = 0;
		// loop through the number of forecast set
		for ($x=1; $x <= $n0FinancialForecast; $x++) 	
		{
			$financialYear = "yr_".$x;
			
			$financialYearData_receive = $receive_financialYearArray[$counter];
			//$financialYearData_payment = $payment_financialYearArray[$counter];
			
			$whereFY_receive = "  lir_loan_investment_id ='$loanInvestId' 
														and loan_investment_received_f_yrs.lir_year";
		
			//$whereFY_payment = "  loan_investment_payment_f_yrs.lip_loan_investment_id = '$loanInvestId' 
			//											and loan_investment_payment_f_yrs.lip_year";
			
			$setYearColumn_receive = "lir_total_per_yr	 = '$financialYearData_receive'";
			//$setYearColumn_payment = "lip_total_per_yr	 = '$financialYearData_payment'";
			
			
			$whereFY_receive = $whereFY_receive." = '$financialYear'";
			
			//$whereFY_payment = $whereFY_payment." = '$financialYear'";
			
			///print_r($whereFY_receive."<hr/>");
			
			$updateReceiveFYTable = $this->db->update($table_receive, $setYearColumn_receive, $whereFY_receive);
			
			//$updatePaymentFYTable = $this->db->update($table_payment, $setYearColumn_payment, $whereFY_payment);
			
			//if(($updateReceiveFYTable == true) and ($updatePaymentFYTable == true))
			if($updateReceiveFYTable == true)
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
	
	
	
	/*-------------------------------------------------------------
		Update month table
	---------------------------------------------------------------*/
	private function updateMonthsTable($table, $updateQuery,$where)
	{
		$isOK = false;
		
		if($this->db->update($table, $updateQuery, $where))
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
	public function _update12MonthData($loanInvestForecastId, $updateQuery, $table, $where)
	{
		return $this->updateMonthsTable($loanInvestForecastId, $updateQuery, $table, $where);
	}
	
	

	
	/*--------------------------------------------------------------------
		Public function to access Update Sales forecast table 
	--------------------------------------------------------------------*/
	public function _updateSalesForecastTable($loanInvestForecastId, $saleForecastName)
	{
		$postedSaleForecastName = htmlentities(addslashes($_POST['sales_forecast_name']),ENT_COMPAT, "UTF-8");	
		
		return $this->updateSalesForecastTable($loanInvestForecastId, $saleForecastName);
	}
	
	/*-----------------------------------------------------------------
		Public Delete function to delete al assosiated sales Forecast
	------------------------------------------------------------------*/
	public function deleteLoanInvestProjection($loanInvestForecastId)
	{
		$isOK = false;
		
		$table01 = LOAN_INVESTMENT_TB;  $queryString01 = " li_id = ".$loanInvestForecastId;
		$table02 = LOAN_INVEST_12_M_RECEIVE_TB; $queryString02 = " limr_loan_investment_id = ".$loanInvestForecastId;
		$table03 = LOAN_INVEST_12_M_PAYMENT_TB; $queryString03 = " limp_loan_investment_id = ".$loanInvestForecastId;
		
		$table04 = LOAN_INVEST_FINANCIAL_F_RECEIVE_TB; $queryString04 = " lir_loan_investment_id = ".$loanInvestForecastId;
		$table05 = LOAN_INVEST_FINANCIAL_F_PAYMENT_TB; $queryString05 = " lip_loan_investment_id = ".$loanInvestForecastId;
		
		
		if($this->db->delelet($table05, $queryString05))
		{
			if($this->db->delelet($table04, $queryString04))
			{
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
	
	public function getInterestIncurred() {
		$cash_projections = $this->getAllCashProjections("", "", "");
		$amount_received = 0;
		$monthly_total_interest_incurred = array();
		$monthly_repayments = array();
		$monthly_interests  = array();
		$monthly_balances   = array();
		$total_monthly_interests = array();
		
		$monthly_balance = 0;
		$monthly_repayment = 0;
		
		$total_interest_year = 0;
		
		
		foreach ($cash_projections as $projection) {
			$name = $projection['loan_invest_name'];
			
			$monthly_repayments[$name] = array();
			$monthly_interests[$name]   = array();
			$monthly_balances[$name]    = array();
			
			$interest = $projection['loan_invest_interest_rate'];
			$years    = $projection['loan_invest_years_to_pay'];
			$per_year = $projection['loan_invest_pays_per_years'];
			$interest_per_month = $interest/$per_year;
			$total_interest = $years * $per_year;
			
			$monthly_balance = 0;
			$monthly_repayment = 0;
			$amount_received = 0;
			
			for ($i = 0; $i < 12; $i++) {
				$key_number = $i + 1;
				$month_key = ($key_number < 10) ? ('0' . $key_number) : $key_number;
				$monthly_received = $projection['limr_month_' . $month_key];
				$amount_received += $monthly_received;
				
				$monthly_repayment = ($monthly_balance == 0) ? 0 : ($amount_received == 0 ? 0 : (PHPExcel_Calculation_Financial::PMT($interest_per_month/100, $total_interest, $amount_received) * -1));
				$monthly_interest  = ($monthly_balance == 0 ? 0 : (PHPExcel_Calculation_Financial::IPMT($interest_per_month,1, $total_interest, $amount_received) * -1) / 100);
				$monthly_balance   = $monthly_balance + $monthly_received - $monthly_repayment + $monthly_interest;
				
				$monthly_repayments[$name][]  = round($monthly_repayment);
				$monthly_interests[$name][]    = round($monthly_interest);
				$monthly_balances[$name][]     = round($monthly_balance);
				$total_monthly_interests[$i] += round($monthly_interest);
				$total_interest_year         += $monthly_interest;
			}
		}
		
		$total_interest_year = round($total_interest_year);
		
		return array(
			array($total_interest_year, $total_interest_year, $total_interest_year), 
			$total_monthly_interests
		);
	}
	
	private function PMT($interest, $num_of_payments, $PV, $FV = 1, $Type = 0) {
		$xp = pow((1+$interest), $num_of_payments);
		return ($PV * $interest * $xp / ($xp - 1) + $interest / ($xp - 1) * $FV) * ($Type==0 ? 1 : 1/($interest+1));
	}
	
		
	public function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
}// end of class
?>