<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
?>  
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
<?php        

		if(isset($totalCurrentAssets))
		{
        	$totalAssets = $totalCurrentAssets;
		}
		else
		{
			$totalAssets = array();
		}
		?>
         <!------------------------------------------	
         Total Assets
         ------------------------------------------>
              <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Assets</p>
                  </span>
                <?php
                $balTotalAssets 		= $webcalc->profitlossdata['ns']['balTotalAssets'];
				
				
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					$open_bracket  = "";
					$closed_bracket = "";
					$cancelNegative = 1;
					
					
					if(count($balTotalAssets) > 0)
					{
						$totalAssets[$e_year] += $long_longterm_assets[$e_year];
						
						if($balTotalAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						$balTotalAssets[$e_year] = number_format($balTotalAssets[$e_year] * $cancelNegative, 0, '.', ',');
						
						
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . $balTotalAssets[$e_year] . $closed_bracket;?> </p>	
						</span>
					<?php 
					}
					else
					{?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $sales->defaultCurrency ;?>0 </p>	
						</span>
					<?php 
					}
					
				
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
