<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
?>   
         <!------------------------------------------	
          Cash In hand
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Cash</p>
                  </span>
                <?php
                $newCashInHand = array_values($webcalc->balancesheetdata['balcash']);
                
                for($eachY = 0; $eachY < count($newCashInHand); $eachY++ )
                {
                    if($newCashInHand[$eachY] < 0)
					{
						$open_bracket  = OPEN_BRACKET;
						$closed_bracket  = CLOSED_BRACKET;
						$cancelNegative = -1;
					}
					else
					{
						$open_bracket  = "";
						$closed_bracket  = "";
						$cancelNegative = 1;
					}
					?><span class="cell data column-1 singleline">
                        <p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency; ?><?php echo number_format(($newCashInHand[$eachY] * $cancelNegative), 0, '.', ',') ;?><?php echo $closed_bracket;?> </p>	
                    </span>
                <?php 
                $totalCostCounter = $totalCostCounter + 1;	
                }
				
				
				
				if(count($newCashInHand)<=0)
				{
					for($eachYnoValue = 0; $eachYnoValue < $_numberOfFinancialYrForcasting; $eachYnoValue++ )
					{	
					?>
                        <span class="cell data column-1 singleline">
                            <p class="overflowable"><?php echo $sales->defaultCurrency; ?>0 </p>	
                        </span>
				<?php 
					}
				}
				
				
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
     