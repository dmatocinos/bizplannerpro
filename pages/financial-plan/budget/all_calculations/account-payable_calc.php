<?php
	  /**---	UPDATED JUNE 10 2013	---**/

	/*-----------------------------------------------------------------------
		ACCOUNT PAYABLE - Figures taken from sale forcast and budget tables			
	-----------------------------------------------------------------------*/
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
	?>
    