<?php

	

	$ns['salesdata'] 		= array(); 
	$ns['expensesdata'] 	= array();
	$ns['employeedata'] 	= array();
	$ns['loansdata'] 		= array();
	$ns['profitlossdata'] 	= array();
	$ns['balancesheetdata'] = array();
	$ns['cashflowdata'] 	= array();
	$ns['graphdatabank']	= array();
	//NUMBER FORMAT: number_format(($gm * 100) / $totalSale[$i], 0, '.', ',') . '%'
	
		
	//SALES FORECAST CALC
	$sales_forecast_lib = new sales_forecast_lib();
	$sales = $sales_forecast_lib->getAllSales('', '', '');
	
	
	$fy_start = $sales_forecast_lib->startFinancialYear;
	$th = array('');
	foreach ($sales[0]['financial_status'] as $each_fin_stat) {
		$th[] = 'FY' . ++$fy_start;
	}
	
	
	$unit_sales 		= array();
	$price_per_unit 	= array();
	$product_sales 		= array();
	$direct_cost_per_unit 	= array();
	$direct_cost 		= array();
	$totalSale 			= array();
	
	$years = array_slice($th, 1);
	//for appendix later on
	$products				= array(); //product name, array() of monthlyvalues
	$monthlyUnitSales		= array();
	$monthlyPricePerUnit 	= array();
	$monthlyProductSales	= array();
	$monthlyDirectCostPerUnit 	= array();
	$monthlyDirectCost		= array();
	
	$monthlyTotalSales		= array();
	$monthlyTotalDirectCost	= array();
	$monthlyGrossMargin		= array();
	$monthlyGrossMPercentage= array();
	
	
	
	$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$list12Months = $sales_forecast_lib->twelveMonths($start_years, $start_month);
	$months					= $list12Months;
	$monthCounter = 0;
	$productCounter = 0;
	//
	
	foreach ($sales as $exp_details) {
		$us_td 		= $ppu_td = $ps_td = $dcpu_td = $dc_td = array();
		$us_td[] 	= $exp_details['sales_forecast_name'];
		$ppu_td[] 	= $exp_details['sales_forecast_name'];
		$ps_td[] 	= $exp_details['sales_forecast_name'];
		$dcpu_td[] 	= $exp_details['sales_forecast_name'];
		$dc_td[] 	= $exp_details['sales_forecast_name'];
	
		$totalSaleCounter = 0;
	
		foreach ($exp_details['financial_status'] as $fin_details) {
			$us_td[] = $fin_details['total_per_yr'];
	
			$ppu_td[] = $exp_details['price'];
	
			if ( ! isset($totalSale[$totalSaleCounter])) {
				$totalSale[$totalSaleCounter] = 0;
			}
			$sale = ($fin_details['total_per_yr'] * $exp_details['price']);
			$totalSale[$totalSaleCounter] += $sale;
	
			$ps_td[] = $sale;
	
			$dcpu_td[] = $exp_details['cost'];
	
			$cost = ($fin_details['total_per_yr'] * $exp_details['cost']);
			if ( ! isset($totalCost[$totalSaleCounter])) {
				$totalCost[$totalSaleCounter] = 0;
			}
			$totalCost[$totalSaleCounter] += $cost;
			$dc_td[] = $cost;
	
			$totalSaleCounter = $totalSaleCounter + 1;
		}
	
		$unit_sales[] 		= $us_td;
		$price_per_unit[] 	= $ppu_td;
		$product_sales[] 	= $ps_td;
		$direct_cost_per_unit[] = $dcpu_td;
		$direct_cost[] 		= $dc_td;
	
		//set monthly values
		$products[$productCounter] = array();
		$products[$productCounter]['name'] = $exp_details['sales_forecast_name'];
		$monthlyUnitSales		= array();
		$monthlyPricePerUnit 	= array();
		$monthlyProductSales	= array();
		$monthlyDirectCostPerUnit 	= array();
		$monthlyDirectCost		= array();
			
	
		$monthCounter = 0;
	
		foreach($list12Months as $monthList)
		{
	
			// $monthsInNumbers i.e 01 ... 12
			$monthsInNumbers 		= str_pad($monthCounter+1,2,"0",STR_PAD_LEFT); //pad with leading 0
	
			//array_push($datax, $allExpensesMonths[$monthCounter]);
			//array_push($datay, array_sum($eachSaleMonth_[$monthCounter]));
	
			$monthlyUnitSales[$monthCounter]		= $exp_details['month_'.$monthsInNumbers];
			$monthlyPricePerUnit[$monthCounter] 	= $exp_details['price'];
			$monthlyProductSales[$monthCounter]		= $exp_details['price'] * $exp_details['month_'.$monthsInNumbers];
			$monthlyTotalSales[$monthCounter]		+= $monthlyProductSales[$monthCounter];
			$monthlyDirectCostPerUnit[$monthCounter]= $exp_details['cost'];
			$monthlyDirectCost[$monthCounter]		= $exp_details['cost'] * $exp_details['month_'.$monthsInNumbers];
			$monthlyTotalDirectCost[$monthCounter]	+= $monthlyDirectCost[$monthCounter];
	
	
	
			$monthCounter = $monthCounter+1;
		}
			
		$products[$productCounter]['monthlyUnitSales'] 		= $monthlyUnitSales;
		$products[$productCounter]['monthlyPricePerUnit'] 	= $monthlyPricePerUnit;
		$products[$productCounter]['monthlyProductSales'] 	= $monthlyProductSales;
		$products[$productCounter]['monthlyDirectCostPerUnit'] 	= $monthlyDirectCostPerUnit;
		$products[$productCounter]['monthlyDirectCost'] 		= $monthlyDirectCost;
	
		$productCounter++;
	
	}
	
	for($mo=0;$mo<12;$mo++) {
		$monthlyGrossMargin[]		= $monthlyTotalSales[$mo] - $monthlyTotalDirectCost[$mo];
		$monthlyGrossMPercentage[]	= $monthlyGrossMargin[$mo]/$monthlyTotalSales[$mo]*100;
	}
	
	//keep values
	$ns['salesdata']['products']			= $products;
	$ns['salesdata']['monthlyTotalSales'] 	= $monthlyTotalSales;
	$ns['salesdata']['monthlyTotalDirectCost'] 	= $monthlyTotalDirectCost;
	$ns['salesdata']['monthlyGrossMargin'] 	= $monthlyGrossMargin;
	$ns['salesdata']['monthlyGrossMPercentage'] 	= $monthlyGrossMPercentage;
	$ns['salesdata']['months'] 				= $months;
	$ns['salesdata']['years'] 				= $years;
	$ns['salesdata']['currency']			= $sales_forecast_lib->defaultCurrency;
	
	
	
	
	
	$ns['salesdata']['yrlyUnitSales']	= $unit_sales;
	$ns['salesdata']['yrlyUnitPrices']	= $price_per_unit;
	$ns['salesdata']['yrlyProdSales']	= $product_sales;
	
	$ns['salesdata']['yrlyUnitCost']	= $direct_cost_per_unit;
	$ns['salesdata']['yrlyCosts']		= $direct_cost;
	$ns['salesdata']['yrlyTotalCosts']	= $total_direct_cost;
	
	
	$total_sales		= array('Total Sales');
	$total_direct_cost 	= array('Total Direct Cost');
	$gross_margin 		= array('Gross Margin');
	$gross_margin_percentage = array('Gross Margin %');
	$ns['salesdata']['grossMarginRaw'] = array();
	for ($i = 0; $i < 3; $i++) {
		$total_sales[] 			= $totalSale[$i];
		$total_direct_cost[] 	= $totalCost[$i];
		$gm 					= $totalSale[$i] - $totalCost[$i];
		$gross_margin[] 		= $gm;
		$ns['salesdata']['grossMarginRaw'][] = $gm;
		$gross_margin_percentage[] = number_format(($gm * 100) / $totalSale[$i], 0, '.', ',') . '%';		
	}
	
	
	//keep a reference to sales data to be used later on
	$ns['salesdata']['yrlyTotalCosts']		= $total_direct_cost;
	$ns['salesdata']['yrlyGrossMargin'] 	= $gross_margin;
	$ns['salesdata']['yrlyGMPercentage'] 	= $gross_margin_percentage;
	$ns['salesdata']['yrlyTotalSales']		= $total_sales;
	
	
	//keep a reference to sales data to be used later on
	$ns['salesdata']['gross_margin'] = $gross_margin;
	$ns['salesdata']['gross_margin_percentage'] = $gross_margin_percentage;
	
	//keep data to graphdatabank
	$ns['graphdatabank'] = array();
	$ns['graphdatabank']['salesobj']  	= $sales_forecast_lib;
	$ns['graphdatabank']['sales']     	= $sales;
	$ns['graphdatabank']['totalsales'] 	= $totalSale;
	$ns['graphdatabank']['grossmargin'] = $gross_margin_percentage;

	
	//END SALES FORECAST CALC
 
	//PERSONNEL PLAN CALC
	
	$employee = new employee_lib();
	$allEmpDetails = $employee->getAllEmployeeDetails2("", "", "");
	
	if(!$allEmpDetails) return;
	
	
	$years = array('');
	
	foreach ($allEmpDetails[0]['financial_status'] as $eachFinStat)
	{
	
		$years[] = "FY" . $eachFinStat['financial_year'];
	
	}
	
	
	
	$counter        = 0;
	$arraySummation = "";
	
	
	$employees 		= array();
	
	
	//echo highlight_string(var_export($allEmpDetails, TRUE));
	
	$monthlysalarytotal = array();
	
	foreach($allEmpDetails as $empDetails)
	{
		$td = array();
			
		$td[] = $empDetails['emplye_name'];
		foreach($empDetails['financial_status'] as $finDetails)
		{
			$td[] = $employee->defaultCurrency.$finDetails['total_per_yr'];
		}
			
	
		for($i=0; $i< count($empDetails['financial_status']); $i++)
		{
		$arraySummation[$i][$counter]  = $empDetails['financial_status'][$i]['total_per_yr'];
		}
			
		
			
		$counter = $counter+1;
			
			
		$monthlysalary 	= array();
	
			
	
	
		for($mo=0; $mo < 12; $mo++)
		{
		// $monthsInNumbers i.e 01 ... 12
			$monthsInNumbers 		= str_pad($mo+1,2,"0",STR_PAD_LEFT); //pad with leading 0
			$monthlysalary[$mo]		= $empDetails['month_'.$monthsInNumbers];
			$monthlysalarytotal[$mo]	+= $monthlysalary[$mo];
	
		}
			
		$employees[] = array('name'=>$empDetails['emplye_name'], 'monthlysalary'=>$monthlysalary, 'yrlysalary'=>$td);
			
			
	}
	
			$ns['employeedata']['employees'] = $employees;
			$ns['employeedata']['monthlysalarytotal'] = $monthlysalarytotal;
	
			
			
			//echo highlight_string(var_export($employees, TRUE));
			$td = array();
			$td = array("Total");
	
			foreach($arraySummation as $total)
			{
			$td[] = $employee->defaultCurrency.array_sum($total);
			}
	
	
			$ns['employeedata']['yrlyTotal'] = $td;
	
			
	
	//END PERSONNEL PLAN CALC
	

	//BUDGET CALC
			
			
			$expenditure    = new expenditure_lib();
			$employee       = new employee_lib();
			$allExpDetails  = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
			$allEmpDetails  = $employee->getAllEmployeeDetails2("", "", ""); // All employees
			$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation
			$arraySummation = array();
			$yearexpenses   = array();
			
			$empexpenses    = array();
			
			
			
			//INIT EXPENSES
			
			
			
			$counter = 0;
			$arraySummation = "";
			// Related Expenses calculation
			(int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
			$personalRelatedExpenseInPercentage = ($personalRelatedExpenses / 100);
			$ns['salesdata']['personalRelatedExpenseInPercentage'] = $personalRelatedExpenseInPercentage;
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
					
				//<!-------------       SALARY  -------------->
						
					$salary = array();
					$empExpenses = array();
						
					foreach($arraySummation as $total)
					{
					$salary[] = array_sum($total);
					$empExpenses[] = $personalRelatedExpenseInPercentage * array_sum($total);
					}
						
						
						
					$expensesrows['Salary'] = $salary;
					$expensesrows['Employee Related Expenses'] = $empExpenses;
						
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
						
						
						
					//init monthly data
					$monthlytotalsalary 			= array();
					$monthlytotalrelatedexpenses 	= array();
					$monthlytotalexpenses 			= array();
						
						
						
					foreach($allEmpDetails as $empDetails)
					{
					for ($counter = 0; $counter < 12; $counter++)
						{
						$counterstr 						= str_pad($counter+1,2,"0",STR_PAD_LEFT);
						$monthlytotalsalary[$counter] 			+= $empDetails['month_' . $counterstr];
						$monthlytotalrelatedexpenses[$counter] 	+= ($personalRelatedExpenseInPercentage * $empDetails['month_' . $counterstr]);
						$monthlytotalexpenses[$counter] 	= $monthlytotalsalary[$counter] + $monthlytotalrelatedexpenses[$counter];
						}
			
			
			
						}
							
							
							
						//echo highlight_string(var_export($monthlyrelatedexpenses, TRUE));
						$ns['expensesdata']['monthlytotalsalary'] 			= $monthlytotalsalary;
						$ns['expensesdata']['monthlytotalrelatedexpenses'] 	= $monthlytotalrelatedexpenses;
							
							
							
						//init yearly data
							
						$yearlyTotalSalary 		= array();
						$yearlyTotalRSalary 	= array();
						$yearlyTotalExpenses 	= array();
							
						foreach($allEmpDetails as $empDetails)
						{
						$counter = 0;
			
						foreach($empDetails['financial_status'] as $yearexpense)
						{
						$yearlyTotalSalary[$counter] 	+= $yearexpense['total_per_yr'];
						$counter++;
						}
			
						}
						$counter = 0;
							
						foreach($yearlyTotalSalary as $yearsalaray)
						{
			
			
							$yearlyTotalRSalary[$counter] 	= $yearlyTotalSalary[$counter]*$personalRelatedExpenseInPercentage;
							$yearlyTotalExpenses[$counter] 	= $yearlyTotalSalary[$counter] + $yearlyTotalRSalary[$counter];
			
							$counter++;
							}
								
								
							//highlight_string(var_export($yearlyTotalSalary, true));
							//highlight_string(var_export($yearlyTotalRSalary, true));
								
							$ns['expensesdata']['yearlyTotalSalary'] 	= $yearlyTotalSalary;
							$ns['expensesdata']['yearlyTotalRSalary'] 	= $yearlyTotalRSalary;
								
								
								
								
							/*---------------------------------------------------
							Expenditure  Calculation loop
							 /*-----------------------------------------------*/
								
							//highlight_string(var_export($allEmpDetails, true));
								
							$monthlyotherexpenses = array();
							$index = 0;
							
							highlight_string(var_export($allExpDetails, true));
							
						foreach($allExpDetails as $expDetails)
						{
			
							$tmparray = array();
							$j = 0;
							foreach($expDetails['financial_status'] as $finDetails)
							{
							$tmparray[] = $finDetails['total_per_yr'];
							$yearlyTotalExpenses[$j] += $finDetails['total_per_yr'];
							$j++;
							}
			
							$expensesrows[$expDetails['expenditure_name']] = $tmparray;
			
							$monthlyotherexpenses[$expDetails['expenditure_name']] = array();
			
							for( $i=0; $i < 12; $i++ )
							{
							$key = str_pad($i+1, 2, '0', STR_PAD_LEFT);
							$monthlyotherexpenses[$expDetails['expenditure_name']][$i] = $expDetails['month_' . $key];
							$monthlytotalexpenses[$i] += $expDetails['month_' . $key];
							$monthlytotalexpenses[$i] += $personalRelatedExpenseInPercentage * $monthlyotherexpenses[$expDetails['expenditure_name']][$i];
							}
			
			
							for($i=0; $i< count($expDetails['financial_status']); $i++)
							{
							$arraySummation[$i][$counter]  = $expDetails['financial_status'][$i]['total_per_yr'];
							}
			
							$counter = $counter+1;
			
							$index++;
			
							}
								
							//echo highlight_string(var_export($monthlytotalexpenses, TRUE));
							$ns['expensesdata']['yearlyTotalExpenses'] 		= $yearlyTotalExpenses;
							$ns['expensesdata']['yearlyexpenses'] 			= $expensesrows;
							$ns['expensesdata']['monthlyotherexpenses'] 	= $monthlyotherexpenses;
							$ns['expensesdata']['monthlytotalexpenses'] 	= $monthlytotalexpenses;
								
							highlight_string(var_export(monthlyotherexpenses,true));
								
							//expenses by year
								
							$y = 0;
							$allExpense     = array();
							$tmparray       = array();
								
							foreach($arraySummation as $sumOfAllExpenses)
							{
							$allExpense[$y] = array_sum($sumOfAllExpenses);
			
							$yearexpenses[$allExpDetails[0]['financial_status'][$y]['financial_year']] = $allExpense[$y];
							$tmparray[] = $allExpense[$y];
							$y = $y+1;
							}
								
							$expensesrows["Total Operating Expenses"] = $tmparray;
								
							$ns['expensesdata']['allExpense'] = $allExpense;
								
								
								
						}
			
			
			//END INIT EXPENSES
			
			
			
			$ns['expensesdata']['employeeexpenses'] = $empexpenses;
			
			//keep data to graphdatabank
			$ns['graphdatabank']['employee']                  = $employee;
			$ns['graphdatabank']['allEmpDetails']             = $allEmpDetails;
			$ns['graphdatabank']['allExpDetails']            = $allExpDetails;
			$ns['graphdatabank']['personalRelatedExpenses']   = $personalRelatedExpenses;
			$ns['graphdatabank']['yearexpenses']              = $yearexpenses;
			
			
			
			(int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
			
			
	//BUDGET CALC
			
	//LOANS INVESTMENTS
			
			$_loanInvestment = new loansInvestments_lib();
			$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
			
			if($allloanInvestmentProjection)
			{

	//$th = array('');
		$yrsOfFinancialForecast = $_loanInvestment->financialYear();
		for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
		{
			$th[] = 'FY' . $yrsOfFinancialForecast[$e_yr];

		}

		$yearlydata = array();
		$monthlydata= $ns['loansdata']['monthly'] 	= array();
		
		$yearlydata['yearsrows'] 			= $th;
		$yearlydata['loansrows']			= array();
		$yearlydata['loansdetailrows']	= array();
		
		
		
		$monthlycolumnname 				= 'limr_month_';
		$monthlydata['loansrows']		= array();
		$monthlydata['loansdetailrows']	= array();
		$monthlydata['totalrows']	 	= array("Total Amount Received");
		
		$monthlyreceive = array();
		$monthlypayment = array();
		
		$tmploans = $allloanInvestmentProjection[0];
		
		for($i = 1; $i < 13; $i++) {			
			
			$monthlyreceive[] = $tmploans['limr_month_' . str_pad($i,2,"0",STR_PAD_LEFT)];
			$monthlypayment[] = $tmploans['limp_month_' . str_pad($i,2,"0",STR_PAD_LEFT)];
		}
		
				
		$ns['loansdata']['$monthlyreceive'] = $monthlyreceive;
		$ns['loansdata']['$monthlypayment'] = $monthlypayment;
		
		
		foreach($allloanInvestmentProjection as $expDetails)
		{

			$td = array($expDetails['loan_invest_name']);

			foreach($expDetails['financial_receive'] as $finDetails)
			{
				$td[] = $finDetails['lir_total_per_yr'];

			}

			$details = $expDetails['type_of_funding'] . " at " . $expDetails['loan_invest_interest_rate'] . "% interest";
			
			$td[0] .= '<br><span style="font-family: arialmt">' . $details . '</span>';
						
			$yearlydata['loansrows'][] = $td;
									

			$yearlydata['loansdetailrows'][] 	= array($details,"","","");
						
			
			for($i=0; $i< count($expDetails['financial_receive']); $i++)
			{
				$arraySummation[$i][$counter]  = $expDetails['financial_receive'][$i]['lir_total_per_yr'];
			}

			
			$tmpmonthlyloanscols 	= array($expDetails['loan_invest_name']);
			$tmpmonthlydetailcols 	= array($details);
			
			
			//keep monthly data
			for($i = 0; $i < 12 ; $i++)
			{
				$strindex 				= str_pad($i+1,2,"0",STR_PAD_LEFT);
				$tmpmonthlyloanscols[] 	= $expDetails[$monthlycolumnname . $strindex];
				$tmpmonthlydetailcols[] = "";				
				$monthlydata['totalrows'][$i+1] += $expDetails[$monthlycolumnname . $strindex];
			}
			
			
			
			
			$monthlydata['loansrows'][] 		= $tmpmonthlyloanscols;
			$monthlydata['loansdetailrows'][] 	= $tmpmonthlydetailcols;		
			
			
			$counter = $counter+1;

		}// end foreach

		$td = array("Total Amount Received");

		foreach($arraySummation as $total)
		{
			$td[] = $_loanInvestment->defaultCurrency . number_format(array_sum($total), 0, '.', ',');
		}

		
		
		$yearlydata['totalrows'] = $td;
		
		
		$ns['loansdata']['yearly'] 	= $yearlydata;
		$ns['loansdata']['monthly'] = $monthlydata;
		
			
				
				
	
	} // end if $allloanInvestmentProjection
			
			
			
			
	//END LOANS INVESTMENTS 			
			
	//PROFT AND LOSS STATEMENT
		
	

		$sales = new sales_forecast_lib();
		$allSalesDetails = $sales->getAllSales("", "", "");

		$yeartotal = 0;
		
		//echo highlight_string(var_export($allSalesDetails, TRUE));
		
		if($allSalesDetails > 0)
		{
			
			
			
			$monthrows = array('Revenue');
			
			
			foreach($allSalesDetails as $expDetails)
			{
				$totaSaleCounter = 0;

				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					$arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
				}
				
				
				
				for($i = 0; $i < 12; $i++){
						
					$stri = str_pad($i+1,2,"0",STR_PAD_LEFT);
					$monthrows[$i+1] += ($expDetails["month_" . $stri] * $expDetails['price']);
				
					
				}
				
				//echo highlight_string(var_export($tmprows, TRUE));
				
				$counter = $counter+1;
			}// end foreach

			$ns['profitlossdata']['monthlyrevenuerows'] = $monthrows;
			//echo highlight_string(var_export($ns['profitlossdata']['monthlyrevenuerows'], TRUE));
			
			$revenue = $arraySalesSummation;
			$th = array("");

			$financialYearSF = $sales->startFinancialYear;
			$financialYearSF = $financialYearSF + 1;

			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				$th[] = "FY" . $financialYearSF++;
			}

			

			$td = array("Revenue");

			
			
			$totalSalesCounter = 0;
			foreach($arraySalesSummation as $sumOfAllSales)
			{
				$totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));
				$totalSales_format[$totalSalesCounter] = array_sum($sumOfAllSales);
				$td[] = $totalSales_format[$totalSalesCounter];
				
				
				$totalSalesCounter = $totalSalesCounter + 1;
				
			}

			

			
			$ns['profitlossdata']['yearlyrevenuerows'] 	= $td;
			
			$monthrows = array("Direct Cost");
			
			$pldirectcosts = array();
			
			foreach($allSalesDetails as $expDetails)
			{
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					$arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
				}
				
				for($i = 0; $i < 12; $i++){
				
					$stri = str_pad($i+1,2,"0",STR_PAD_LEFT);
					$monthrows[$i+1] += ($expDetails["month_" . $stri] * $expDetails['cost']);
					$pldirectcosts[$i] += $expDetails["month_" . $stri] * $expDetails['cost']; 
				
				}
				
				
				
				$counter = $counter+1;
			}// end foreach

			$ns['profitlossdata']['monthlydirectcostrows'] 	= $monthrows;
			
			
			$td = array("Direct Cost");

			$totalCostCounter = 0;
			foreach($arrayCostSummation as $sumOfAllCost)
			{
				$totalDirectCost[$totalCostCounter] = (array_sum($sumOfAllCost));
				$totalDirectCost_format[$totalCostCounter] = array_sum($sumOfAllCost);
				$td[] = $totalDirectCost_format[$totalCostCounter];

				$totalCostCounter = $totalCostCounter + 1;
			}

			$ns['profitlossdata']['yearlydirectcostrows'] 	= $td;
			
			
			

			$grossMargin = $ns['salesdata']['grossMarginRaw'];
			
			$td = array("Gross Margin");
			
			foreach($grossMargin as $key=>$value){
				$td[] = $value;                   
				
			}
			

			
			
			
			//var_dump($grossMargin);
			
			$allExpense = $ns['expensesdata']['allExpense'];

			
			

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
        $totalSales_format[$totalSalesCounter] = array_sum($sumOfAllSales);
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
            $totalDirectCost_format[$totalCostCounter] = array_sum($sumOfAllCost);
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
        $grossMargin_format[$grossMarginCounter] = ($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]);
   
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
        $grossMarginPercentage[$grossMarginPercentageCounter] = $grossMarginPercentage[$grossMarginPercentageCounter];
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

$currency = $cashProjection->defaultCurrency;


if($allcashProjection)
{
	$_arrayOfYrlyCalcInterest = array();
	$_arrayOfYrlyCalcInterestCounter = 0;
	
	
	// Amount Receive Yearly
	$_array_recieveAmountYrly = array();
	$_array_recieveAmountYrlyCounter1 = 0;
	
	// Amount Paid Yearly
	$_array_paymentAmountYrly = array();
	$_array_paymentAmountYrlyCounter1 = 0;
	
	
	//echo highlight_string(var_export($allcashProjection, TRUE));
	
	
	foreach($allcashProjection as $cashProjectionDetails)
	{
		
		$zeroPrefix = 0;
		$zeroPrefix = 0;
		$zeroCounter = 0;
		$oneToTwelveCounterLoan = 1;
		for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
		{ //for 1
			if($oneToTwelveCounterLoan < 10)
			{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
				$oneToTwelveCounterLoan = $zeroPrefix.$oneToTwelveCounterLoan;
			}
			/*---	Fomat figure and remove seperated commas	---*/
			$amountReceiveWithoutCommas[$e_month] = number_format($cashProjectionDetails["limr_month_".$oneToTwelveCounterLoan], 0, '.', '');

			/*---	Fomat figure and seperate thousands with commas	---*/
			$amountReceiveWithCommas[$e_month] = number_format($cashProjectionDetails["limr_month_".$oneToTwelveCounterLoan], 0, '.', ',');

			/*---	Add to counter until it gets to 12	---*/
			$oneToTwelveCounterLoan = $oneToTwelveCounterLoan + 1;

		} // end for 1

		$_array_recieveAmountYrlyCounter2 = 1;
		
		foreach($cashProjectionDetails['financial_receive'] as $e_financialStatus)
		{
			$_array_recieveAmountYrly[$_array_recieveAmountYrlyCounter1]["yr_".$_array_recieveAmountYrlyCounter2] = $e_financialStatus['lir_total_per_yr'];
			$_array_recieveAmountYrlyCounter2 = ($_array_recieveAmountYrlyCounter2 + 1);
		}
		
		$_array_recieveAmountYrlyCounter1 = ($_array_recieveAmountYrlyCounter1 + 1);
		
		//Monthly Interest for the amount Paid back Section
		$zeroPrefix     = 0;
		$zeroCounter    = 0;
		$loanCounter    = 0;
		$interestRate   = 0;
		$paymentsMadeBeforeLoaningCounter = 0;
		$paymentsMadeBeforeLoaning = array();
		$addUpPaymentCounter = 0;
		$addUpPayment   = array();
		$addUpLoan      = 0;
		$paymentsOverPaidCounter = 0;
		$paymentsOverPaid = array();
		$addAllPaymentsToALevel = 0;
		$tosin          = 0;
		$femi           = 0;
		$tope           = 0;
		$getAllLoan     = 0;
		$monthFirstLoanWasTakenOut_ = 0;
		$inBtwnOrExactPayBack = 0;
		$oneToTwelveCounterPayment = 1;
		$countNumbersOfPayments = 0; // loop control
		$counter_FirstPaymentBeforeOverpaying = 0; // loop control
		$first_payment_box_location = 0; /*--- Declare this use it should incase it never changes---*/
		$calculatedYearInterest = array();
		$monthlyLoanBoxLocation_ = array();
		$sumOfLoansAtEachPaymentBoxLocation = array();
		$sumOfPaymentsAfterALoanIsTakeOut = array();
		$_paybackAddOn = 0;
		
		/*---	Set the ---*/
		$cashProjection->allData($cashProjectionDetails);
		
		/*---	At this junction, get the first loan box's location, so that you can deduct the first_payment_box_location from it ---*/
		$interestRate = $cashProjectionDetails['loan_invest_interest_rate'];
		
		for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
		{ //for 2
			if($oneToTwelveCounterPayment < 10)
			{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
				$oneToTwelveCounterPayment = $zeroPrefix.$oneToTwelveCounterPayment;
			}
			
			/*---	Format figure and remove seperated commas	---*/
			$amountPaidBackWithoutCommas[$e_month] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];
			
			/*---	Format figure and seperate thousands with commas	---*/
			$amountPaidBackWithCommas[$e_month] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];
			
			
			/*---	Check if amount received (loan) in each loan box is greater than 0	---*/
			if($amountReceiveWithoutCommas[$e_month] >0)
			{
				/*---	Get loan box loction	---*/
				$loan_box_location = $e_month;
				
				$monthlyLoanBoxLocation_[$loanCounter] = $e_month;
				
				$monthFirstLoanWasTakenOut_ = $monthlyLoanBoxLocation_[0]; // Get me the first month a loan was taken out
				
				$loanCounter = ($loanCounter + 1);
			}
			
			
			/*---	Check if payment value in each box is greater than 0 i.e pay back ?	---*/
			if($amountPaidBackWithoutCommas[$e_month] > 0)
			{
				
				
				//echo "<hr/> Differece --> ".($amountPaidBackWithoutCommas[$e_month] + $amountPaidBackWithoutCommas[$e_month])."<hr/>";
				if($monthFirstLoanWasTakenOut_ == 0)
				{
					/*---	
												If at First, there is no loan taken while payment was made
												do not calculate any interest. Just display the amount entered the same way
											---*/
					$paymentsMadeBeforeLoaning[$paymentsMadeBeforeLoaningCounter] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];
					
					$paymentsMadeBeforeLoaningCounter = ($paymentsMadeBeforeLoaningCounter + 1);
				}
				/*--- Calculation for the FIRST BOX of payment after at least a loan has been taken out.  ---*/
				elseif($monthFirstLoanWasTakenOut_ > 0)
				{
					
					$addUpPayment[$addUpPaymentCounter] = $amountPaidBackWithoutCommas[$e_month];
					
					if($addUpPaymentCounter > 0)
					{
						$addAllPaymentsToALevel += $addUpPayment[$addUpPaymentCounter-1];
						
						
					}
					
					$upto = $e_month;
					$getAllLoan = $cashProjection->TotalLoanTaken($upto);
					
					$differenceBtwnLoanAndPayBack = (($getAllLoan) - ($addAllPaymentsToALevel));
					
					$addUpPaymentCounter =  ($addUpPaymentCounter + 1);
										
					/*--- if the amount you are paying back has reached or over the amount you loaned	---*/
					if($differenceBtwnLoanAndPayBack <= 0)
					{
						$finalResult = 0;
						$cashProjection->PaymentTrick($e_month);
						
						$_PaymentBoxLocationNow = $cashProjection->nowPaymentBoxLocation;
						$_PaymentBoxLocationPrev = $cashProjection->previousPaymentBoxLocation;
						$_LoanBoxLocationPrev = $cashProjection->previousLoanBoxLocation;
						
						$cashProjection->LoanTakenUpto($_PaymentBoxLocationPrev);
						$immediate_PrevPayment = $cashProjection->YesThereWasAPayment;
						$immediate_PrevLoan = $cashProjection->YesThereIsALoan;
						
						$immediate_PrevPayment = str_replace(",", "", $immediate_PrevPayment);
						$immediate_PrevLoan = str_replace(",", "", $immediate_PrevLoan);
						
						$_diffInPrevLoanAndPayment = ($immediate_PrevLoan - $immediate_PrevPayment);
						
						
						
						
						$paymentsOverPaid[$paymentsOverPaidCounter] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];	
						
						
						if(($_diffInPrevLoanAndPayment > 0) and ($e_month != 12))
						{
							
							$_PaymentBoxLocationNow;
							
							$diff_inLastLoanAndNowPayment_boxLocation =    ($_LoanBoxLocationPrev - $_PaymentBoxLocationNow);
							$diff_inLastPaymentAndNowPayment_boxLocation = ($_PaymentBoxLocationPrev - $_PaymentBoxLocationNow);
							
							if($diff_inLastLoanAndNowPayment_boxLocation < $diff_inLastPaymentAndNowPayment_boxLocation)
							{
								$diffInCalc = $diff_inLastLoanAndNowPayment_boxLocation;
							}
							else
							{
								$diffInCalc = $diff_inLastPaymentAndNowPayment_boxLocation;
							}
							
							// Variable use to made addons Calculation
							$diffInCalc;
							$immediate_PrevPayment;
							$immediate_PrevLoan;
							
							$diffInMonthlyPrevLoanAndPrevPayment = ($immediate_PrevLoan - $immediate_PrevPayment);
							$MonthlyInterest = $cashProjection->calculateMonthlyInterest($diffInMonthlyPrevLoanAndPrevPayment, $diffInCalc);
							$_IntRate = ($interestRate / 100);
							
							$finalResult = ($MonthlyInterest * $_IntRate);
							
							
						}
						else
						{
							$finalResult = 0;
						}
						$paymentsOverPaid[$paymentsOverPaidCounter] = (($paymentsOverPaid[$paymentsOverPaidCounter]) + $finalResult);
						
						$counter_FirstPaymentBeforeOverpaying = ($counter_FirstPaymentBeforeOverpaying + 1);
						$paymentsOverPaidCounter = ($paymentsOverPaidCounter + 1);
					}
					else /*---	The amount you are paying back has NOT reached or over 0	---*/
					{
						
						
						
						
						if($countNumbersOfPayments == 0)
						{
							/* --- 	Get self box location  -----*/
							$first_payment_box_location = $e_month;
							
							/*---	Call The function to calculate each months interest  (Capital replayment + interest Payable)	---*/
							
							$capitalPLUSinterestPayable_first = $cashProjection->monthlyInterestExpectedPayment( $first_payment_box_location, "", $amountPaidBackWithoutCommas[$e_month], $amountReceiveWithoutCommas, $interestRate);	
							$calculatedYearInterest[0] = $capitalPLUSinterestPayable_first;
							
							//$calculatedYearInterest[$e_month] = $capitalPLUSinterestPayable_others;
							
							/*---	Control the loop to only call the above function ONES, because the calculation for the fisrt is different from the rest	---*/
							$countNumbersOfPayments = $countNumbersOfPayments + 1 ;
							
						}
						else /*---	Calculate for the REMAINING BOXES that have there values above 0 ---*/
						{
							
							/*---If true it means there has been an earlier payment made ----*/
							if($first_payment_box_location > 0)
							{
								
								/*---	Get self box locations---*/
								$others_payment_box_location = $e_month;
								//echo "<hr/><hr/><hr/> --- > First loan location ". $first_payment_box_location ." ". $others_payment_box_location . " Other loans box location < ----<hr/><hr/><hr/>";
								/*---	Amount paid back	---*/	
								$amountPaidBack_ = $amountPaidBackWithoutCommas[$e_month];
								
								/*---	Call The fuunction to calculate each months interest  (Capital replayment + interest Payable)	---*/
								$capitalPLUSinterestPayable_others = $cashProjection->monthlyInterestExpectedPayment($first_payment_box_location, $others_payment_box_location, $amountPaidBack_, $amountReceiveWithoutCommas, $interestRate);
								
								$calculatedYearInterest[0] = $capitalPLUSinterestPayable_first;
								
								$calculatedYearInterest[$e_month] = $capitalPLUSinterestPayable_others;
								
							}
						}
					}// End of the amount you are paying back has NOT reached or over 0	
				}
				
			}									
			
			/*---	Add to counter until it gets to 12 ( LOOP CONTROL ) ---*/
			$oneToTwelveCounterPayment = $oneToTwelveCounterPayment + 1;
		} // end for 2
		
		//Yearly Interest Calculattion for amount paid back						
		
		$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
		$_paymentMadeWithoutInterest = 0;
		$_paymentsOverPaid = 0;
		$_array_paymentAmountYrlyCounter2 = 1;
		
		$monthlyrows = array();
		
		//echo "count: " . count($calculatedYearInterest);
		
		
		for ($i= 0 ; $i < 12; $i ++)
		{
			$monthlyrows[] = $tmpinterest + $paymentsMadeBeforeLoaning[$i] + $paymentsOverPaid[$i] + $inBtwnOrExactPayBack;
			//echo 'intest ' . $monthlyrows[$i] . ' ';
		}

		$ns['profitlossdata']['monthlyinterestincurredrows'] = $monthlyrows;
		
		
		/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
		for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
		{ //for 3
			/*---
			If Financial Yr is 1, ignore the calculation cos it as already been done via the 12 months one.	
			Just focus on the ones after the first yr of payment
			---*/
			
			
			if ($e_year == 0)
			{
				
				
				$_paymentMadeWithoutInterest = array_sum($paymentsMadeBeforeLoaning); // fine
				$_paymentsOverPaid = array_sum($paymentsOverPaid);
				$inBtwnOrExactPayBack = $inBtwnOrExactPayBack;
				
				$_TotalYrlyInterst = (array_sum($calculatedYearInterest) + $_paymentMadeWithoutInterest + $_paymentsOverPaid + $inBtwnOrExactPayBack);
				
				$_arrayOfYrlyCalcInterest[$_arrayOfYrlyCalcInterestCounter] = $_TotalYrlyInterst;
								
				$_array_paymentAmountYrly[$_array_paymentAmountYrlyCounter1]["yr_".$_array_paymentAmountYrlyCounter2] =  $_TotalYrlyInterst;
				
				
				
				
				
				
			}
			elseif($e_year > 0)
			{
				// Year 2 and 3 ....
				$upToTheCurrentYr = $e_year;
				
				$loanTakenOut = $cashProjectionDetails['financial_receive'][$e_year]['lir_total_per_yr'];
				
				$yrlyPayBack = $cashProjectionDetails['financial_payment'][$e_year]['lip_total_per_yr'];
				
				$YrlyInterest = $cashProjection->CalculateYrlyInterest($interestRate, $_paymentMadeWithoutInterest, $cashProjectionDetails['financial_receive'], $cashProjectionDetails['financial_payment'],  $upToTheCurrentYr, $yrlyPayBack);
				
				if($yrlyPayBack == 0)
				{
					$YrlyInterest = 0;
				}
				
				$_array_paymentAmountYrly[$_array_paymentAmountYrlyCounter1]["yr_".$_array_paymentAmountYrlyCounter2] = $YrlyInterest;
				
				
			}
			$_array_paymentAmountYrlyCounter2 = ($_array_paymentAmountYrlyCounter2 + 1);
			
			$_interestIncured["years_0".($e_year+1)][$_arrayOfYrlyCalcInterestCounter] = (($_arrayOfYrlyCalcInterest[$_arrayOfYrlyCalcInterestCounter]) -  ($cashProjectionDetails['financial_payment'][($e_year)]['lip_total_per_yr']));
			
			
			
		} // end for 3
		$_array_paymentAmountYrlyCounter1 = ($_array_paymentAmountYrlyCounter1 + 1);
		$_arrayOfYrlyCalcInterestCounter = $_arrayOfYrlyCalcInterestCounter + 1;
		
	}	
	$_interestIncured;
	
} // end of if

  
        
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
				
				$array_eachYrEstimatedIncomeTax[$e_year] = $array_eachYrEstimatedIncomeTax[$e_year];
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
	$monthlytotalexpenses		= $ns['expensesdata']['monthlytotalexpenses'];
	$monthlygrossmargin 		= $ns['salesdata']['monthlyGrossMargin'];
	$montylyinterestincurred 	= $ns['profitlossdata']['monthlyinterestincurredrows'];
		
		
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
    
		
		//PDF CALC EXPENSE
		
		
		
		
		$monthrows 	= $ns['profitlossdata']['monthlyrevenuerows'];		// monthlyrevenue rows - includes label
		$months 	= $ns['salesdata']['months'];						// mos eg. Jan - Dec
		$years 		= $ns['salesdata']['years'];						// sales years
		$monthrows 	= $ns['profitlossdata']['monthlydirectcostrows'];	// monthly direct cost rows - includes label
		$monthrows 	= $ns['salesdata']['monthlyGrossMargin'];			// monthly gross margin rows - includes label
		$monthrows 	= $ns['salesdata']['monthlyGrossMPercentage'];		// monthly gross margin % - no label
		
		
		$monthlytotalsalary			= $ns['expensesdata']['monthlytotalsalary'];			//monthlytotalsalary no label
		$monthlytotalrelatedexpenses= $ns['expensesdata']['monthlytotalrelatedexpenses'];	//monhtlytotalrelated salaray no label
		
		$monthlyotherexpenses 		= $ns['expensesdata']['monthlyotherexpenses'];				//array of other expenses each row includes label
		
		//echo highlight_string(var_export($monthlytotalexpenses, TRUE));
		/* example loop of monthly other expenses
		 foreach($monthlyotherexpenses as $key=>$value) {
		$thtml->addLTDRow(array_merge(array($key),$this->farraynumber($value)), null);
		}
		*/
		
		
		$monthlytotalexpenses		= $ns['expensesdata']['monthlytotalexpenses'];		//monthly total operating expenses no label
		
		$monthlyrows = $ns['profitlossdata']['monthlyinterestincurredrows'];			//monthly interestincurred rows include labels
		$monthlyrows = array_merge(array('Interest Incurred'), $monthlyrows);
		
		//add depreciation
		$monthlyvals = $ns['profitlossdata']['monthly_accudepreciation'];
		
		
		$monthlygrossmargin 		= $ns['salesdata']['monthlyGrossMargin'];
		$montylyinterestincurred 	= $ns['profitlossdata']['monthlyinterestincurredrows'];
		$monthlyrows = array();
		$tmprows	 = array();
		
		$expenditure    = new expenditure_lib();
		$incomeTaxRate  =  $expenditure->incomeTaxRate;
		
		$tmptotalexpense = array();
		
		$depreciation = $ns['profitlossdata']['monthly_accudepreciation'];
		
		//echo "tax rate: " . $incomeTaxRate;
		
		for($i = 0; $i < 12; $i++)
		{
			$totalexpense 	= $monthlytotalexpenses[$i];
			$grossmargin 	= $monthlygrossmargin[$i];
			$interestincur	= $montylyinterestincurred[$i];
		
			$monthlyrows[$i] = ($grossmargin - $totalexpense - $interestincur);		//operating income * taxrate/100
			$tmprows[$i] = (($monthlyoperatingincome[$i] * $incomeTaxRate) / 100);
		
		
			if($monthlyrows[$i] < 0) {
				$monthlyrows[$i] = 0;
			}
		
			$tmptotalexpense[$i] = $interestincur + $tmprows[$i] + $depreciation[$i] + $monthlytotalexpenses[$i]; //income tax + interest incurred + depreciation
		
		}
		
		
		
		$ns['profitlossdata']['monthlyincometax'] 		= $tmprows; //incometax no label
		
		
		
		//calculate net profit
		
			
		$monthlydirectcost	= array_slice($ns['profitlossdata']['monthlydirectcostrows'], 1);
		
		$revenue = $ns['profitlossdata']['monthlyrevenuerows'];
		
		$tmprows	 = array();
		$monthlyrows = array();
		
		
		$pltotalexpense = array();
		
		for($i = 0; $i < 12; $i++)
		{
		$expense 			= $monthlydirectcost[$i] + $monthlytotalexpenses[$i] + $monthlyincometaxes[$i];
		$pltotalexpense[]	= $expense;
		$monthlyrows[$i] 	= $revenue[$i+1] - $expense;
		$tmprows[$i] 		= $revenue[$i+1] > 0 ? ($monthlyrows[$i] / $revenue[$i+1] * 100) : 0 ;
		}
		
		$ns['profitlossdata']['ns']['MonthlyTotalExpenses'] 	= $tmptotalexpense; //incometax no label, $ns['profitlossdata']['ns'] is a member variable
		
		$ns['profitlossdata']['monthlynetprofit'] = $monthlyrows;
		//highlight_string(var_export($revenue,true));
		//highlight_string(var_export($monthlytotalexpenses,true));
		//highlight_string(var_export($monthlyincometaxes,true));
		
		$ns['expensesdata']['balYearlyTotalExpenses'] = $pltotalexpense;
		
		
				//highlight_string(var_export($ns['expensesdata']['balYearlyTotalExpenses'],true));
		
		// YEARLY DATA
		
		
		$ns['salesdata']['netprofit'] = $monthlyrows;
		
		$years 		= $ns['salesdata']['years'];
		
		$tmprows 	= $ns['profitlossdata']['yearlyrevenuerows'];		//rows includes label
		
		$tmprows 	= $ns['profitlossdata']['yearlydirectcostrows'];	//rows include lable
		
		$tmprows 	= $ns['salesdata']['grossMarginRaw'];				//no label
		
		$yearlyTotalSalary		= $ns['expensesdata']['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $ns['expensesdata']['yearlyTotalRSalary'];
		
		$yearlyOperatingTotalExpenses	= $ns['expensesdata']['yearlyTotalExpenses']; // no label
		
		$tmprows = $ns['profitlossdata']['yearlyoperatingincomerows'];
		
		$TotalExpenses = array();
		
		$ns['profitlossdata']['yearlyinterestincurredrows'][1] = array_sum($montylyinterestincurred);
		
		for($i = 1; $i < 4; $i++ ) {
		//direct cost
		$dcost 		= str_replace(array($ns['salesdata']['currency'],','), '', $ns['profitlossdata']['yearlydirectcostrows'][$i]);
		$dcost 		= floatval($dcost);
		$iincur 	= str_replace(array($ns['salesdata']['currency'],',','(',')'), '', $ns['profitlossdata']['yearlyinterestincurredrows'][$i]);
		$iincur 	= floatval($iincur);
		$dep		= $ns['profitlossdata']['yearlydepreciation'][$i-1];
		$itax 		= str_replace(array($ns['salesdata']['currency'],','), '', $ns['profitlossdata']['yearlyincometaxrows'][$i]);
		$itax		= floatval($itax);
		
		$totaloperatingexpense 	= $yearlyOperatingTotalExpenses[$i-1];
		$TotalExpenses[] 		= $dcost + $iincur + $dep + $itax + $totaloperatingexpense;
		}
		
		
		//echo highlight_string(var_export($TotalExpenses, TRUE));
		//echo highlight_string(var_export($ns['profitlossdata']['yearlyinterestincurredrows'], TRUE));
		//echo highlight_string(var_export($ns['profitlossdata']['yearlydirectcostrows'], TRUE));
		//echo highlight_string(var_export($ns['profitlossdata']['yearlydepreciation'], TRUE));
		//echo highlight_string(var_export($ns['profitlossdata']['yearlyincometaxrows'], TRUE));
		
		$ns['expensesdata']['balYearlyTotalExpenses'] = $TotalExpenses;
		
		
		//END PDF CALC EXPENSE
		

			
			
			
			//<!--------------------        INTEREST INCURRED SECTION       ------------------------->
			$totalCostCounter = 0;
			$td = array("Operating Income");
			

			//var_dump($grossMargin);

			foreach($arrayCostSummation as $sumOfAllCost)
			{

				//$grossvalue = str_replace($sales->defaultCurrency, "", $grossMargin[$totalCostCounter+1]);
				//$grossvalue = str_replace(",", "", $grossvalue);
				//$grossvalue = floatval($grossvalue);

				$operatingIncome[$totalCostCounter] = ( $grossMargin[$totalCostCounter] - $allExpense[$totalCostCounter]);

				if($operatingIncome[$totalCostCounter] < 0)
				{
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}

				$td[] = $open_bracket . ($operatingIncome[$totalCostCounter] * $cancelNegative ) . $closed_bracket;

				$totalCostCounter = $totalCostCounter + 1;
			}
			
			

			//<!--------------------------------------------------------------------------->
			$ns['profitlossdata']['yearlyoperatingincomerows'] = $td; 
			
			
			//<!--------------------        INTEREST INCURRED SECTION       ------------------------->
			if(isset($_interestIncured))
			{
				$_loanInvestment = new loansInvestments_lib();
				$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
				$sumOfEachLoan = "";

				$td = array("Interest Incurred");

				$_yrlyCalcInterest = array();
				$array_interestIncuredCounter = 0;
				$array_interestIncured = array();
				$currency = $sales->defaultCurrency;
				if(isset($_interestIncured))
				{
					$_yrlyCalcInterest =  $_interestIncured;
				} else
				{
					$_yrlyCalcInterest = 0;
				}

				// loop through this for number of years
				foreach($_yrlyCalcInterest as $yrInterestIncured)
				{
					//print_r($yrInterestIncured);

					$array_interestIncured[$array_interestIncuredCounter] = array_sum($yrInterestIncured);

					if($array_interestIncured[$array_interestIncuredCounter] < 0)
					{
						$open_bracket  = OPEN_BRACKET;
						$closed_bracket  = CLOSED_BRACKET;
						$cancelNegative = -1;
					}
					else
					{
						$open_bracket  = "";
						$closed_bracket  = "";
						$cancelNegative = 1;
					}

					$td[] = $open_bracket . $sales->defaultCurrency . ($array_interestIncured[$array_interestIncuredCounter] * $cancelNegative) . $closed_bracket;

					$array_interestIncuredCounter = $array_interestIncuredCounter + 1;
				}
				
				
				$ns['profitlossdata']['yearlyinterestincurredrows'] = $td;
				

				//$table->addTDRow($td);

			} //end isset interestincurred



			//add depreciation and amortization
			$lib = new expenditure_lib();
			$numbersyrOfFinancialForecast = $lib->numberOfFinancialYrForcasting;	
			$major_purchases_details = $lib->getAllMajorPurchaseDetails('', 'mp_date');
			$years = array();
			
			$p = .20;
			
			$monthly_detail_purchases 	= array();
			$monthly_depreciation		= array(); 
			
			$monthlypurchases			= array();		
			$yearlypurchases			= array();
			
			
			
			foreach ($major_purchases_details as $purchase) {
				
				list($pm, $py) = explode(' ', $purchase['mp_date']);
				if ( !isset($years[$py])) {
					$years[$py] = 0;
				}

				
				$years[$py] += $purchase['mp_price'];
					
				if (!isset($monthly_detail_purchases[$purchase['mp_date']])) {
						$monthly_detail_purchases[$purchase['mp_date']] = $purchase['mp_price'];
				} else {
						$monthly_detail_purchases[$purchase['mp_date']] += $purchase['mp_price'];
				}
				
				
				
				if ($purchase['mp_depreciate']) {
					
					
					
					if (!isset($monthly_depreciation[$purchase['mp_date']])) {
							$monthly_depreciation[$purchase['mp_date']] = $purchase['mp_price']*$p/12;
					} else {
							$monthly_depreciation[$purchase['mp_date']] += $purchase['mp_price']*$p/12;
					}
				}
					
				if (isset($monthlypurchases[$purchase['mp_name']])) {
					
					$mp = $monthlypurchases[$purchase['mp_name']];
					
					if ( isset($mp[$purchase['mp_date']]) ) {
						$mp[$purchase['mp_date']] += $purchase['mp_price'];
					} else {
						$mp[$purchase['mp_date']] = $purchase['mp_price'];
					}
					
					$monthlypurchases[$purchase['mp_name']] = $mp;
					
				} else {
					
					$monthlypurchases[$purchase['mp_name']] = array();					
					$monthlypurchases[$purchase['mp_name']][$purchase['mp_date']] = $purchase['mp_price'];
					
				}
				//init yearly
				if (isset($yearlypurchases[$purchase['mp_name']])) {
						
					$mp = $yearlypurchases[$purchase['mp_name']];
						
					if ( isset($mp[$py]) ) {
						$mp[$py] += $purchase['mp_price'];
					} else {
						$mp[$py] = $purchase['mp_price'];
					}
						
					$yearlypurchases[$purchase['mp_name']] = $mp;
						
				} else {
						
					$yearlypurchases[$purchase['mp_name']] = array();
					$yearlypurchases[$purchase['mp_name']][$py] = $purchase['mp_price'];
						
				}
				
				
								
			}
			
			//highlight_string(var_export($monthlypurchases,true));
			
			$major_purchase = array_values($years);

			
			$financialYearSF = $sales->startFinancialYear;
			//$financialYearSF = $financialYearSF + 1;

			$td = array('Depreciation and Amortization');

			
			
			//initmonthly depreciation
				
			$tmpvaluerows 	= array('Depreciation and Amortization');
			$tmpvalues 		= array();
			$tmpvalues1		= array();
			$monthnames 	= array("Jan", "Feb", "Mar", "Apr", "May", "Jun",
					"Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
				
				
			$financialYearSF = $sales->startFinancialYear;
				
			//adjust month start depending on the plan
				
			if(isset($_SESSION['bpFinancialStartDate'])) {
				$startMonth = split(" ", $_SESSION['bpFinancialStartDate']);
			}
				
			//echo "hello " . $startMonth[0];
				
			$index = array_search($startMonth[0], $monthnames);
				
			$monthnamesfixed = array();
				
			for($i = 0; $i < 12; $i++) {
			
				if($index>11) {
					$index = 0;				}
			
				$monthnamesfixed[] = $monthnames[$index];
				$index++;
			}
				
				
			foreach($monthlypurchases as $key=>$value) {
				$indexedpurchases[$key] = array();
				$indexedpurchasesrows[$key] = array($key);
								
				$indexedypurchases[$key] = array();								
			}
			
			
				
			foreach($monthnamesfixed as $mm){
			
				if (isset($monthly_depreciation[$mm. " " .$financialYearSF])) {
					$tmpvaluerows[] = 	$monthly_depreciation[$mm. " " .$financialYearSF];
					$tmpvalues[]	=	$monthly_depreciation[$mm. " " .$financialYearSF];
				} else {
					$tmpvaluerows[] = 0;
					$tmpvalues[]	= 0;
				}
			
				if (isset($monthly_detail_purchases[$mm. " " .$financialYearSF])) {
					$tmpvalues1[]	= 	$monthly_detail_purchases[$mm. " " .$financialYearSF];
				} else {
					$tmpvalues1[]	= 0;
				}
				
				foreach($monthlypurchases as $key=>$value) {
					if ( isset( $value[$mm. " " .$financialYearSF]  ) ){
						$indexedpurchases[$key][] 		= $value[$mm. " " .$financialYearSF];
						$indexedpurchasesrows[$key][] 	= $value[$mm. " " .$financialYearSF];
					} else {
						$indexedpurchases[$key][] 		= 0;
						$indexedpurchasesrows[$key][] 	= 0;
					}
					
					
				}				
			}
			
			//highlight_string(var_export($indexedpurchases, true));
			
			
			$ns['profitlossdata']['monthlydepreciationrows'] 	= $tmpvaluerows;
			$ns['profitlossdata']['monthlydepreciation'] 		= $tmpvalues;
			$ns['profitlossdata']['monthly_detail_purchases'] 	= $tmpvalues1;
			$ns['profitlossdata']['monthlypurchases'] 			= $indexedpurchases;
			
			
			//highlight_string(var_export($tmpvalues, true));
			
			$monthly_accudepreciation = array();
			
			for($i = 0; $i < 12; $i++) {
				
				if ($i>0) {				
					for($j = 0; $j <= $i; $j++) {
						$monthly_accudepreciation[$i] += $tmpvalues[$j];
					}			
				} else {
					$monthly_accudepreciation[$i] = $tmpvalues[$i];
				}	
			}
			
			$monthly_balaccudepreciation = array();
			
			for($i = 0; $i < 12; $i++) {
				if($i>0) {
					$monthly_balaccudepreciation[$i] = $monthly_balaccudepreciation[$i-1] - $monthly_accudepreciation[$i];
				} else {
					$monthly_balaccudepreciation[$i] = $monthly_accudepreciation[$i];
				}				
			}
			
			
			$ns['profitlossdata']['monthly_accudepreciation'] 		= $monthly_accudepreciation;
			$ns['profitlossdata']['monthly_balaccudepreciation'] 	= $monthly_balaccudepreciation;
			
			//highlight_string(var_export($monthly_accudepreciation, true));
			
			
			$index = 0;
			
			$year1depreciation = array_sum($monthly_accudepreciation);
			
			

			

			
			//initialise yearly major purchases
			foreach($indexedypurchases as $key=>$value) {
				
				$financialYearSF = $sales->startFinancialYear;
				
				$index = 0;
							
				
				foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
				{

					if ($index == 0 ) {
						
						$indexedypurchases[$key][0] = array_sum($indexedpurchases[$key]);
						
					} else {
					
						if (isset($yearlypurchases[$key][$financialYearSF])) {
							$indexedypurchases[$key][] = $yearlypurchases[$key][$financialYearSF];
						} else {
							$indexedypurchases[$key][] = 0;
						}						
					}						
				
					$financialYearSF++;
					$index++;
				}
								
			}
			
			
			$ns['profitlossdata']['yearlypurchases'] 			= $indexedypurchases;
			
			//highlight_string(var_export($indexedypurchases, true));
			
			
			$financialYearSF = $sales->startFinancialYear;
			
			$index = 0;
			
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				$tmpval = isset($years[$financialYearSF])?$years[$financialYearSF]:0;
				$major_purchase[$index] = $tmpval;
				$financialYearSF++;
				$index++;
			}
			
			$bookvalues = array();
				
			$bookvalues[0] = array_sum($monthly_detail_purchases) - $year1depreciation;
						
			$financialYearSF = $sales->startFinancialYear;
			
			$index = 0;
			
			$yearlydepreciation 	= array();
			$yearlydepreciation[0] 	= $year1depreciation; 
			
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				if ($index > 0 ) {
					$yearlydepreciation[$index] = ($bookvalues[$index-1] + $yearlydepreciation[$index-1]) * $p;
					$bookvalues[$index]			= $bookvalues[$index-1] + $major_purchase[$index] - $yearlydepreciation[$index-1];
				}
				
				$index++;
				
				
			}
			
			$limit = count($allSalesDetails[0]['financial_status']);
			
			for($i = 0; $i < $limit; $i++) {
				$data[] = $yearlydepreciation[$i];
				$td[] = $yearlydepreciation[$i];
			}
			
								
					
			$ns['profitlossdata']['yearlymajor_purchase'] = $major_purchase;

			$ns['profitlossdata']['yearlydepreciation'] = $data;
			$ns['profitlossdata']['yearlydepreciationrows'] = $td;


			$array_depreciation = $data;
			
			
			
			$tmpvalues = array();
			$tmpvalues1 = array();
			$tmpvalues2 = array();
			
			
			for($i=0; $i<12;$i++) {
				if ($i > 0) {
					$tmpvalues[$i] = $ns['profitlossdata']['monthly_detail_purchases'][$i] + $tmpvalues[$i-1];
					$tmpvalues1[$i] = $ns['profitlossdata']['monthlydepreciation'][$i] + $tmpvalues1[$i-1];
					
				} else {
					$tmpvalues[$i] = $ns['profitlossdata']['monthly_detail_purchases'][$i];
					$tmpvalues1[$i] = $ns['profitlossdata']['monthlydepreciation'][$i];
				}	

				$tmpvalues1[$i] = -1 * abs($tmpvalues1[$i]);
				
				$tmpvalues2[$i] = $tmpvalues[$i] + $tmpvalues1[$i];
				
			}
			
			$ns['profitlossdata']['monthly_acculongtermassets'] = $tmpvalues;
			//$ns['profitlossdata']['monthly_accudepreciation'] 	= $tmpvalues1;
			$ns['profitlossdata']['monthly_totallongassets'] 	= $tmpvalues2;
			
			//end depreciation and amortization


			//<!--------------------       INCOME TAXES    ------------------------------------->
			$_loanInvestment = new loansInvestments_lib();
			$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
			//print_r($allloanInvestmentProjection);
			$sumOfEachLoan = "";

			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;

			$td = array("Income Taxes");
			$array_incomeTax = array();

			if(isset($array_eachYrEstimatedIncomeTax))
			{
				$array_eachYrEstimatedIncomeTax = $array_eachYrEstimatedIncomeTax;
			}

			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				//print_r($yrInterestIncured);
				if(count($array_eachYrEstimatedIncomeTax) > 0)
				{
					$array_incomeTax[$e_yr] = $array_eachYrEstimatedIncomeTax[$e_yr];
				}
				else
				{
					$array_incomeTax[$e_yr] = 0;
				}

				if($array_incomeTax[$e_yr] < 0)
				{
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}

				$td[] = $open_bracket . ($array_incomeTax[$e_yr] * $cancelNegative) . $closed_bracket;

			}

			
			
			$ns['profitlossdata']['yearlyincometaxrows'] = $td;
			

			//<!--------------------------------------------------------------------------->
			//<!--------------------	NET PROFIT	----------------------------------------->
			$sumOfEachLoan = "";					
			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;    
			$td = array("Net Profit");		
			$array_netProfit = array();
			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				if((count($operatingIncome) > 0) and (count($array_interestIncured) > 0) and (count($array_incomeTax) > 0))
				{
					/*---	Take off any available comas	---*/
					$operatingIncome[$e_yr] = 			str_replace(",", "", $operatingIncome[$e_yr]);
					$array_interestIncured[$e_yr] = 	str_replace(",", "", $array_interestIncured[$e_yr]);
					$array_incomeTax[$e_yr] = 			str_replace(",", "", $array_incomeTax[$e_yr]);
					
					$array_netProfit[$e_yr] = ($operatingIncome[$e_yr] - ($array_interestIncured[$e_yr] + $array_incomeTax[$e_yr]+
					$array_depreciation[$e_yr]));
				}
				else
				{
					$array_netProfit[$e_yr] = 0;
				}
				
				//$array_netProfit[$e_yr] = ($array_netProfit[$e_yr] * -1);
				
				if($array_netProfit[$e_yr] < 0)
				{
					
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}
				
				
				$td[] = $open_bracket . ($array_netProfit[$e_yr] * $cancelNegative) . $closed_bracket;
				$td_raw[] = $array_netProfit[$e_yr];
			} 

			
			
			$ns['profitlossdata']['yearlynetprofitrows'] = $td;
			$ns['profitlossdata']['yearlynetprofit'] = $td_raw;
			
			//<!--------------------------------------------------------------------------->
			
			
			
			
			
			
			//<!--------------------	NET PROFIT / SALES	--------------------------------->
			$sumOfEachLoan = "";			
			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;
			
			$array_netProfitSales = array();
			$array_revenue = array();
			$array_revenue = $totalSales_format;
			
			$td = array('Net Profit/Sales');
			
			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				if((count($array_netProfit) > 0) and (count($array_revenue) > 0))
				{
					/*---	Take off any available comas	---*/
					$array_revenue[$e_yr] = 			str_replace(",", "", $array_revenue[$e_yr]);
					$array_netProfit[$e_yr] = 	str_replace(",", "", $array_netProfit[$e_yr]);
					
					$array_netProfitSales[$e_yr] = (($array_netProfit[$e_yr] / $array_revenue[$e_yr]) * 100);
				}
				else
				{
					$array_netProfitSales[$e_yr] = 0;
				}
				if($array_netProfitSales[$e_yr] < 0)
				{
					
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}
				
				
				$td[] = $open_bracket . ($array_netProfitSales[$e_yr] * $cancelNegative) . '%' . $closed_bracket;
				
			}
			
				
			
			//keep net profit dales in salesdata
			$ns['salesdata']['netprofitsales'] = $array_netProfitSales;
			$ns['profitlossdata']['yearlynetprofitsalesrows'] = $td;
		

		}
	
	
	
	//END PROFIT AND LOSS STATEMENT
	
		
		
		//BEGIN APPENDIX PROFIT AND LOSS
		
		$monthrows = $ns['salesdata']['monthlyGrossMPercentage'];
		$monthrows = array_merge( array('Gross Margin %') , $monthrows);
		
		
		
		//data is calculated in expenses
		$monthlytotalsalary			= $ns['expensesdata']['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $ns['expensesdata']['monthlytotalrelatedexpenses'];
		
		
		
		$monthlyotherexpenses = $ns['expensesdata']['monthlyotherexpenses'];
		
		//echo highlight_string(var_export($monthlytotalexpenses, TRUE));
		
		//Forreach($monthlyotherexpenses as $key=>$value) {
		//	$thtml->addLTDRow(array_merge(array($key),$this->farraynumber($value)), null);
		//}
		
		
		
		$monthlytotalexpenses		= $ns['expensesdata']['monthlytotalexpenses'];
		
		
		
		$monthrows = $ns['salesdata']['monthlyGrossMargin'];
		
		$tmprows = array();
		
		for($i = 0; $i < 12; $i++){
			$tmprows[$i] = $monthrows[$i] -  $monthlytotalexpenses[$i];
		}
		
		
		$monthlyoperatingincome = $tmprows;
		
		
		
		//add depreciation
		$monthlyvals = $ns['profitlossdata']['monthly_accudepreciation'];
		
		//$ns['profitlossdata']['monthly_accudepreciation']
		
		
		
			
		
		//calculate monthtly incometax
		//loop through this for number of years
		
		$monthlygrossmargin 		= $ns['salesdata']['monthlyGrossMargin'];
		$montylyinterestincurred 	= $ns['profitlossdata']['monthlyinterestincurredrows'];
		$monthlyrows = array();
		$tmprows	 = array();
		
		$expenditure    = new expenditure_lib();
		$incomeTaxRate  =  $expenditure->incomeTaxRate;
		
		$tmptotalexpense = array();
		
		$depreciation = $ns['profitlossdata']['monthly_accudepreciation'];
		
		//echo "tax rate: " . $incomeTaxRate;
		
		for($i = 0; $i < 12; $i++)
		{
		$totalexpense 	= $monthlytotalexpenses[$i];
		$grossmargin 	= $monthlygrossmargin[$i];
		$interestincur	= $montylyinterestincurred[$i];
			
		$monthlyrows[$i] = ($grossmargin - $totalexpense - $interestincur);		//operating income * taxrate/100
		$tmprows[$i] = (($monthlyoperatingincome[$i] * $incomeTaxRate) / 100);
			
		
		if($monthlyrows[$i] < 0) {
		$monthlyrows[$i] = 0;
		}
		
		$tmptotalexpense[$i] = $interestincur + $tmprows[$i] + $depreciation[$i]; //income tax + interest incurred + depreciation
			
		}
		
		$monthlyincometaxes = $tmptotalexpense;
		
		$ns['profitlossdata']['monthlyincometax'] = $tmprows;
		
		
		
		
					
				$monthlydirectcost	= array_slice($ns['profitlossdata']['monthlydirectcostrows'], 1);
		
				$revenue = $ns['profitlossdata']['monthlyrevenuerows'];
		
				$tmprows	 = array();
				$monthlyrows = array();
		
		
				$pltotalexpense = array();
		
				for($i = 0; $i < 12; $i++)
				{
				$expense 			= $monthlydirectcost[$i] + $monthlytotalexpenses[$i] + $monthlyincometaxes[$i];
				$pltotalexpense[]	= $expense;
				$monthlyrows[$i] 	= $revenue[$i+1] - $expense;
				$tmprows[$i] 		= $revenue[$i+1] > 0 ? ($monthlyrows[$i] / $revenue[$i+1] * 100) : 0 ;
				}
		
				$ns['profitlossdata']['monthlynetprofit'] = $monthlyrows;
				//highlight_string(var_export($revenue,true));
				//highlight_string(var_export($monthlytotalexpenses,true));
				//highlight_string(var_export($monthlyincometaxes,true));
		
				$ns['expensesdata']['balYearlyTotalExpenses'] = $pltotalexpense;
		
		
				//highlight_string(var_export($ns['expensesdata']['balYearlyTotalExpenses'],true));
		
				//TOTAL EXPENSE
				
				$ns['salesdata']['netprofit'] = $monthlyrows;
		
							
		
				$years 	= $ns['salesdata']['years'];
		
				$yearlyTotalSalary		= $ns['expensesdata']['yearlyTotalSalary'];
				$yearlyTotalRSalary		= $ns['expensesdata']['yearlyTotalRSalary'];
		
											
		
				$yearlyOperatingTotalExpenses	= $ns['expensesdata']['yearlyTotalExpenses'];
												
				$tmprows = $ns['profitlossdata']['yearlyoperatingincomerows'];
		
		
				$TotalExpenses = array();
		
				$ns['profitlossdata']['yearlyinterestincurredrows'][1] = array_sum($montylyinterestincurred);
		
				for($i = 1; $i < 4; $i++ ) {
														//direct cost
					$dcost = str_replace(array($ns['salesdata']['currency'],','), '', $ns['profitlossdata']['yearlydirectcostrows'][$i]);
					$dcost = floatval($dcost);
					$iincur = str_replace(array($ns['salesdata']['currency'],',','(',')'), '', $ns['profitlossdata']['yearlyinterestincurredrows'][$i]);
					$iincur = floatval($iincur);
					$dep	= $ns['profitlossdata']['yearlydepreciation'][$i-1];
					$itax 	= str_replace(array($ns['salesdata']['currency'],','), '', $ns['profitlossdata']['yearlyincometaxrows'][$i]);
					$itax	= floatval($itax);
																
					$totaloperatingexpense = $yearlyOperatingTotalExpenses[$i-1];
																
					$TotalExpenses[] = $dcost + $iincur + $dep + $itax + $totaloperatingexpense;
		}
		
		
															//echo highlight_string(var_export($TotalExpenses, TRUE));
															//echo highlight_string(var_export($ns['profitlossdata']['yearlyinterestincurredrows'], TRUE));
															//echo highlight_string(var_export($ns['profitlossdata']['yearlydirectcostrows'], TRUE));
															//echo highlight_string(var_export($ns['profitlossdata']['yearlydepreciation'], TRUE));
															//echo highlight_string(var_export($ns['profitlossdata']['yearlyincometaxrows'], TRUE));
		
		$ns['expensesdata']['balYearlyTotalExpenses'] = $TotalExpenses;
		
		// END APPENDIX PROFIT AND LOSS
	

		
		//PDF CALC CASH
		//monthly cash in hand
		$monthlytotalsales			= $ns['salesdata']['monthlyTotalSales'];
		$monthlytotalexpenses		= $ns['expensesdata']['monthlytotalexpenses'];
		$monthlygrossmargin 		= $ns['salesdata']['monthlyGrossMargin'];
		$monthylyinterestincurred 	= $ns['profitlossdata']['monthlyinterestincurredrows'];
		$monthlyreceive 			= $ns['loansdata']['$monthlyreceive'];
		$monthlypayment				= $ns['loansdata']['$monthlypayment'];
		$monthlycash				= array();
		$tmploans					= $ns['loansdata']['monthly']['loansrows'];
		$tmploans					= array_slice($tmploans[0], 1);
		
		$monthlypurchase 			= $ns['profitlossdata']['monthly_detail_purchases'];
		
		
		$cashsettinglib = new cashFlowProjection_lib();
		
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			$cashsetting 	= $cashsettinglib->Payments($businessPlanId);
			if ($cashsetting.length) {
				$cashsetting = $cashsetting[0];
			}
		}
		
		//echo highlight_string(var_export($cashsetting, TRUE));
		
		
		$percentoncredit 	= $cashsetting['percentage_sale']/100;
		$daystocollect		= $cashsetting['days_collect_payments'];
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		$collectpercent = array();
		$collectdays	= array();
		
		for($i = 0; $i < 20; $i++) {
			$collectpercent[] 	= ($i * 5)/100;
			$collectdays[]		= ($i * 30);
		}
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsReceivable 		= array();
		$MonthlyAccountsReceivable[-1] 	= 0;
		$TotalAccountsReceivable		= array();
		$TotalAccountsReceivable[-1]	= 0;
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $monthlytotalsales[$j];
			} else {
				$tmprow[$j] = $monthlytotalsales[$j]*(1-$percentoncredit);
			}
		
		}
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
		//echo highlight_string(var_export($cashcollected[0], TRUE));
		
		
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			//$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
		
			$MonthlyAccountsReceivable[$i-1] 	= $monthlytotalsales[$i-1] - $cashcollectedcurrent[$i-1];
			$TotalAccountsReceivable[$i-1]		= $TotalAccountsReceivable[$i-2] + $monthlytotalsales[$i-1] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsReceivable[$i-1] : 0 );
				}
			}
		
		}
		
		
		$TotalAccountsReceivable = array_slice($TotalAccountsReceivable,1);
		
		$ns['expensesdata']['TotalAccountsReceivable'] = $TotalAccountsReceivable;
		
		
		$cashcollectedreceivable 		= $cashcollected;
		$totalcashcollectedreceivable 	= $totalcashcollected;
		//echo highlight_string(var_export($cashcollectedreceivable, TRUE));
		
		//echo highlight_string(var_export($totalcashcollectedreceivable, TRUE));
		
		//Calculate monthly payable
		$percentoncredit 	= $cashsetting['percentage_purchase']/100;
		$daystocollect		= $cashsetting['days_make_payments'];
		
		$tmptotalDirectCost	= $ns['salesdata']['monthlyTotalDirectCost'];
		
		$monthlytotalsalary			= $ns['expensesdata']['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $ns['expensesdata']['monthlytotalrelatedexpenses'];
		$monthlytotalexpenses		= $ns['expensesdata']['monthlytotalexpenses'];
		
		$totalExpenses = array();
		
		for($j = 0; $j < 12; $j++ ){
			$totalExpenses[] = $tmptotalDirectCost[$j] + $monthlytotalexpenses[$j] - $monthlytotalsalary[$j];
		}
		
		
		
		
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsPayable 		= array();
		$MonthlyAccountsPayable[-1] 	= 0;
		$TotalAccountsPayable			= array();
		$TotalAccountsPayable[-1]		= 0;
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $totalExpenses[$j];
			} else {
				$tmprow[$j] = $totalExpenses[$j] * (1-$percentoncredit);
			}
		
		}
		
		
		
		
		
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
		
		
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			$MonthlyAccountsPayable[$i-1] 	= $totalExpenses[$i-1] - $cashcollectedcurrent[$i-1];
			$TotalAccountsPayable[$i-1]		= $TotalAccountsPayable[$i-2] + $totalExpenses[$i-1] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsPayable[$i-1] : 0 );
				}
			}
		
		}
		
		
		
		
		
		$MonthlyAccountsPayable = array_slice($MonthlyAccountsPayable,1);
		$TotalAccountsPayable = array_slice($TotalAccountsPayable,1);
		
		$totalcashcollectedpayable = $totalcashcollected;
		
		//echo highlight_string(var_export($totalcashcollectedpayable, TRUE));
		
		$ns['loansdata']['MonthlyAccountsPayable'] = $MonthlyAccountsPayable;
		
		
		//calculate monthly cash
		$monthlycash = array();
		
		$mhtml = "<table>";
		
		$incometax = $ns['profitlossdata']['monthlyincometax'];
		
		
		for($i = 0; $i < 12; $i++) {
		
			if ($i>0) {
				$monthlycash[$i] = $monthlycash[$i-1];
			}
		
			$monthlycash[$i] = $monthlycash[$i] + $monthlyreceive[$i] - $monthlypayment[$i] + $totalcashcollectedreceivable[$i];
			$monthlycash[$i] = $monthlycash[$i] - $totalcashcollectedpayable[$i] - $monthlytotalsalary[$i] - $monthlytotalrelatedexpenses[$i];
			$monthlycash[$i] = $monthlycash[$i] - $monthylyinterestincurred[$i] - $monthlypurchase[$i] - $incometax[$i];
		
		}
		
		
		
		
		echo highlight_string(var_export($monthlycash, TRUE));
		
		$ns['loansdata']['monthlycash'] = $monthlycash;
		
		
		/*
		 for($i = 0; $i < 12; $i++) {
		$tmpoperatingIncomes = $margin - $monthlytotalexpenses[$i];
		
		$tmploan[$i] = $tmploansrows[$i] + $monthlyreceive[$i] - $monthlypayment[$i] + $tmpoperatingIncomes;
		
		if ($i==1) {
		$tmploan[$i] = $tmploan[$i] + $tmploan[0];
		} elseif ( $i != 0 ) {
		$tmploan[$i] = $tmploan[$i] + ($tmploan[$i-1] - $tmploan[$i-2]);
		}
		
		$monthlycash[] = ($tmploan[$i] - $monthlyreceive[$i]) + $monthlypayment[$i] - $monthlyincometax[$i];
			
		
		}
		*/
		//$ns['loansdata']['monthlycash'] = $monthlycash;
		
		//fill empty keys
		for($i = 0; $i < 12; $i++) {
			if (isset($accountReceivable_allMonths[$i+1])) {
				$accountReceivable_allMonths[$i] = $accountReceivable_allMonths[$i+1];
				unset ($accountReceivable_allMonths[$i+1]);
			} elseif (isset($accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)])) {
				$accountReceivable_allMonths[$i] = $accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)];
				unset ($accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)]);
			} else {
				$accountReceivable_allMonths[$i] = 0 ;
			}
		}
		
		//$ns['loansdata']['accountReceivable_allMonths'] = $accountReceivable_allMonths;
		
		$ns['loansdata']['accountReceivable_allMonths'] = $TotalAccountsReceivable;
		
		//echo highlight_string(var_export($accountReceivable_allMonths, TRUE));
		//$accountReceivable_allMonths
		//end monthly cash in hand
		
		//total current assets
		$totalcurrentassets_monthly = array();
		for($i = 0; $i < 12; $i++) {
			$totalcurrentassets_monthly[$i] = $monthlycash[$i] + $TotalAccountsReceivable[$i];
		}
		
		$yrAccountReceivable = array();
		
		
		
		$balTotalSales = array();
		
		$tarray = $ns['salesdata']['yrlyTotalSales'];
		
		$currency = $ns['salesdata']['currency'];
		
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balTotalSales[] = floatval($value);
		}
		
		$balTotalExpenses = array();
		
		$tarray = $ns['salesdata']['yrlyTotalCosts'];
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balTotalExpenses[] = floatval($value);
		}
		//echo highlight_string(var_export($TotalAccountsReceivable, TRUE));
		//echo highlight_string(var_export($yrAccountPayable, TRUE));
		
		
		$yrAccountReceivable[0] = $TotalAccountsReceivable[11];
		$yrAccountReceivable[1] = $yrAccountReceivable[0]/$balTotalSales[1]*$balTotalSales[2];
		$yrAccountReceivable[2] = $yrAccountReceivable[0]/$balTotalSales[1]*$balTotalSales[3];
		
		$yrAccountPayable = array();
		$yrAccountPayable[0] = $TotalAccountsPayable[11];
		$yrAccountPayable[1] = $yrAccountPayable[0]/$balTotalExpenses[1]*$balTotalExpenses[2];
		$yrAccountPayable[2] = $yrAccountPayable[0]/$balTotalExpenses[1]*$balTotalExpenses[3];
		
		$expectedloanspayment = array(0,0,0);
		
		$ns['loansdata']['totalcurrentassets_monthly'] = $totalcurrentassets_monthly;
		
		
		$major_purchase = $ns['profitlossdata']['yearlymajor_purchase'];
		
		
		
		
		
		
		
		/*=========================================*/
		
		
		/*
		 highlight_string(var_export($TotalExpenses, TRUE));
		
		echo '<br>yearly interest incurred: ' ;
		highlight_string(var_export($ns['profitlossdata']['yearlyinterestincurredrows'], TRUE));
		echo '<br>yearly direct cost incurred: ' ;
		highlight_string(var_export($ns['profitlossdata']['yearlydirectcostrows'], TRUE));
		echo '<br>yearly depreciation: ' ;
		highlight_string(var_export($ns['profitlossdata']['yearlydepreciation'], TRUE));
		echo '<br>yearly incometax: ' ;
		highlight_string(var_export($ns['profitlossdata']['yearlyincometaxrows'], TRUE));
		*/
		
		//$ns['expensesdata']['balYearlyTotalExpenses'] = $TotalExpenses;
		
		$yearlyTotalExpenses = $ns['expensesdata']['balYearlyTotalExpenses'];
		
		
		/*========================================*/
		
		
		
		
		$tmpvals  = $ns['profitlossdata']['yearlydepreciation'];
		
		
		$yrAmountReceive1 = $ns['loansdata']['yearly']['totalrows'];
		
		$yrAmountReceive = array();
		
		$tarray = $yrAmountReceive1;
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$yrAmountReceive[] = floatval($value);
		}
		
		
		
		
		
		
		$balRevenue = array();
		
		$tarray = $ns['profitlossdata']['yearlyrevenuerows'];
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balRevenue[] = floatval($value);
		}
		
		$balmoloansdata1 = $ns['loansdata']['monthly']['totalrows'];
		$balmoloansdata	 = array();
		$tarray = $balmoloansdata1;
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balmoloansdata[] = floatval($value);
		}
		
		
		$expectedloanspayment[0] = array_sum($balmoloansdata);
		
		$balRevenue 		= array_slice($balRevenue, 1);
		$yrAmountReceive 	= array_slice($yrAmountReceive, 1);
		
		
		$cash = array();
		
		$cash[1] = $ns['loansdata']['monthlycash'][11];
		
		
		$cash[2] = $cash[1]
		+ $yrAccountReceivable[0]
		+ $balRevenue[1]
		- $yrAccountReceivable[1]
		+ $yrAmountReceive[1]
		- $expectedloanspayment[1]
		- $major_purchase[1]
		- $yrAccountPayable[0]
		- $yearlyTotalExpenses[1]
		+ $yrAccountPayable[1]
		+ $tmpvals[1];
		
		
		$cash[3] = $cash[2]
		+ $yrAccountReceivable[1]
		+ $balRevenue[2]
		- $yrAccountReceivable[2]
		+ $yrAmountReceive[2]
		- $expectedloanspayment[2]
		- $major_purchase[2]
		- $yrAccountPayable[1]
		- $yearlyTotalExpenses[2]
		+ $yrAccountPayable[2]
		+ $tmpvals[2];
		
		
		/*
		 echo '<br>cash 1<br>';
		echo $cash[2] . '<br>';
		echo $yrAccountReceivable[1] . '<br>';
		echo $balRevenue[2] . '<br>';
		echo $yrAccountReceivable[2] . '<br>';
		echo $yrAmountReceive[2] . '<br>';
		echo $expectedloanspayment[2] . '<br>';
		echo $major_purchase[2] . '<br>';
		echo $yrAccountPayable[1] . '<br>';
		echo $yearlyTotalExpenses[2] . '<br>';
		echo $yrAccountPayable[2] . '<br>';
		echo $tmpvals[2] . '<br>';
		*/
		
		
		
		$ns['balancesheetdata']['balcash'] = $cash;
		$ns['balancesheetdata']['balaccreceivable'] = $yrAccountReceivable;
		$ns['balancesheetdata']['balaccpayable'] = $yrAccountPayable;
		
		//highlight_string(var_export($cash, TRUE));
		
		
		
		
		
		/*
		 echo '<br>$yrAccountReceivable: ';
		highlight_string(var_export($yrAccountReceivable, TRUE));
		echo '<br>$balRevenue: ';
		highlight_string(var_export($balRevenue, TRUE));
		echo '<br>$yrAccountReceivable: ';
		highlight_string(var_export($yrAccountReceivable, TRUE));
		echo '<br>$yrAmountReceive: ';
		highlight_string(var_export($yrAmountReceive, TRUE));
		echo '<br>$expectedloanspayment: ';
		highlight_string(var_export($expectedloanspayment, TRUE));
		echo '<br>$major_purchase: ';
		highlight_string(var_export($major_purchase, TRUE));
		echo '<br>$yrAccountPayable: ';
		highlight_string(var_export($yrAccountPayable, TRUE));
		echo '<br>$yearlyTotalExpenses: ';
		highlight_string(var_export($yearlyTotalExpenses, TRUE));
		echo '<br>$yrAccountPayable: ';
		highlight_string(var_export($yrAccountPayable, TRUE));
		echo '<br>$tmpvals: ';
		highlight_string(var_export($tmpvals, TRUE));
		*/
		$balTotalCurrentAssets = array();
		for($i = 0; $i < 3; $i++) {
			$balTotalCurrentAssets[$i] = $cash[$i+1] + $yrAccountReceivable[$i];
		}
		
		$monthly_totallongassets = $ns['profitlossdata']['monthly_totallongassets'];
		
		$balLongTermsAssets = array();
		$balLongTermsAssets[] = $monthly_totallongassets[11];
		for($i = 1; $i < 3; $i++) {
			$balLongTermsAssets[$i] = $balLongTermsAssets[$i-1] + $major_purchase[$i];
		}
		
		$monthly_accudepreciation = $ns['profitlossdata']['monthly_balaccudepreciation'];
		
		$balAccuDepreciation = array();
		$balAccuDepreciation[0] = $monthly_accudepreciation[11];
		$balAccuDepreciation[1] = $balAccuDepreciation[0] + $tmpvals[1];
		$balAccuDepreciation[2] = $tmpvals[0] - $tmpvals[1] - $tmpvals[2];
		
		
		$balTotalLongTermsAssets = array();
		for($i = 0; $i < 3; $i++) {
			$balTotalLongTermsAssets[$i] = $balLongTermsAssets[$i] + $balAccuDepreciation[$i];
		}
		
		$balTotalAssets = array();
		for($i = 0; $i < 3; $i++) {
			$balTotalAssets[$i] = $balTotalCurrentAssets[$i] + $balTotalLongTermsAssets[$i];
		}
		
		$ns['profitlossdata']['ns']['balTotalCurrentAssets'] = $balTotalCurrentAssets;
		$ns['profitlossdata']['ns']['balLongTermsAssets']	 = $balLongTermsAssets;
		$ns['profitlossdata']['ns']['balAccuDepreciation']	 = $balAccuDepreciation;
		$ns['profitlossdata']['ns']['balTotalLongTermsAssets']	 = $balTotalLongTermsAssets;
		$ns['profitlossdata']['ns']['balTotalAssets']	 		= $balTotalAssets;
		
		
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $ns['loansdata']['$monthlyreceive'][$i] - 	$ns['loansdata']['$monthlypayment'][$i];
		}
		
		$balSalesTax 		= array(0,0,0);
		$balShortTermDebt 	= array(0,0,0);
		$balTotalCurrentLiability = array(); //value should be account payable + sales tax + short term debt, but for now sales tax and short term debt values are zero
		
		for($i = 0; $i < 3; $i++) {
			$balTotalCurrentLiability[] = $yrAccountPayable[$i] + $balSalesTax[$i] + $balShortTermDebt[$i];
		}
		
		
		
		$balLongTermDebt 	= array();
		$balLongTermDebt[0] =  $tmpvalues[11];
		for($i = 1; $i < 3; $i++) {
			$balLongTermDebt[$i] =  $balLongTermDebt[$i-1] +  $yrAmountReceive[$i] - $expectedloanspayment[$i] ;
		}
		
		
		$balTotaLiabilities = array();
		for($i = 0; $i < 3; $i++) {
			$balTotaLiabilities[] = $balTotalCurrentLiability[$i] + $balLongTermDebt[$i];
		}
		
		
		$ns['profitlossdata']['ns']['balTotalCurrentLiability']	 	= $balTotalCurrentLiability;
		$ns['profitlossdata']['ns']['balLongTermDebt']	 			= $balLongTermDebt;
		$ns['profitlossdata']['ns']['balTotaLiabilities']	 		= $balTotaLiabilities;
		
		
		
		//monthly retained earnings and earnings
		$balMonthlyEarnings = $ns['salesdata']['netprofit'];
			
		
		$balMonthlyRetainedEarnings 	= array();
		$balMonthlyRetainedEarnings[0] = 0;
		for ($i = 1; $i < 12; $i++ ) {
			$balMonthlyRetainedEarnings[$i] = $balMonthlyRetainedEarnings[$i-1] + $balMonthlyEarnings[$i-1];
		}
		
		$balNetProfit1 = $ns['profitlossdata']['yearlynetprofitrows'];
		$balNetProfit = array();
		foreach($balNetProfit1 as $key=>$value) {
			$value = str_replace(array($currency,',',')','('), '', $value);
			$balNetProfit[] = floatval($value);
		}
		
		$balNetProfit = array_slice($balNetProfit,1);
		
		
		$balEarnings		= array();
		$balEarnings[0]		=  $balEarnings[11] + $balMonthlyRetainedEarnings[11];
		$balEarnings[1]		=  $balNetProfit[1];
		$balEarnings[2]		=  $balNetProfit[2];
		
		$balRetainedEarnings = array();
		$balRetainedEarnings[0] =  0;
		
		for ($i = 1; $i < 3; $i++ ) {
			$balRetainedEarnings[$i] = $balRetainedEarnings[$i-1] + $balEarnings[$i-1];
		}
		
		
		$balTotalOwnerEquity = array();
		for ($i = 0; $i < 3; $i++ ) {
			$balTotalOwnerEquity[$i] = $balRetainedEarnings[$i] + $balEarnings[$i];
		}
		
		$balTotalLiabilitiesAndEquities = array();
		for ($i = 0; $i < 3; $i++ ) {
			$balTotalLiabilitiesAndEquities[$i] = $balTotalOwnerEquity[$i] + $balTotaLiabilities[$i];
		}
		
		$ns['profitlossdata']['ns']['balEarnings']	 			= $balEarnings;
		$ns['profitlossdata']['ns']['balRetainedEarnings']	 	= $balRetainedEarnings;
		$ns['profitlossdata']['ns']['balTotalOwnerEquity']	 	= $balTotalOwnerEquity;
		$ns['profitlossdata']['ns']['balTotalLiabilitiesAndEquities']	= $balTotalLiabilitiesAndEquities;
		
		
		
		
		
		
