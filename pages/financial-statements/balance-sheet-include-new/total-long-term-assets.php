<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
	  
	//long term assets is calculated in long-term-assets.php
	  
?>   
	     <!------------------------------------------	
         Total Long Term Assets
         ------------------------------------------>
              <div class="row row-group_header singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Long-Term Assets</p>
                  </span>
                <?php
                
                $balTotalLongTermsAssets = $webcalc->profitlossdata['ns']['balTotalLongTermsAssets'];
                
				
				
				$acuLAssets = 0;
				$p = 0.20;	 	
				$long_longterm_assets = array();
				
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket  = "";
					$closed_bracket = "";
					$cancelNegative = 1;
					
						$acuLAssets += $long_term_assets[$e_year];
						$acuDep	= $acuLAssets * $p;
						
						$long_longterm_assets[$e_year] = $acuLAssets-$acuDep;
						
						if($balTotalLongTermsAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						$balTotalLongTermsAssets[$e_year] = $webcalc->number($balTotalLongTermsAssets[$e_year]);
						
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . ($balTotalLongTermsAssets[$e_year] * $cancelNegative). $closed_bracket;?> </p>	
						</span>
				<?php	
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
