<?php
	if(isset($_GET['edit_expenditureID']))
	{
		(int)$expenditureID = $_GET['edit_expenditureID'];	
		$whereEdit = "expenditure.exp_id != ".$expenditureID;
		$allExpDetails = $expenditure->getAllExpenditureDetails($whereEdit, "", "");
	}
	
	
	else if(isset($_GET['add']) and ($_GET['add'] == "new_expenditure"))
	{
		(int)$expenditureID = $expenditure->maxEmployeeId;
		$whereEditLatest = "expenditure.exp_id != ".$expenditureID;
		$allExpDetails = $expenditure->getAllExpenditureDetails($whereEditLatest, "", "");
	}
	else
	{
		$allExpDetails = $expenditure->getAllExpenditureDetails("", "", "");
	}
	
	$currency = $expenditure->defaultCurrency;
	
?>
<div class="all_budget_">

	<?php 
	
	if($allExpDetails)
	{
		foreach($allExpDetails as $expenditureDetails)
		{
		?>						
			<div class="each_expenditure">
            
            
             <div class="edit_section">
                <div class="widget_content">
                    <h4><?php  echo $expenditureDetails['expenditure_name']; ?></h4>
                    <div class="clearboth"></div>
                </div>
                <div class="click-to-edit" >
                  
                    <div class="tuck">
                        <a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&edit_expenditureID=".$expenditureDetails['exp_id']; ?>">
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
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_01'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_02'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_03'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_04'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_05'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_06'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_07'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_08'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_09'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_10'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_11'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($expenditureDetails['month_12'], 0, '.', ','); ?></span>
                                </div>
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
					  	foreach($expenditureDetails['financial_status'] as $e_financialStatus)
                        {
						?>
					    	<div class="column column-year">
                                <span><?php  echo $currency; ?><?php  echo number_format($e_financialStatus['total_per_yr'], 0, '.', ','); ?></span>
                                							
                            </div>
                        <?php } ?>
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