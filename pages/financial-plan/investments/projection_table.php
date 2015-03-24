
        
<div id="personal_table">
     <div class="edit_section">
        <div class="widget_content">
            <h3><?php echo $pageTitle; ?> Table</h3>
            <div class="clearboth"></div>
        </div>
        <div class="click-to-edit" >
          
            <div class="tuck">
                <a href="<?php echo $_SERVER['PHP_SELF'].'?projection=_loan_invest'?>">
                    <div class="flag">
                    <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                    </div>
                </a>
            </div>
          
       </div>
    </div><!--end .edit_section-->
    
    
     
<div class="preview-table-wrapper-inner" >
	<div id="widgetForm:j_id278:j_id287:j_id289:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns funding">
    
    
               
        <div class="row row-header singleline">
             <span class="cell label column-0 singleline">
                  <p class="overflowable"> </p>
            </span>
            
           
           <?php // loop through and pick out the years
           
			
			 $yrsOfFinancialForecast = $_loanInvestment->financialYear();
			for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
			 {
			?>	
				   <span class="cell data column-1 singleline">
					<p class="overflowable">FY<?php echo $yrsOfFinancialForecast[$e_yr]; ?></p>
				</span>
		 <?php } ?>   

           
            <div class="x-clear"></div>
        </div><!--end .singleline-->
                            
                        
        <?php
        $counter = 0;
        $arraySummation = "";
        // Related Expenses calculation
       // (int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
       // $personalRelatedExpenseInPercentage = ($personalRelatedExpenses / 100);
        
        
        /*---------------------------------------------------
            _loanInvestment  Calculation loop	
        /*-----------------------------------------------*/
        foreach($allloanInvestmentProjection as $expDetails)
        {
        	if ($expDetails['type_of_funding']=="Investment") {
        	?>
            <div class="row row-item multiline"> 
                 <span class="cell label column-0 multiline">
                  <p class="overflowable bold"><?php echo $expDetails['loan_invest_name'];?></p>
                  <p class="overflowable description"><?php echo $expDetails['type_of_funding'];?> at <?php echo $expDetails['loan_invest_interest_rate'];?>% interest</p>
                </span>
                <?php 
                foreach($expDetails['financial_receive'] as $finDetails)
                {?>
                    <span class="cell data column-1 singleline">
                          <p class="overflowable"><?php echo $_loanInvestment->defaultCurrency.number_format($finDetails['lir_total_per_yr'], 0, '.', ','); ?></p>
                    </span>
                    
                <?php 
                }
                ?>
                <div class="x-clear"></div>
            </div><!--end .multiline-->		 
            
            	<?php 
                
                for($i=0; $i< count($expDetails['financial_receive']); $i++)
                {
                     $arraySummation[$i][$counter]  = $expDetails['financial_receive'][$i]['lir_total_per_yr'];
                }
                $counter = $counter+1;
                
            } //end test if type of funding is investment
                 
		  }// end foreach ?>
        
       
            <!------------------------------------------
                Total Expsenses
             ------------------------------------------>
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Amount Received</p>
                  </span>
                
                <?php
                $count = 0;
                if (is_array($arraySummation)) {
					foreach($arraySummation as $total)
					{?><span class="cell data column-1 singleline">
						<p class="overflowable">
						<?php echo $_loanInvestment->defaultCurrency . number_format(array_sum($total), 0, '.', ',')  ?>	
						</p>
					</span>
				   <?php   
					$count++;
				   
					}
                }

                
				if (!is_array($arraySummation)) {
					for($i=$count;$i<count($yrsOfFinancialForecast);$i++)
					{
						echo '<span class="cell data column-1 singleline"><p class="overflowable">&nbsp;</p></span>';
					}
				}
				
				?>
				<div class="x-clear"></div>
            </div><!--end .singleline-->
        </div><!--end of .widgetForm-->
    <div class="x-clear"></div>
 </div><!--end #personal_table-->
 <p>&nbsp;</p><p>&nbsp;</p>