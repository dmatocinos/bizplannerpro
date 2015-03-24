<?php
  /**---	UPDATED JUNE 10 2013	---**/
?>
<?php
	$sales = new sales_forecast_lib();
	$allSalesDetails = $sales->getAllSales("", "", "");
	
	$arraySalesSummation = array();
	$counter = 0;
	
	if($allSalesDetails > 0)
	{
		foreach($allSalesDetails as $expDetails)
		{ 
				$totaSaleCounter = 0;
				
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					 $arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
				}
				$counter = $counter+1;
		}// end foreach  
		$revenue = $arraySalesSummation;
	
	}
	?>
      	<!----------------------------------------------------------------------------
           REVENUE
        ---------------------------------------------------------------------------->
        <?php
        $totalSalesCounter = 0;
		
		
        foreach($arraySalesSummation as $sumOfAllSales)
        {
            $totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));
            $totalSales_format[$totalSalesCounter] = number_format(array_sum($sumOfAllSales), 0, '.', ',');
            $totalSalesCounter = $totalSalesCounter + 1;
        }
        ?>
        
       
             
       
		  
		