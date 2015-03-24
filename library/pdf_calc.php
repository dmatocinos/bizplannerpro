<?php

    $expenditure    = new expenditure_lib();	
	$incomeTaxRate  =  $expenditure->incomeTaxRate;
	
	$sales          = new sales_forecast_lib();
	$allSalesDetails= $sales->getAllSales("", "", "");
	
	// ==============
	//begin revenue calculation /all_calculations/revenue_calc.php
	$arraySalesSummation = array();
	$counter = 0;
	
	if($allSalesDetails > 0)
	{
		foreach($allSalesDetails as $expDetails)
		{
				$totaSaleCounter = 0;
				
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					 $arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
				}
				$counter = $counter+1;
		}// end foreach  
		
		$revenue = $arraySalesSummation;
	
	}
    
    $totalSalesCounter = 0;
			
    foreach($arraySalesSummation as $sumOfAllSales)
    {
        $totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));
        $totalSales_format[$totalSalesCounter] = number_format(array_sum($sumOfAllSales), 0, '.', ',');
        $totalSalesCounter = $totalSalesCounter + 1;
    }
       
    //end revenue calculation
    
    
    
    
    
    
    
    
    
    
    // ==============
    //begin direct cost calculation /all_calculations/direct-cost_calc.php
    $arrayCostSummation = array();
    
	if($allSalesDetails)
	{
	    foreach($allSalesDetails as $expDetails)
		{
		    for($i=0; $i< count($expDetails['financial_status']); $i++)
			{
			    $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
			}
				$counter = $counter+1; 
		}// end foreach 
			
            
        //   TOTAL DIRECT COST SECTION
            
        $totalCostCounter = 0;
            
        foreach($arrayCostSummation as $sumOfAllCost)
        {
            $totalDirectCost[$totalCostCounter] = (array_sum($sumOfAllCost));
            $totalDirectCost_format[$totalCostCounter] = number_format(array_sum($sumOfAllCost), 0, '.', ',');
            $totalCostCounter = $totalCostCounter + 1;	
        }
            
    
        foreach($allSalesDetails as $expDetails)
        {
            for($i=0; $i< count($expDetails['financial_status']); $i++)
            {
                $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
            }
                
            $counter = $counter+1; 
        }// end foreach 
	}
    //end direct cost calculation
    
    
    
    
    
    
    
    
    
    
    
    
    // ==============
    // begin gross margin calculation /all_calculations/gross_margin_calc.php
    $grossMarginCounter = 0;
    
	$grossMargin = array();
	
    foreach($arrayCostSummation as $sumOfAllCost)
    {
        $grossMargin[$grossMarginCounter] = (($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]));
        $grossMargin_format[$grossMarginCounter] = number_format(($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]), 0, '.', ',');
   
    	$grossMarginCounter = $grossMarginCounter + 1;
    }
	
	
    //GROSS MARGIN PERCENTAGE SECTION
 
    $grossMarginPercentageCounter = 0;
    
    foreach($arrayCostSummation as $sumOfAllCost)
    {
        // Avoid Division by zero in 
        if($totalSales[$grossMarginPercentageCounter] == 0)
        {
            $grossMarginPercentage[$grossMarginPercentageCounter] = 0;
        }
        else
        {
            $grossMarginPercentage[$grossMarginPercentageCounter] = (($grossMargin[$grossMarginPercentageCounter] * 100) /  $totalSales[$grossMarginPercentageCounter]);	
        }
        // Format $grossMarginPercentage
        $grossMarginPercentage[$grossMarginPercentageCounter] = number_format($grossMarginPercentage[$grossMarginPercentageCounter], 0, '.', ',');
        $grossMarginPercentageCounter = $grossMarginPercentageCounter + 1;
    }
    // end gross margin calculation
    
    // =====================
    // begin total expenses calculation /all_calculations/total-expenses_calc.php
    $expenditure    = new expenditure_lib();
	$employee       = new employee_lib();
	$allExpDetails  = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
	$allEmpDetails  = $employee->getAllEmployeeDetails2("", "", ""); // All employees

    $counter = 0;
    $arraySummation = "";
    // Related Expenses calculation
    (int)$personalRelatedExpenses       = $_SESSION['bpRelatedExpensesInPercentage'];
    $personalRelatedExpenseInPercentage = ($personalRelatedExpenses / 100);
        
    /*---------------------------------------------------------------------
            Employee Salary Calculation loop using the same counter and 
            array summation for both allEmployee and all exenditure
    ---------------------------------------------------------------------*/
    if($allEmpDetails)
		{
			foreach($allEmpDetails as $empDetails)
			{
				//$empDetails = number_format($empDetails);
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					 $arraySummation[$i][$counter]  = ($empDetails['financial_status'][$i]['total_per_yr']);
				 }
				 $counter = $counter+1;
			}
			
	        /*---------------------------------------------------------------------
                Calculate Employee Related Expenses 	
            ---------------------------------------------------------------------*/
          	foreach($allEmpDetails as $empDetails)
			{		
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					 $arraySummation[$i][$counter]  = ($personalRelatedExpenseInPercentage * $empDetails['financial_status'][$i]['total_per_yr']);
				}
				$counter = $counter+1;
			} 
           
		}// -----------	End of $allEmpDetails is true	-----------
      
       if($allExpDetails)
	   {
			foreach($allExpDetails as $expDetails)
			{
					for($i=0; $i< count($expDetails['financial_status']); $i++)
					{
						 $arraySummation[$i][$counter]  = $expDetails['financial_status'][$i]['total_per_yr'];
					}
					$counter = $counter+1;
					 
			}// end foreach
			
			
			
			//Total Expsenses
			
			$y = 0;
			$allExpense = array();
			foreach($arraySummation as $sumOfAllExpenses)
			{
				$allExpense[$y] = array_sum($sumOfAllExpenses);
				$eachYear[$allExpDetails[0]['financial_status'][$y]['financial_year']] = $allExpense[$y];
				$y = $y+1;
			}
	   }// end of if $allExpDetails
    // end total expenses calculation
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //begin loan and investments /all_calculations/loans-and-investments_calc.php

$cashProjection = new loansInvestments_lib();

if(isset($_GET['edit_loanInvestID']))
{
	(int)$cashProjectionID = $_GET['edit_loanInvestID'];	
	$whereEdit = " loan_investment.li_id != ".$cashProjectionID;
	$allcashProjection = $cashProjection->getAllCashProjections($whereEdit, "", "");
}


else if(isset($_GET['add']) and ($_GET['add'] == "new_projection"))
{
	(int)$cashProjectionID = $cashProjection->maxEmployeeId;
	$whereEditLatest = "loan_investment.li_id != ".$cashProjectionID;
	$allcashProjection = $cashProjection->getAllCashProjections($whereEditLatest, "", "");
}
else
{
	$allcashProjection = $cashProjection->getAllCashProjections("", "", "");
}

	$_loanInvestment = new loansInvestments_lib();
	
	list($yearly_interest_incurred, $_interestIncured) = $_loanInvestment->getInterestIncurred();
	$this->profitlossdata['monthlyinterestincurredrows'] = array_merge(array('Interest Incurred'), $_interestIncured);
	$this->profitlossdata['monthlyinterestincurred']     = $_interestIncured;
  
        
    //end loan and investments
    
    
    //begin interest incurred calculation
	// Update 08/June/2013
	$_loanInvestment = new loansInvestments_lib();
	$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
	//print_r($allloanInvestmentProjection);
	$sumOfEachLoan = "";

    $_yrlyCalcInterest = array();
    $array_interestIncuredCounter = 0;
    $array_interestIncured = array();
    $currency = $sales->defaultCurrency;
    
    
    if(isset($_interestIncured))
    {
        $_yrlyCalcInterest =  $_interestIncured;
    }
    else
    {
        $_yrlyCalcInterest = 0;
    }
    
    
    if((!empty($_yrlyCalcInterest)) || ($_yrlyCalcInterest > 0)  )
	{
	 // loop through this for number of years
		foreach($_yrlyCalcInterest as $yrInterestIncured)
		{
			//print_r($yrInterestIncured);
			$array_interestIncured[$array_interestIncuredCounter] = array_sum($yrInterestIncured);
			$array_interestIncuredCounter = $array_interestIncuredCounter + 1; 
		} 
	}

	//end interest incurred calculation
    
    
    
    
    
    
    
    
    
    
    //begin acount receivable account-receivable_calc.php
    $daysCollectPayment = "";
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			
			$cashFlow = new cashFlowProjection_lib();
			$getPayments = $cashFlow->Payments($businessPlanId);
		}
		
		
		$sales = new sales_forecast_lib();
		$allSalesDetails = $sales->getAllSales("", "", "");
		
		//print_r($allSalesDetails);
		
		if($getPayments)
		{
			$percentageSale = $getPayments[0]['percentage_sale'];
			$daysCollectPayment = $getPayments[0]['days_collect_payments'];
		}
		$monthsCollectPayment = ($daysCollectPayment / 30);
		// CHeck if decimal
		if(strpos($monthsCollectPayment,".") !== false)
		{
			// Is a decimal;
			$monthsCollectPaymentDecimal = true;
		}
		else
		{
			//	Not a decimal;
			$monthsCollectPaymentDecimal = false;
		}
		
		//echo highlight_string(var_export($allSalesDetails, TRUE));
		
		
		// loop to get all months from the back
		if($allSalesDetails)
		{	
			$array_year = array();
			$year2_calc = 0;
			$year3_calc = 0;
			$accountReceivable_allYears = array();
			
			$loop_time = (12 - $monthsCollectPayment);
			
			
			$accountReceivable_allMonths = array();
			
			$zeroPrefix = 0;
			// Loop through each sale forcast
			for($each_sale_forcast = 0; $each_sale_forcast < count($allSalesDetails); $each_sale_forcast++)
			{
				$zeroCounter  = 0 ;
				
				for($e_month = 12; $e_month > $loop_time; $e_month-- )
				{
					if($e_month < 10)
					{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
						$e_month = $zeroPrefix.$e_month;
					}
					
					$collect_each_sale_forecast[$each_sale_forcast][$e_month] =  $allSalesDetails[$each_sale_forcast]['month_'.$e_month];
					
					
					/*---	If the divisin of $monthsCollectPayment is in decimal	---*/
					if($monthsCollectPaymentDecimal)
					{
						$whereToStopLoop = ($e_month);
						$newDataOfArray = $collect_each_sale_forecast[$each_sale_forcast][$whereToStopLoop];
						$newDataOfArray = ($newDataOfArray / 2);
						$collect_each_sale_forecast[$each_sale_forcast][$whereToStopLoop] = $newDataOfArray;
						
					}
				}
				
				//echo highlight_string(var_export($collect_each_sale_forecast, TRUE));
				
					
				
				if(empty($collect_each_sale_forecast))
				{
					$collect_each_sale_forecast = 0;
					$accountReceivable_01[$each_sale_forcast] = 0;
				}
				else
				{
					$accountReceivable_01[$each_sale_forcast] = (array_sum($collect_each_sale_forecast[$each_sale_forcast]) *  $allSalesDetails[$each_sale_forcast]['price']);
					
					foreach ($collect_each_sale_forecast as $key => $value) {						
						foreach($value as $key1 => $value1) {
							if(isset($accountReceivable_allMonths[$key1])) {
								$accountReceivable_allMonths[$key1] += $value1 * $allSalesDetails[$each_sale_forcast]['price'];
							} else {							
								$accountReceivable_allMonths[$key1] = ($value1 * $allSalesDetails[$each_sale_forcast]['price']);							
							}							
						}					
					}
					
					
					//echo highlight_string(var_export($accountReceivable_allMonths, TRUE));
					
					
					
				}
				
				
				
				
				
				// Start from 1 meaning you start the calculation from years 2 upward. Year one has been calculated above
				for($f_status = 1; $f_status < count($allSalesDetails[$each_sale_forcast]['financial_status']); $f_status++ )
				{
					// Year forcast start from year 2 upward
					$yearForcast = ($allSalesDetails[$each_sale_forcast]['financial_status'][$f_status]['total_per_yr']);
					
					// Divide it by 12 to get each month's amount
					$each_month_amount = ($yearForcast / 12);
					
					// Get the amount of months and divide by 30 to find how many months
					// i.e 45 / 30 = 1.5
					$no_of_months = ($daysCollectPayment / 30);
					
					$array_year[$each_sale_forcast][$f_status] =  (($each_month_amount * $no_of_months) * $allSalesDetails[$each_sale_forcast]['price']);
					
					// add them array up together
					if($f_status == 1) // Year 2
					{
						$array_year[$each_sale_forcast][$f_status];
						$year2_calc += $array_year[$each_sale_forcast][$f_status];
						
					}
					else if($f_status == 2) // Year 3
					{
						$array_year[$each_sale_forcast][$f_status];
						$year3_calc += $array_year[$each_sale_forcast][$f_status];
					}
				}
			}
			
			$accountReceivable_Year_01 = (array_sum($accountReceivable_01) * ($percentageSale / 100));
			$accountReceivable_Year_02 =  ($year2_calc * ($percentageSale / 100));
			$accountReceivable_Year_03  = ($year3_calc * ($percentageSale / 100));
			
			$accountReceivable_allYears[0] = $accountReceivable_Year_01;
			$accountReceivable_allYears[1] = $accountReceivable_Year_02;
			$accountReceivable_allYears[2] = $accountReceivable_Year_03;
		}
		/*---	Return Array $accountReceivable_allYears --*/
    
    //end account receivable
    
    
    //begin account payable
    
    $daysCollectPayment = "";
    	if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
		
			$cashFlow = new cashFlowProjection_lib();
			$getPayments = $cashFlow->Payments($businessPlanId);
		}
		
	
		$sales = new sales_forecast_lib();
		$allSalesDetails = $sales->getAllSales("", "", "");
		
		//print_r($getPayments);
		if($getPayments)
		{
			$percentageSale = $getPayments[0]['percentage_purchase'];
			$daysCollectPayment = $getPayments[0]['days_make_payments'];
		}
		
		$monthsCollectPayment = ($daysCollectPayment / 30);
		
		// Check if decimal
		if(strpos($monthsCollectPayment,".") !== false)
		{
			// Is a decimal;
			$IsMonthsCollectPaymentDecimal = true;
		}
		else
		{
			//	Not a decimal;
			$IsMonthsCollectPaymentDecimal = false;
		}
		
		//echo "<br/><br/><br/><hr/>";
		//print_r($allSalesDetails);
		// loop to get all months from the back
		if($allSalesDetails) 
		{	
			$array_year = array();
			$year2_calc = 0;
			$year3_calc = 0;
		 	$accountPayable_allYears = array();
		 	
			$loop_time = (12 - $monthsCollectPayment);
			
			$zeroPrefix = 0;
			
			/*------------------------------------------------------------------------------		
			/*	Calculation for Year 1 using the months. Loop through each sale forcast
			/*------------------------------------------------------------------------------*/
			for($each_sale_forcast = 0; $each_sale_forcast < count($allSalesDetails); $each_sale_forcast++)
			{
				$zeroCounter  = 0 ;
				
				for($e_month = 12; $e_month > $loop_time; $e_month-- )
				{
					if($e_month < 10)
					{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
						$e_month = $zeroPrefix.$e_month;
					}
					
					$collect_each_sale_forecast[$each_sale_forcast][$e_month] =  $allSalesDetails[$each_sale_forcast]['month_'.$e_month];
				}
				
				/*---	If the divisin of $monthsCollectPayment is in decimal	---*/
				if($IsMonthsCollectPaymentDecimal)
				{
					$whereToStopLoop = ($e_month + 1);
					$newDataOfArray = $collect_each_sale_forecast[$each_sale_forcast][$whereToStopLoop];
					$newDataOfArray = ($newDataOfArray / 2);
					$collect_each_sale_forecast[$each_sale_forcast][$whereToStopLoop] = $newDataOfArray;
					
				}
				if(empty($collect_each_sale_forecast))
				{
					$collect_each_sale_forecast = 0;
					$accountPayable_01[$each_sale_forcast] = 0;
				}
				else
				{
					$accountPayable_01[$each_sale_forcast] = (array_sum($collect_each_sale_forecast[$each_sale_forcast]) *  $allSalesDetails[$each_sale_forcast]['cost']);
				}
				
				
				/*------------------------------------------------------------------------------		
				/*	Calculation for year 2 and 3
				/*------------------------------------------------------------------------------*/
				
				// Start from 1 meaning you start the calculation from years 2 upward. Year one has been calculated above
				for($f_status = 1; $f_status < count($allSalesDetails[$each_sale_forcast]['financial_status']); $f_status++ )
				{
					// Year forcast start from year 2 upward
					$yearForcast = ($allSalesDetails[$each_sale_forcast]['financial_status'][$f_status]['total_per_yr']);
					
					// Divide it by 12 to get each month's amount
					 $each_month_amount = ($yearForcast / 12);
					
					// Get the amount of months and divide by 30 to find how many months
					// i.e 45 / 30 = 1.5
					$no_of_months = ($daysCollectPayment / 30);
					
					$array_year[$each_sale_forcast][$f_status] =  (($each_month_amount * $no_of_months) * $allSalesDetails[$each_sale_forcast]['cost']);
					
					// add them array up together
					if($f_status == 1) // Year 2
					{
						$array_year[$each_sale_forcast][$f_status];
						$year2_calc += $array_year[$each_sale_forcast][$f_status];
						
					}
					else if($f_status == 2) // Year 3
					{
						$array_year[$each_sale_forcast][$f_status];
						$year3_calc += $array_year[$each_sale_forcast][$f_status];
					}
				}
			}
			
			$accountPayable_Year_01 = (array_sum($accountPayable_01) * ($percentageSale / 100));
			$accountPayable_Year_02 =  ($year2_calc * ($percentageSale / 100));
			$accountPayable_Year_03  = ($year3_calc * ($percentageSale / 100));
			
			$accountPayable_allYears[0] = $accountPayable_Year_01;
			$accountPayable_allYears[1] = $accountPayable_Year_02;
			$accountPayable_allYears[2] = $accountPayable_Year_03;
			
		}
		
		
		
		/*------------------------------------------------------------------------------------------------	
		/*------------------------------------------------------------------------------------------------	
			Calculation From budget Table
		/*------------------------------------------------------------------------------------------------	
		/*------------------------------------------------------------------------------------------------*/
		$allExpDetails = $expenditure->getAllExpenditureDetails("", "", "");
		
	
		
		if($allExpDetails) 
		{
			$year2_calc_bdgt = 0;
			$year3_calc_bdgt = 0;
		 	$accountPayable_allYears_bdgt = array();
		 	
			$Total_accountPayable_allYears_bdgt = array();
			
			$loop_time_bdgt = (12 - $monthsCollectPayment);
			
			$zeroPrefix_bdgt = 0;
			
			/*------------------------------------------------------------------------------		
			/*	Calculation for Year 1 using the months. Loop through each sale forcast
			/*------------------------------------------------------------------------------*/
			
			$monthlyPayable = array();
			
			
			for($each_sale_forcast_bdgt = 0; $each_sale_forcast_bdgt < count($allExpDetails); $each_sale_forcast_bdgt++)
			{
				$zeroCounter_bdgt  = 0 ;
				
				for($e_month_bdgt = 12; $e_month_bdgt > $loop_time_bdgt; $e_month_bdgt-- )
				{
					if($e_month_bdgt < 10)
					{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
						$e_month_bdgt = $zeroPrefix_bdgt.$e_month_bdgt;
					}
					
					$collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt][$e_month_bdgt] =  $allExpDetails[$each_sale_forcast_bdgt]['month_'.$e_month_bdgt];
				}
				
				/*---	If the divisin of $monthsCollectPayment is in decimal	---*/
				if($IsMonthsCollectPaymentDecimal)
				{
					$whereToStopLoop_bdgt = ($e_month + 1);
					$newDataOfArray_bdgt = $collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt][$whereToStopLoop_bdgt];
					$newDataOfArray_bdgt = ($newDataOfArray_bdgt / 2);
					$collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt][$whereToStopLoop_bdgt] = $newDataOfArray_bdgt;
					
				}
				//echo "<br/>";
				
				//print_r($collect_each_sale_forecast_bdgt);
				
				if (isset($monthlyPayable[$whereToStopLoop_bdgt])) {
					$monthlyPayable[$whereToStopLoop_bdgt] += $collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt][$whereToStopLoop_bdgt];
				} else {
					$monthlyPayable[$whereToStopLoop_bdgt] = $collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt][$whereToStopLoop_bdgt];
				} 
				
				
				
				if(empty($collect_each_sale_forecast_bdgt))
				{
					$collect_each_sale_forecast_bdgt = 0;
					$accountPayable_01_bdgt[$each_sale_forcast_bdgt] = 0;
				}
				else
				{
					$accountPayable_01_bdgt[$each_sale_forcast_bdgt] = (array_sum($collect_each_sale_forecast_bdgt[$each_sale_forcast_bdgt]));
				}
				
				
				
				/*------------------------------------------------------------------------------		
				/*	Calculation for year 2 and 3
				/*------------------------------------------------------------------------------*/
				
				// Start from 1 meaning you start the calculation from years 2 upward. Year one has been calculated above
				for($f_status_bdgt = 1; $f_status_bdgt < count($allExpDetails[$each_sale_forcast_bdgt]['financial_status']); $f_status_bdgt++ )
				{
					// Year forcast start from year 2 upward
					$yearForcast_bdgt = ($allExpDetails[$each_sale_forcast_bdgt]['financial_status'][$f_status_bdgt]['total_per_yr']);
					
					// Divide it by 12 to get each month's amount
					 $each_month_amount_bdgt = ($yearForcast_bdgt / 12);
					
					// Get the amount of months and divide by 30 to find how many months
					// i.e 45 / 30 = 1.5
					$no_of_months_bdgt = ($daysCollectPayment / 30);
					
					$array_year_bdgt[$each_sale_forcast_bdgt][$f_status_bdgt] =  (($each_month_amount_bdgt * $no_of_months_bdgt));
					
					// add them array up together
					if($f_status_bdgt == 1) // Year 2
					{
						$array_year_bdgt[$each_sale_forcast_bdgt][$f_status_bdgt];
						$year2_calc_bdgt += $array_year_bdgt[$each_sale_forcast_bdgt][$f_status_bdgt];
						
					}
					else if($f_status_bdgt == 2) // Year 3
					{
						$array_year_bdgt[$each_sale_forcast_bdgt][$f_status_bdgt];
						$year3_calc_bdgt += $array_year_bdgt[$each_sale_forcast_bdgt][$f_status_bdgt];
					}
				}
			}
			$accountPayable_Year_01_bdgt = (array_sum($accountPayable_01_bdgt) * ($percentageSale / 100));
			$accountPayable_Year_02_bdgt =  ($year2_calc_bdgt * ($percentageSale / 100));
			$accountPayable_Year_03_bdgt  = ($year3_calc_bdgt * ($percentageSale / 100));
			 
			$accountPayable_allYears_bdgt[0] = $accountPayable_Year_01_bdgt;
			$accountPayable_allYears_bdgt[1] = $accountPayable_Year_02_bdgt;
			$accountPayable_allYears_bdgt[2] = $accountPayable_Year_03_bdgt;
			
			$Total_accountPayable_allYears_bdgt[0] = ($accountPayable_allYears[0] + $accountPayable_allYears_bdgt[0])."<br/>";
			$Total_accountPayable_allYears_bdgt[1] = ($accountPayable_allYears[1] + $accountPayable_allYears_bdgt[1])."<br/>";
			$Total_accountPayable_allYears_bdgt[2] = ($accountPayable_allYears[2] + $accountPayable_allYears_bdgt[2]);
		}
		/*---	Return Array $Total_accountPayable_allYears_bdgt --*/
    
    //end account payable
		//echo highlight_string(var_export($monthlyPayable, TRUE));
		
		
		
		
    
    // begin incomce tax rates
    /*----------------------------------------------------------------------
			Income Tax Calculation
		------------------------------------------------------------------------*/
		$yrsOfFinancialForecast = $expenditure->financialYear();
		
		for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
		{
			//echo $_SESSION['sessionOfarrayOfYrlyCalcInterest'];	
		}
		
		// Define arrays 
		
		$array_totalExpenses = array();
		$array_grossMargin = array();
		$array_estimatedIncomeTax = array();
		$array_eachYrEstimatedIncomeTax = array();
		
		//echo $incomeTaxRate;
		
		/*--- Get Array of Interest Incured	---*/
		if(isset($array_interestIncured)){$array_interestIncured =  $array_interestIncured;}
		
		
		/*--- Get Array of total Expenses	---*/
		if(isset($allExpense)){$array_totalExpenses = $allExpense;}
		
		/*--- Get Array of Gross Margin	---*/
		if(isset($grossMargin)){$array_grossMargin = $grossMargin;}
		
		//loop through this for number of years
		for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
		{
			if(empty($array_interestIncured))
			{
				/*---	create arrays based on the number of financial year and set value to 0	---*/
				for($e_year_at = 0; $e_year_at < count($yrsOfFinancialForecast); $e_year_at++ )
				{
					$array_interestIncured[$e_year_at] = 0;
				}
			}
			
			if(count($array_totalExpenses) > 0)		{$array_totalExpenses[$e_year] = 	str_replace(",", "", $array_totalExpenses[$e_year]);}
			if(count($array_grossMargin) > 0)		{$array_grossMargin[$e_year] = 	str_replace(",", "", $array_grossMargin[$e_year]);}
			
			
			
			if((count($array_totalExpenses) > 0) and (count($array_grossMargin) > 0))
			{
				$array_estimatedIncomeTax[$e_year] = ($array_grossMargin[$e_year] - $array_totalExpenses[$e_year] - $array_interestIncured[$e_year] ); 
				
				$array_eachYrEstimatedIncomeTax[$e_year] = (($array_estimatedIncomeTax[$e_year] * $incomeTaxRate) / 100);
				
				$array_eachYrEstimatedIncomeTax[$e_year] = number_format($array_eachYrEstimatedIncomeTax[$e_year], 0, '.', '');
			}
			else
			{
				$array_eachYrEstimatedIncomeTax[$e_year] = 0;
			}
			if($array_eachYrEstimatedIncomeTax[$e_year] < 0)
			{
				$array_eachYrEstimatedIncomeTax[$e_year] = 0;
			}
			 
		}
    
    
    // end income tax
    
	// monthly income tax
	$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
	$monthlygrossmargin 		= $this->salesdata['monthlyGrossMargin'];
	$montylyinterestincurred 	= $this->profitlossdata['monthlyinterestincurred'];
		
		
	$monthlyincometax = array();
	for($i = 0; $i < 12; $i++) {
		$monthlyincometax[$i] = $monthlygrossmargin[$i] - $monthlytotalexpenses[$i] - $montylyinterestincurred[$i];
		$monthlyincometax[$i] = $monthlyincometax[$i] * $incomeTaxRate / 100;
	}
	// end monthly income tax
		
    
    //begin cash in hand
    
    $eachYrlyCalc = array();
		
		$totalYrOne = array();
		$totalYrTwo = array();
		$totalYrThree = array();
		$loanTakenMinusPaymentMade = array();
		$newCashInHand = array();
		
		
		
		if(isset($_array_recieveAmountYrly))
		{
			
			// Amount reveiev minus payment made
			for($fundSection1 = 0; $fundSection1 < count($_array_recieveAmountYrly); $fundSection1 ++)
			{
				
				for($fundSection2 = 1; $fundSection2 <= count($_array_recieveAmountYrly[$fundSection1]); $fundSection2 ++)
				{
					//echo $_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2]."<br/>";
					
					($_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2] - $_array_paymentAmountYrly[$fundSection1]["yr_".$fundSection2])."<br/>";
					$eachYrlyCalc[$fundSection1]["yr_".$fundSection2] = ($_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2] - $_array_paymentAmountYrly[$fundSection1]["yr_".$fundSection2]);
					
					if($fundSection2 == 1)
					{
						$totalYrOne[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
					elseif($fundSection2 == 2)
					{
						$totalYrTwo[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
					elseif($fundSection2 == 3)
					{
						$totalYrThree[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
				}
			}
		}
		
		
		
		/*---	Calculate the Cash In hands	---*/
		for($x = 0; $x < count($grossMargin); $x++)
		{
			$operatingIncomes = ($grossMargin[$x] - $allExpense[$x]);
			if($x == 0) // Year one
			{
				$loanTakenMinusPaymentMade[$x]  = (array_sum($totalYrOne) +  $operatingIncomes);
			}
			elseif($x == 1) // year 2
			{
				$loanTakenMinusPaymentMade[$x] = (array_sum($totalYrTwo) +  $operatingIncomes);
				$loanTakenMinusPaymentMade[$x] = ($loanTakenMinusPaymentMade[$x] + $loanTakenMinusPaymentMade[0]);
				
			}
			elseif($x == 2) // year 3
			{
				$loanTakenMinusPaymentMade[$x] = (array_sum($totalYrThree) +  $operatingIncomes);
				// Add the original calculation of year 2 by taking year one from year two
				$loanTakenMinusPaymentMade[$x] = ($loanTakenMinusPaymentMade[$x] + ($loanTakenMinusPaymentMade[1] - $loanTakenMinusPaymentMade[0]));
			}
			
		}
			
		//print_r($loanTakenMinusPaymentMade);
		//print_r(array_sum($totalYrOne));
		
		
		
		/*---	Deduct Account receivabel from the total	i.e ($loanTakenMinusPaymentMade - $accountReceivable_allYears) --- 
				Add Account Payable 
		*/
		for($yrs = 0; $yrs < count($loanTakenMinusPaymentMade); $yrs++)
		{
			$newCashInHand[$yrs] = (($loanTakenMinusPaymentMade[$yrs] - $accountReceivable_allYears[$yrs]) + $Total_accountPayable_allYears_bdgt[$yrs] - $array_eachYrEstimatedIncomeTax[$yrs]);
		}
    
    //end cash in hand
    
		include(LIBRARY_PATH . '/pdf_calc_expense.php');
		include(LIBRARY_PATH . '/pdf_calc_cash.php');
		
		
		
		
   
