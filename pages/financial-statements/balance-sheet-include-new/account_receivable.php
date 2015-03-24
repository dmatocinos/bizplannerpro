<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
if ( ! isset($accounts_receivable_title)) {
	$accounts_receivable_title = 'Accounts Receivable';
}

$accountReceivable_allYears = $webcalc->balancesheetdata['balaccreceivable'];

?>   

	     <!------------------------------------------	
           Account Receivable
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
			  <p class="overflowable"><?php echo $accounts_receivable_title ?></p>
                  </span>
                <?php
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
				
				
				if(!isset($accountReceivable_allYears))
				{
					$accountReceivable_allYears = array();
					for($yrs = 0; $yrs < $_numberOfFinancialYrForcasting; $yrs++)
					{
					?>
                        <span class="cell data column-1 singleline">
                            <p class="overflowable"><?php echo $sales->defaultCurrency;?>0 </p>	
						</span>
                    <?php
					}
				}
				else
				{
					for($yrs = 0; $yrs < count($accountReceivable_allYears); $yrs++)
					{
						if(count($accountReceivable_allYears) > 0)
						{
							if($accountReceivable_allYears[$yrs] < 0)
							{
								$open_bracket  = OPEN_BRACKET;
								$closed_bracket  = CLOSED_BRACKET;
								$cancelNegative = -1;
							}
						}
						?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . number_format(($accountReceivable_allYears[$yrs] * $cancelNegative), 0, '.', ',') . $closed_bracket;?> </p>	
						</span>
					
					<?php 
					}
				}
               ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
