 
    <?php
	
	
		
		
		
	
    	$_loanInvestment = new loansInvestments_lib();
		$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;	
		
		// Making use of these two array lists
		$totalDirectCost_format;
		$allExpense;
	?>
    
       	<div class="row row-group_footer singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Total Expenses</p>
              </span>
              <?php
               $array_netProfit = array();
			   
				 // loop through this for number of years
				for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
				{
					if((count($allExpense) > 0) and (count($totalDirectCost_format) > 0))
					{
						/*---	Take off any available comas	---*/
						$array_allExpense_new[$e_yr] = 			str_replace(",", "", $allExpense[$e_yr]);
						$array_totalDirectCost_format_new[$e_yr] = 	str_replace(",", "", $totalDirectCost_format[$e_yr]);						
						
						$array_netProfit[$e_yr] = ($operatingIncome[$e_yr] - ($array_interestIncured[$e_yr] + $array_incomeTax[$e_yr]+$array_depreciation[$e_yr]));
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
					
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $open_bracket;?><?php echo $currency. number_format(($array_netProfit[$e_yr] * $cancelNegative), 0, '.', ','); ?><?php echo $closed_bracket;?></p>
					</span>
				<?php
				} 
				?>
				
            <div class="x-clear"></div>
        </div><!--end .singleline-->

   
