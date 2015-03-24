	     <!------------------------------------------	
           Account Receivable
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Inventory</p>
                  </span>
                <?php
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
				
				for($yrs = 0; $yrs < count($accountReceivable_allYears); $yrs++)
				{
					if($accountReceivable_allYears[$yrs] < 0)
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
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    