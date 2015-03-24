<?php  /**---	UPDATED JUNE 10 2013	---**/ ?>

         <!------------------------------------------	
        	Sales Taxes Payable
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Sales Taxes Payable</p>
                  </span>
                <?php
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
				
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					
					if(isset($totalCurrentAssets))
					{
						if($totalCurrentAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . 0 . $closed_bracket;?> </p>	
						</span>
				<?php 
					}
					else
					{?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $sales->defaultCurrency;?>0</p>	
						</span>
					<?php
                    }
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    