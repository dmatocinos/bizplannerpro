<?php  /**---	UPDATED JUNE 11 2013	---**/ ?>  
<?php
      $_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
?>
<?php if ( ! $hide_spacer): ?>
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
<?php endif ?>
                    
          <!------------------------------------------	
        	Long Term Debt
         ------------------------------------------>
             <?php
              	$loanTakenMinusPaymentMade;
				$array_interestIncured;
				$total_long_term_assets = array();
					
					?>
              
              
              
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Long-Term Debt</p>
                  </span>
                <?php
                
                $balLongTermDebt 	= $webcalc->profitlossdata['ns']['balLongTermDebt'];
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
				$operatingIncome = array();
				if(!isset($allExpense)){$allExpense = array();}
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					if((count($grossMargin) > 0) and (count($allExpense)))
					{
						$operatingIncome[$e_year] = ($grossMargin[$e_year] - $allExpense[$e_year]);
						$total_long_term_assets[$e_year] = (($loanTakenMinusPaymentMade[$e_year] + $array_interestIncured[$e_year]) - $operatingIncome[$e_year]);
						$cancelNegative = 1;
						$open_bracket  	= "";
						$closed_bracket = "";
						
						$total_long_term_assets[$e_year] *= -1;
						
						if($balLongTermDebt[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket = CLOSED_BRACKET;
							$cancelNegative = -1;
						}?>
						<span class="cell data column-1 singleline">
							<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . number_format(($balLongTermDebt[$e_year] * $cancelNegative), 0, '.', ',')  . $closed_bracket;?></p>	
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
    
