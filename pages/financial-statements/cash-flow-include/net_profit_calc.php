    <?php
    	$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
		//print_r($allloanInvestmentProjection);		
		$sumOfEachLoan = "";			
		
		$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;	
	?>
              <?php
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
						
						$array_netProfit[$e_yr] = ($operatingIncome[$e_yr] - ($array_interestIncured[$e_yr] + $array_incomeTax[$e_yr] +$array_depreciation[$e_yr]));
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

					$array_netProfitFormat[$e_yr] = $open_bracket.$currency.number_format(($array_netProfit[$e_yr] * $cancelNegative), 0, '.', ',').$closed_bracket;
					
					?>
				<?php
				} 
				?>
				
    
