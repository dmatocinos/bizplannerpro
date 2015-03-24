<!------------------------------------------
          GROSS MARGIN SECTION
 ------------------------------------------>
 <div class="row  singleline">
  <span class="cell label column-0 singleline">
          <p class="overflowable">Gross Margin</p>
  </span>
    <?php
    $grossMarginCounter = 0;
	$grossMargin = array();
    foreach($arrayCostSummation as $sumOfAllCost)
    {
    	
    	$grossMargin[$grossMarginCounter] = (($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]));
    	$grossMargin_format[$grossMarginCounter] = number_format(($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]), 0, '.', ',');
    	
    	$valuedisplay = $grossMargin_format[$grossMarginCounter];
    	
    	if ($valuedisplay < 0 ) {
    		$valuedisplay = "(" . abs($valuedisplay) . ")";
    	}
    	
    	
        ?><span class="cell data column-1 singleline">
            
            <p class="overflowable"><?php echo $sales->defaultCurrency.$valuedisplay;  ?>  </p>	
        </span>
    <?php 
    $grossMarginCounter = $grossMarginCounter + 1;
    }
	
	$_SESSION['array_grossMargin'] = $grossMargin_format;
   ?>
	<div class="x-clear"></div>
</div><!--end .singleline-->


<!------------------------------------------
      GROSS MARGIN PERCENTAGE SECTION
 ------------------------------------------>
<div class="row row-group_footer singleline">
      <span class="cell label column-0 singleline">
              <p class="overflowable">Gross Margin %</p>
      </span>
      <?php
        $grossMarginPercentageCounter = 0;
        foreach($arrayCostSummation as $sumOfAllCost)
        {
            ?><span class="cell data column-1 singleline">
                <?php 
                // Avoid Division by zero in 
                if($totalSales[$grossMarginPercentageCounter] == 0)
                {
                    $grossMarginPercentage[$grossMarginPercentageCounter] = 0;
                }
                else
                {
                    $grossMarginPercentage[$grossMarginPercentageCounter] = (($grossMargin[$grossMarginPercentageCounter] * 100) /  $totalSales[$grossMarginPercentageCounter]);	
                }
                // Format $grossMarginPercentage
                $grossMarginPercentage[$grossMarginPercentageCounter] = number_format($grossMarginPercentage[$grossMarginPercentageCounter], 0, '.', ',');
                                
                $valuedisplay = $grossMarginPercentage[$grossMarginPercentageCounter];
                 
                if ($valuedisplay < 0 ) {
                	$valuedisplay = "(" . abs($valuedisplay) . "%)";
                }
                
                ?>
                <p class="overflowable"><?php echo $valuedisplay;  ?> </p>	
            </span>
        <?php 
        $grossMarginPercentageCounter = $grossMarginPercentageCounter + 1;
        }
       ?>
    <div class="x-clear"></div>
</div><!--end .singleline-->
