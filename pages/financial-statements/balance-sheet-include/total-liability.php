<?php  /**---	UPDATED JUNE 11 2013	---**/ ?>  
<?php
      $_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
?>
        <div class="row row-spacer singleline">
		<?php // display empty line 
            for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
            {
			?>
                  <span class="cell label column-0 singleline">
                          <p class="overflowable"> </p>
                  </span>
            <?php
            }
			?>            
            <div class="x-clear"></div>
        </div>
                    
          <!------------------------------------------	
        	Total Liabilities
         ------------------------------------------>
              <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Liabilities</p>
                  </span>
                <?php
                
				
				
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					if(isset($totalCurrentAssets))
					{
					
						$tmptotal = $total_long_term_assets[$e_year]+$Total_accountPayable_allYears_bdgt[$e_year];
					
						$open_bracket  = "";
						$closed_bracket = "";
						$cancelNegative = 1;	
					
					
						if($tmptotal < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						?>
						<span class="cell data column-1 singleline">							
						<p class="overflowable">
							<?php echo $open_bracket . $sales->defaultCurrency . number_format($tmptotal*$cancelNegative, 0, '.', ',') . $closed_bracket;?>
						</p>
						</span>
					<?php 
					}
					else
					{?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $sales->defaultCurrency;?>0 </p>	
						</span>
					<?php 
					}
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
