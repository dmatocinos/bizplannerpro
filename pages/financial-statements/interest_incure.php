 
    <?php
    	$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
		//print_r($allloanInvestmentProjection);		
		$sumOfEachLoan = "";				
	?>
    
       
       <div class="preview-table salesForecast-preview table-4-columns profit_and_loss">      
     		
            <div class="row row-item singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Interest Incurred</p>
              </span>
              <?php
               
			   // loop through this for number of years
				for($eachFyear = 1; $eachFyear <= $_loanInvestment->numberOfFinancialYrForcasting; $eachFyear++)
				{
					$arraySummation = "";
					$counter = 0;	
					$counter_2 = 0;	
					$counter_3 = 0;	
					foreach($allloanInvestmentProjection as $eachLoanInvest)
					{
					 
						// CALCULATION FOR FINANCIAL YEAR ONE
						if($eachFyear == 1)
						{
							$currency = $sales->defaultCurrency;
							$zeroPrefix = 0;
							$twelveMonthsData = $sales->twelveMonths("", "");
							$receieve_sumOfEachMonthsInterestIncurred = array();
							$payBack_sumOfEachMonthsInterestIncurred = array();
							
							$monthCounter = 12;
							// loop through each month
							for($e_month = 1; $e_month <= count($twelveMonthsData); $e_month++ )
							{
								$monthCounter = ($monthCounter - 1); // reduce the counter from 12 to 0
								
								if($e_month < 10){$e_month = $zeroPrefix.$e_month;}// add zero to the back of $e_month to make it fit with the month array
								 
								// Receieve Interest 
								$receieve_InterestFirstStage  = (($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["limr_month_".$e_month]/12));
								$receieve_InterestSecondStage = (number_format($receieve_InterestFirstStage, 0, '.', ',') * $monthCounter);
								$receieve_sumOfEachMonthsInterestIncurred[$e_month] =  $receieve_InterestSecondStage;
				
								// Payback Interest
								$payBack_InterestFirstStage  = (($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["limp_month_".$e_month]/12));
								$payBack_InterestSecondStage = (number_format($payBack_InterestFirstStage, 0, '.', ',') * $monthCounter);
								$payBack_sumOfEachMonthsInterestIncurred[$e_month] =  $payBack_InterestSecondStage;
							}
							$recieve_sumOfEachLoan[$counter] = (array_sum($receieve_sumOfEachMonthsInterestIncurred));
							$payBack_sumOfEachLoan[$counter] = (array_sum($payBack_sumOfEachMonthsInterestIncurred));
							$counter = $counter + 1;
                     	}
						
						
						// CALCULATION FOR FINANCIAL YEAR TWO
						if($eachFyear == 2)
						{
							// Receive Interest
							$FYear2Receive_IncurredInterest[$counter_2]  = ($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["financial_receive"][0]['lir_total_per_yr']);
							$FYear2Receive_IncurredInterest[$counter_2] = number_format($FYear2Receive_IncurredInterest[$counter_2], 0, '.', ''); // round up to the nerest figure
							
							// PayBack Interest
							$FYear2PayBack_IncurredInterest[$counter_2]  = ($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["financial_payment"][0]['lip_total_per_yr']);
							$FYear2PayBack_IncurredInterest[$counter_2] = number_format($FYear2PayBack_IncurredInterest[$counter_2], 0, '.', ''); // round up to the nerest figure
							$counter_2 = $counter_2 + 1;
						}
						
						// CALCULATION FOR FINANCIAL YEAR THREE
						if($eachFyear == 3)
						{
							// Receive Interest
							$FYear3Receive_IncurredInterest[$counter_3]  = ($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["financial_receive"][1]['lir_total_per_yr']);
							$FYear3Receive_IncurredInterest[$counter_3] = number_format($FYear3Receive_IncurredInterest[$counter_3], 0, '.', ''); // round up to the nerest figure
							
							// PayBack Interest
							$FYear3PayBack_IncurredInterest[$counter_3]  = ($eachLoanInvest['loan_invest_interest_rate'] /100) * ($eachLoanInvest["financial_payment"][1]['lip_total_per_yr']);
							$FYear3PayBack_IncurredInterest[$counter_3] = number_format($FYear3PayBack_IncurredInterest[$counter_3], 0, '.', ''); // round up to the nerest figure
							$counter_3 = $counter_3 + 1;
						}
						
						//$grossMarginPercentageCounter = $grossMarginPercentageCounter + 1;
					}?>
					
				<?php
				if($eachFyear == 1)
				{
					// Deduce payback interest from Revieved ones
					$differenceBtwRecieveAndPayBackInterest_FY01 = array_sum($recieve_sumOfEachLoan) - array_sum($payBack_sumOfEachLoan);
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $currency.$differenceBtwRecieveAndPayBackInterest_FY01; ?></p>
					</span>
					<?php 
				}
				else if($eachFyear == 2)
				{
					$differenceBtwRecieveAndPayBackInterest_FY02 = (array_sum($FYear2Receive_IncurredInterest) - array_sum($FYear2PayBack_IncurredInterest));
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $currency.$differenceBtwRecieveAndPayBackInterest_FY02; ?></p>
					</span>
					<?php 
				}
				else if($eachFyear == 3)
				{
					$differenceBtwRecieveAndPayBackInterest_FY03 = (array_sum($FYear3Receive_IncurredInterest) - array_sum($FYear3PayBack_IncurredInterest));
					$differenceBtwRecieveAndPayBackInterest_FY03 = $differenceBtwRecieveAndPayBackInterest_FY03 + $differenceBtwRecieveAndPayBackInterest_FY02
					?>
					<span class="cell data column-1 singleline">
						  <p class="overflowable"><?php echo $currency.$differenceBtwRecieveAndPayBackInterest_FY03; ?></p>
					</span>
					<?php 
				}
			}
            	?>
            <div class="x-clear"></div>
        </div><!--end .singleline-->
    