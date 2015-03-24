 
    <?php
    	$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
		//print_r($allloanInvestmentProjection);		
		$sumOfEachLoan = "";			
		
		$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;	
	?>
    
       	<div class="row row-group_footer singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Net Profit/Sales</p>
              </span>
              <?php
               $array_netProfitSales = array();
			   $array_revenue = array();
			   $array_revenue = $totalSales_format;
			 
			   
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
					
						
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $open_bracket;?><?php echo number_format(($array_netProfitSales[$e_yr] * $cancelNegative), 0, '.', ','); ?>%<?php echo $closed_bracket;?></p>
					</span>
				<?php
				} 
				?>
				
            <div class="x-clear"></div>
        </div><!--end .singleline-->

   
