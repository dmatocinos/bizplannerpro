<?php
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
	
?>
<div class="all_budget_">

	<?php 
	
	if($allcashProjection)
	{
		$_arrayOfYrlyCalcInterest = array();
		$_arrayOfYrlyCalcInterestCounter = 0;
		//$_SESSION['sessionOfarrayOfYrlyCalcInterest'] = 0;
		
		foreach($allcashProjection as $cashProjectionDetails)
		{
			
			if ($cashProjectionDetails['type_of_funding']=="Investment") continue;
			
		?>						
			<div class="each_expenditure">
            
            
             <div class="edit_section">
                <div class="widget_content">
                    <h4 style="text-transform:uppercase; color:#16315F;"><?php  echo $cashProjectionDetails['loan_invest_name']; ?> &nbsp; <span style="text-transform:capitalize; font-size:13px;"><?php echo $cashProjectionDetails['type_of_funding'] ?> at <?php echo $cashProjectionDetails['loan_invest_interest_rate'] ?>% interest</span></h4>
                    <div class="clearboth"></div>
                    <span style="margin-left: 10px; font-size:12px; font-weight:bold;">Amount Received</span>
                </div>
                <div class="click-to-edit" >
                  
                    <div class="tuck">
                        <a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?projection=_loan_invest&edit_loanInvestID=".$cashProjectionDetails['li_id']; ?>">
                            <div class="flag">
                            <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                            </div>
                        </a>
                    </div>
                  
               </div>
                            
            </div><!--end .edit_section-->
            
            
            
            
			 		<div class="expense financial-table view">
						<div class="head">
         					<div class="row">
                            
                            <?php
                                $twelveMonthsData = $cashProjection->twelveMonths("", "");
                                for($e_month = 0; $e_month < count($twelveMonthsData); $e_month++ )
                                {
                                ?>
                                    <div class="column column-month">
                                        <span><?php echo $twelveMonthsData[$e_month]; ?></span>
                                    </div>
                               <?php
                                }
								?>
                                
                        </div>
                    </div><!--end .head-->
                    
                   
                   
                    <div class="body">
                        <div class="row values">
                            
                             <?php
							 	
								/*----------------------------------------------------------------------------------------
								
									Amount Received Section
								
								----------------------------------------------------------------------------------------*/
							 	$zeroPrefix = 0;
							 	$zeroPrefix = 0;
                               	$zeroCounter = 0;
                               	$oneToTwelveCounterLoan = 1;
							    for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
                                {
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
									
									?>	
									
									<div class="column column-month">
                                    	<span><?php  echo $currency; ?><?php  echo $amountReceiveWithCommas[$e_month]; ?></span>
                                	</div>
									<?php
								}
							?>
                            
                            
                            <!--
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_01'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_02'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_03'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_04'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_05'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_06'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_07'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_08'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_09'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_10'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_11'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($cashProjectionDetails['limr_month_12'], 0, '.', ','); ?></span>						
                                </div>
                           -->
                                    
                                    
                                    
                        </div>
                    </div><!--end .body-->
                </div>
            <div class="financial-table view">
                <div class="head">
                    <div class="row">
                    	<?php
                        	$yrsOfFinancialForecast = $cashProjection->financialYear();
							for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
							 {
							?>	
                                <div class="column column-year">
                                    <span>FY<?php echo $yrsOfFinancialForecast[$e_yr]; ?></span>
                                </div>
							 <?php } ?>   
                    
                    </div>
                </div>
                <div class="body">
                    <div class="row values">
                    	<div class="yr_row">	
                      <?php
					  	foreach($cashProjectionDetails['financial_receive'] as $e_financialStatus)
                        {
						?>
					    	<div class="column column-year">
                                <span><?php  echo $currency; ?><?php  echo number_format($e_financialStatus['lir_total_per_yr'], 0, '.', ','); ?></span>
                                							
                            </div>
                     	<?php 
						} ?>
                        <div class="clearboth"></div>
                        </div>
                    </div>
                </div>
            </div>
             <br/><br/>
            
           </div><!--end.each_expenditure-->
    <?php 
        }
		//print_r($_interestIncured);
		//$_SESSION['sessionOfarrayOfYrlyCalcInterest'] = $_interestIncured;
		
		
		
	}// end of if
    ?>  
    
</div><!--end .all_budget_-->