<?php
  /**---	UPDATED JUNE 10 2013	---**/
?>
<?php
		
		$arrayCostSummation = array();
		if($allSalesDetails)
		{
			foreach($allSalesDetails as $expDetails)
			{
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					 $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
				}
				$counter = $counter+1; 
			}// end foreach 
			?> 
            <!----------------------------------------------------------------------------
               TOTAL DIRECT COST SECTION
            ---------------------------------------------------------------------------->
            <?php
            $totalCostCounter = 0;
            foreach($arrayCostSummation as $sumOfAllCost)
            {
                $totalDirectCost[$totalCostCounter] = (array_sum($sumOfAllCost));
                $totalDirectCost_format[$totalCostCounter] = number_format(array_sum($sumOfAllCost), 0, '.', ',');
                $totalCostCounter = $totalCostCounter + 1;	
            }
            
    
            foreach($allSalesDetails as $expDetails)
            {
                for($i=0; $i< count($expDetails['financial_status']); $i++)
                {
                     $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
                }
                $counter = $counter+1; 
            }// end foreach 
		}
	?>    	