<?php
	/**---	UPDATED JUNE 10 2013	---**/
	// 		Updated - 08/June/2013
	

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
		
		
		// loop to get all months from the back
		if($allSalesDetails) 
		{	
			$array_year = array();
			$year2_calc = 0;
			$year3_calc = 0;
			$accountReceivable_allYears = array();
			
			$loop_time = (12 - $monthsCollectPayment);
			
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
				
				if(empty($collect_each_sale_forecast))
				{
					$collect_each_sale_forecast = 0;
					$accountReceivable_01[$each_sale_forcast] = 0;
				}
				else
				{
					$accountReceivable_01[$each_sale_forcast] = (array_sum($collect_each_sale_forecast[$each_sale_forcast]) *  $allSalesDetails[$each_sale_forcast]['price']);
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
	?>
    