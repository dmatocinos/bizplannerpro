<?php
	
	// Updated 08/June/2013
	
	/*------------------------------------------------------------------------------------------*/
	/* All files involved in calculation of the whole software									*/
	/*------------------------------------------------------------------------------------------*/                    
	
	
	define("BUDGET", BASE_PATH."/pages/financial-plan/budget" ,true)	;					
	$expenditure = new expenditure_lib();
	
	$incomeTaxRate =  $expenditure->incomeTaxRate;
	
						
	include_once(BUDGET."/all_calculations/revenue_calc.php");
		
	include_once(BUDGET."/all_calculations/direct-cost_calc.php");
	 
	include_once(BUDGET."/all_calculations/gross_margin_calc.php");
	
	include_once(BUDGET."/all_calculations/total-expenses_calc.php");
	
	include_once(BUDGET."/all_calculations/loans-and-investments_calc.php");
	
	include_once(BUDGET."/all_calculations/interest-incurred_calc.php");
	
	include_once(BUDGET."/all_calculations/account-receivable_calc.php");
	
	include_once(BUDGET."/all_calculations/account-payable_calc.php");
	
	
	
?>
<?php
		//global_lib::log($array_interestIncured);
		//global_lib::log($major_purchases_data['yearlydepreciation']);
		//global_lib::log($operatingIncome);
		/*----------------------------------------------------------------------
			Income Tax Calculation
		------------------------------------------------------------------------*/
		$yrsOfFinancialForecast = $expenditure->financialYear();
		
		//for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
		//{
			//echo $_SESSION['sessionOfarrayOfYrlyCalcInterest'];	
		//}
		
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
		
		/*global_lib::log($totalDirectCost);
		global_lib::log($array_totalExpenses);
		global_lib::log($array_interestIncured);
		global_lib::log($major_purchases_data['yearlydepreciation']);
		global_lib::log($totalSales);*/
		
		//loop through this for number of years
		for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
		{
			$array_estimatedIncomeTax[$e_year] = ($operatingIncome[$e_year] - $major_purchases_data['yearlydepreciation'][$e_year] - $array_interestIncured[$e_year] ); 
			$array_eachYrEstimatedIncomeTax[$e_year] = (($array_estimatedIncomeTax[$e_year] * $incomeTaxRate) / 100);
			$array_eachYrEstimatedIncomeTax[$e_year] = number_format($array_eachYrEstimatedIncomeTax[$e_year], 0, '.', '');
			
			$array_netProfit[$e_year] = $totalSales[$e_year] - (
				$totalDirectCost[$e_year] + 
				$array_totalExpenses[$e_year] + 
				$array_interestIncured[$e_year] + 
				$major_purchases_data['yearlydepreciation'][$e_year] +
				$array_eachYrEstimatedIncomeTax[$e_year]
			);
			
			$array_netProfitFormat[$e_year] = global_lib::formatDisplayWithBrackets($array_netProfit[$e_year], $currency);
		} 	
		
		//global_lib::log($array_netProfit);
			//$_SESSION['array_eachYrEstimatedIncomeTax'] = $array_eachYrEstimatedIncomeTax;
		include_once(BUDGET."/all_calculations/cash-in-hand_calc.php");
		
		?>
