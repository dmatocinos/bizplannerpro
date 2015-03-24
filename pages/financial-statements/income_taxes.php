 
    <?php
    	$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
		//print_r($allloanInvestmentProjection);		
		$sumOfEachLoan = "";			
		
		$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;	
	?>
    
       	<div class="row row-item singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Income Taxes</p>
              </span>
              <?php
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
						
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency; ?><?php echo number_format(($array_incomeTax[$e_yr] * $cancelNegative), 0, '.', ',') ;?><?php echo $closed_bracket;?> </p>
					</span>
				<?php
					
				} 
				
				?>
				
            <div class="x-clear"></div>
        </div><!--end .singleline-->

   