<!------------------------------------------------------------------
          GROSS MARGIN SECTION
 ------------------------------------------------------------------>
<?php
    $grossMarginCounter = 0;
	$grossMargin = array();
    foreach($arrayCostSummation as $sumOfAllCost)
    {
        $grossMargin[$grossMarginCounter] = (($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]));
        $grossMargin_format[$grossMarginCounter] = number_format(($totalSales[$grossMarginCounter] - $totalDirectCost[$grossMarginCounter]), 0, '.', ',');
   
    	$grossMarginCounter = $grossMarginCounter + 1;
    }
	
	// return $grossMargin
   ?>
	

<!------------------------------------------------------------------
      GROSS MARGIN PERCENTAGE SECTION
 ------------------------------------------------------------------>

  <?php
    $grossMarginPercentageCounter = 0;
    foreach($arrayCostSummation as $sumOfAllCost)
    {
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
        $grossMarginPercentageCounter = $grossMarginPercentageCounter + 1;
    }
   ?>
