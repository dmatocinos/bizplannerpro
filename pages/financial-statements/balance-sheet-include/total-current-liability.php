<?php  /**---	UPDATED JUNE 11 2013	---**/ ?>    
         <!------------------------------------------	
        	Total Current Liabilities
         ------------------------------------------>
              <div class="row row-group_header singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Current Liabilities</p>
                  </span>
                <?php
               
				
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					if(isset($Total_accountPayable_allYears_bdgt))
					{
						 
						$open_bracket  = "";
						$closed_bracket = "";
						$cancelNegative = 1;
						
						
						if($Total_accountPayable_allYears_bdgt[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket . $sales->defaultCurrency . number_format(($Total_accountPayable_allYears_bdgt[$e_year] * $cancelNegative), 0, '.', ',') . $closed_bracket;?> </p>		
						</span>
					<?php 
					}
					else
					{
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $sales->defaultCurrency;?>0 </p>	
						</span>
					<?php 
					}
				} // End of loop
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
