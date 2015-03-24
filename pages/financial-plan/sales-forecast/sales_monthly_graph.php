 <div class="widget_content">
    <h3>Sales by Month</h3>
    <div class="clearboth"></div>
</div><br/>
 <?php
 		
		
		$each_month_counter = 0;
		//use to get 12 months from Jan
		$list12Months2 = $sales->twelveMonths(date('Y'), "Jan");
		
		$monthlysales = array();
	 	foreach($allSalesDetails as $monthlySalary)
		{ 
			
			for($c = 0; $c<12; $c++)
			{
				
				// Calculate Monthly Gross Margin Percentage  
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
				
				$monthlySalesPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['price']);								

				
				//$monthlySalesPerUnit = number_format($monthlySalesPerUnit, 0, '.', ',');
					
				//$eachSaleMonth_[$c][$each_month_counter]  = $monthlySalesPerUnit;
				
				$monthlysales[$c]  += $monthlySalesPerUnit;
				
			}
						
			$each_month_counter = $each_month_counter+1;
			
		}
			
		for($c = 0; $c<12; $c++)
		{
			
			//$monthlysales[$c] = number_format($monthlysales[$c], 0, '.', ',');
			
		
		}
	
	
		
	/*--------------------------------------------------------------------------
	 	Get start month and loop to get every other 12 months
	 ---------------------------------------------------------------------------*/
	$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$list12Months = $sales->twelveMonths($start_years, $start_month);
	$monthCounter = 0;
	foreach($list12Months as $monthList)
	{
		$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
		$sales_monthly_axis[$allExpensesMonths[$monthCounter]] = array_sum($eachSaleMonth_[$monthCounter]);
		$sales_monthly_axis[$allExpensesMonths[$monthCounter]] = $monthlysales[$monthCounter];
		$monthCounter = $monthCounter+1;
	}
	
	//print_r($monthly_axis);	
	// Create Monthly Graph For Gros MArgin Percentage
	//$draw = new graph_lib(); 
	$graphImageNameSales = "monthly_graph_sales" . $_SESSION['bpId'] . ".png";
	$bar_width = 20;
	$xAxisFont = 2;
	$xAxisPostion = 3;
	$unitPosition = "before";
	$unit = html_entity_decode($sales->currencySetting);

	
	

	//$imgb64 = GraphHandler::getImgB64($sales_monthly_axis,  $unit);	
	//echo '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.$imgb64.'" />';

	$imgb64 = GraphHandler::getImgB64($sales_monthly_axis,  $unit, $graphImageNameSales, 600); //	getImgB64($data, $valueformat)
	//GraphHandler::createGraphImg($data,  $unit, $imgname);
	echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageNameSales . '" />';
	
	
	//$drawGraph = $draw->_graph($sales_monthly_axis, $graphImageNameSales, $bar_width, $xAxisFont, $xAxisPostion, $unitPosition, $unit);
	//echo " <img src='".BASE_URL."/".$graphImageNameSales."' />" ;
	
	echo '<p style="text-align:center">Months in Year 1</p>';
 
 ?>
