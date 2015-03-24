<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
?>   
	     <!------------------------------------------	
          Accumulted Depreciation
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Accumulated Depreciation</p>
                  </span>
                <?php
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
				
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				$acuLAssets = 0;
				
				$p = .20;
				
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
						$acuLAssets += $long_term_assets[$e_year];						
						$acuDep	= $acuLAssets * $p;
						
						if(-$acuDep < 0)

						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . $acuDep . $closed_bracket;?> </p>	
						</span>
					<?php 
					
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
