<?php
	if(isset($_GET['edit_majorPurchaseID']))
	{
		(int)$purchaseMajorID = $_GET['edit_majorPurchaseID'];	
		$whereEdit = "major_purchases.mp_id != ".$purchaseMajorID;
		$allExpDetails = $expenditure->getAllMajorPurchaseDetails($whereEdit, "", "");
	}
	
	
	else if(isset($_GET['add']) and ($_GET['add'] == "new_expenditure"))
	{	
		(int)$purchaseMajorID = $expenditure->maxEmployeeId;
		
		//$whereEditLatest = "expenditure.exp_id != ".$purchaseMajorID;
		$whereEditLatest = "major_purchases.mp_id != ".$purchaseMajorID;
		
		$allExpDetails = $expenditure->getAllMajorPurchaseDetails($whereEditLatest, "", "");

		
	}
	else
	{
		$allMpDetails = $expenditure->getAllMajorPurchaseDetails("", "", "");
	}
	
	$currency = $expenditure->defaultCurrency;
?>
<div class="all_budget_">

	<?php 
	
	if(isset($allMpDetails))
	{
		foreach($allMpDetails as $majourPurchaseDetails)
		{
		?>						
			<div class="each_expenditure">
            
            
             <div class="edit_section">
                <div class="widget_content">
                    <h4><?php  echo $majourPurchaseDetails['mp_name']; ?></h4>
                    <div class="clearboth"></div>
                </div>
                <div class="click-to-edit" >
                  
                    <div class="tuck">
                        <a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&edit_new_majorPurchaseID=".$majourPurchaseDetails['mp_id']."&expensetype=majorpurchase&pgIndex=1"; ?>">
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
                                $twelveMonthsData = $expenditure->twelveMonths("", "");
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
								$num_of_years = count($expenditure->financialYear());
								$purchase_by_year = array();
								
								for ($i = 0; $i < $num_of_years; $i++) {
									$purchase_by_year[$i] = 0;
								}

								for($e_month = 0; $e_month < count($twelveMonthsData); $e_month++ )
                                { 
								
									$mp_date = $majourPurchaseDetails['mp_date'];
									$mp_month = substr($mp_date, 0, 3);
									
									$n_mp_month = date("m", strtotime($mp_month));
									
									
									// Use the key of the month to display the month from the selection through 12 months to the next year 
									$startYear = $expenditure->startFinancialYear;
									$startMonth = $expenditure->startMonth;
									$time = strtotime("+" . $e_month . " months", strtotime( $startYear . "-" . $startMonth . "-01"));
									$month_key = date('m', $time);
									//$name = date('M Y', $time);
									//$months[$key] = $name;
									
									$yrTwoFinancialYear = $startYear + 1;
									$yrThreeFinancialYear = $startYear + 2;
						 		
									$selectedYear = substr($mp_date,  4, 4);
									
									$eachMpMonth_price = array(); 	
									$eachMpMonth_price[$e_month] = 0;
									
									if($selectedYear > $startYear)
									{
										// This is either year 2 or 3
										if($mp_month == $startMonth)
										{
											$purchase_by_year[$selectedYear - $startYear] = $majourPurchaseDetails['mp_price'];
										}
										else //Year 1
										{
											if ($month_key == $n_mp_month)	
											{
												$eachMpMonth_price[$e_month] = $majourPurchaseDetails['mp_price'];
											}
										}
									}
									else // Year 1 too
									{
										if ($month_key == $n_mp_month)	
										{
											$eachMpMonth_price[$e_month] = $majourPurchaseDetails['mp_price'];
										}
									}
									?>
                                     <div class="column column-month">
                                        <span><?php  echo $currency; ?><?php  echo number_format($eachMpMonth_price[$e_month], 0, '.', ','); ?></span>
                                    </div>
									<?php
								}
								
								?>
                                
                             
                        </div>
                    </div><!--end .body-->
                </div>
            <div class="financial-table view">
                <div class="head">
                    <div class="row">
                    	<?php
                        	$yrsOfFinancialForecast = $expenditure->financialYear();
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
					  	for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
						{
							/*************************************	 TOSN PLS CONTINUE FROM HERE 	************************************
							
							// Year 2 or 3
							if($selectedYear > $startYear)
							{
								// This is either year 2 or 3
								if($mp_month == $startMonth)
								{
									
								}
								else //Year 1
								{
									if ($month_key == $n_mp_month)	
									{
										$eachMpMonth_price[$e_month] = $majourPurchaseDetails['mp_price'];
									}
								}
							}
							else // Definitly Year 1
							{
								if ($month_key == $n_mp_month)	
								{
									$eachMpYr_price[$e_yr] = $majourPurchaseDetails['mp_price'];
								}
							}
							*/
							
							
							?>
                                <div class="column column-year">
                                    <span><?php  echo $currency; ?><?php  echo number_format($purchase_by_year[$e_yr], 0, '.', ','); ?></span>
                                </div>
                        	<?php
						}
						?>
                        <div class="clearboth"></div>
                        </div>
                    </div>
                </div>
            </div>
          </div><!--end.each_expenditure-->
    <?php 
        }
	}// end of if
    ?>  
</div><!--end .all_budget_-->
