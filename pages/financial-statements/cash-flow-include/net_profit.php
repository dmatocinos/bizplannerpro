<?php include_once('cash-flow-include/net_profit_calc.php'); ?>
    
<div class="row row-item singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Net Profit</p>
              </span>
		<?php for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ): ?>
		<span class="cell data column-1 singleline">
			  <p class="overflowable"><?php echo $array_netProfitFormat[$e_yr]; ?></p>
		</span>
		<?php endfor ?>
     <div class="x-clear"></div>
        </div><!--end .singleline-->

   
