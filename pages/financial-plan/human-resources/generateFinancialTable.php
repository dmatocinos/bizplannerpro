<?php
	$allEmpDetails = $employee->getAllEmployeeDetails2("", "", "");
	$currency = $employee->defaultCurrency;
?>

<div id="" class="padded">
	<?php 
		foreach($allEmpDetails as $employeeDetails)
		{
			//print_r($employeeDetails);
		?>						
			<h4><span><?php  echo $employeeDetails['emplye_name']; ?></span>
                <a href="<?php echo $employeeDetails['../new_employee/emplye_id']; ?>">Edit</a>
            </h4>
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
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_01']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_02']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_03']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_04']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_05']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_06']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_07']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_08']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_09']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_10']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_11']; ?></span>
                                </div>
                                <div class="column column-month">
                                    <span><?php  echo $currency; ?><?php  echo $employeeDetails['month_12']; ?></span>
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
                      <?php
					  	foreach($employeeDetails['financial_status'] as $e_financialStatus)
                        {
						?>
					    	<div class="column column-year">
                                <span><?php  echo $currency; ?><?php  echo $e_financialStatus['total_per_yr']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    <?php 
        }
    
    ?>  
</div><!--end padded-->