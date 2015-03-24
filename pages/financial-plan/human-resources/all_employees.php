<?php
	
	if(isset($_GET['edit_employeeID']))
	{
		(int)$employeeID = $_GET['edit_employeeID'];	
		$whereEdit = "employee.emplye_id != ".$employeeID;
		$allEmpDetails = $employee->getAllEmployeeDetails2($whereEdit, "", "");
		
	}
	
	else if(isset($_GET['add']) and ($_GET['add'] == "new_employee"))
	{
		(int)$employeeID = $employee->maxEmployeeId;
		$whereEditLatest = "employee.emplye_id != ".$employeeID;
		$allEmpDetails = $employee->getAllEmployeeDetails2($whereEditLatest, "", "");
	}
	else
	{
		$allEmpDetails = $employee->getAllEmployeeDetails2("", "", "");
	}
	
	
	// TOSIN CHNAGE THIS LATER
	$currency = $employee->defaultCurrency;
	
?>
<style>
	#widgets-container{
		padding-top:0;
	}
	.widget-page-header h2 {padding-left:0;}
	
	#widgets-container .nav li{
		list-style: none;
		margin-left: 0px;
		
	}
</style>


<div class="all_employee_">
	<?php 
	
	if($allEmpDetails)
	{
		
		foreach($allEmpDetails as $employeeDetails)
		{
			
		?>		
        <div class="each_employee">
        			
             <div class="edit_section">
                <div class="widget_content">
                    <h4><?php  echo $employeeDetails['emplye_name']; ?></h4>
                    <div class="clearboth"></div>
                </div>
                <div class="click-to-edit" >
                  
                    <div class="tuck">
                        <a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?personnel=_employee&edit_employeeID=".$employeeDetails['emplye_id']; ?>">
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
                                $twelveMonthsData = $employee->twelveMonths("", "");
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
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_01'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_02'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_03'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_04'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_05'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_06'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_07'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_08'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_09'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_10'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_11'], 0, '.', ','); ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo number_format($employeeDetails['month_12'], 0, '.', ','); ?></span>
                                </div>
                        </div>
                    </div><!--end .body-->
                </div>
            <div class="financial-table view">
                <div class="head">
                    <div class="row">
                    	<?php
                        	$yrsOfFinancialForecast = $employee->financialYear();
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
					  	foreach($employeeDetails['financial_status'] as $e_financialStatus)
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
</div><!--end .all_employee_-->