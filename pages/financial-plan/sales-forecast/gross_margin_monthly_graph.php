 <div class="widget_content">
    <h3>Gross Margin(%) by Month</h3>
    <div class="clearboth"></div>
</div><br/>
 <?php
 		
		
		$each_month_counter = 0;
		//use to get 12 months from Jan
		$list12Months2 = $sales->twelveMonths(date('Y'), "Jan");
		
		$totalmonthlygrossmargin = array();
		$monthlysales			 = array();
		$totalmonthlycost		 = array();
		
	 	foreach($allSalesDetails as $monthlySalary)
		{ 
			
			for($c = 0; $c<12; $c++)
			{
				
				// Calculate Monthly Gross Margin Percentage  
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
				
				$monthlySalesPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['price']);
				
				$monthlysales[$c] += $monthlySalesPerUnit;
				
				$monthlyCostPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['cost']);
				
				$totalmonthlycost[$c] += $monthlyCostPerUnit;
				
				$monthlyGrossMargin = ($monthlySalesPerUnit - $monthlyCostPerUnit);
				
				$totalmonthlygrossmargin[$c] += $monthlyGrossMargin;				
				
				if($monthlySalesPerUnit == 0)
				{
					$monthlyGrossMarginPercent = 0;	
				}
				else
				{
					$monthlyGrossMarginPercent = (($monthlyGrossMargin * 100) / $monthlySalesPerUnit);
				}
				
				$monthlyGrossMarginPercent = number_format($monthlyGrossMarginPercent, 0, '.', ','); 
				
				$eachGrossMarginMonth_[$c][$each_month_counter]  = $monthlyGrossMarginPercent;
			}
			
			$each_month_counter = $each_month_counter+1;
		}
		
	
		for($c = 0; $c<12; $c++)
		{
			//$monthlysales = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['price']);
			//$totalmonthlygrossmargin[$c] = $monthlysales[$c] - $totalmonthlycost[$c];
			//$totalmonthlygrossmargin[$c] = number_format($totalmonthlygrossmargin[$c]/$monthlysales[$c], 0, '.', ',');
			//$totalmonthlygrossmargin[$c] = $totalmonthlygrossmargin[$c];///$monthlysales[$c]*100;
			$totalmonthlygrossmargin[$c] = $totalmonthlygrossmargin[$c]/$monthlysales[$c]*100;
			$totalmonthlygrossmargin[$c] = number_format($totalmonthlygrossmargin[$c], 0, '.', ',');
			
			
		}
		
		//highlight_string(var_export($totalmonthlygrossmargin,true));
		
	
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
		$monthly_axis[$allExpensesMonths[$monthCounter]] = array_sum($eachGrossMarginMonth_[$monthCounter]);
		$monthly_axis[$allExpensesMonths[$monthCounter]] = (int) $totalmonthlygrossmargin[$monthCounter];
		$monthCounter = $monthCounter+1;
	}
	
	//print_r($monthly_axis);	
	// Create Monthly Graph For Gros MArgin Percentage
	//$draw = new graph_lib(); 
	$graphImageName = "monthly_graph_gross_margin" . $_SESSION['bpId'] . ".png";
	$bar_width = 20;
	$xAxisFont = 2;
	$xAxisPostion = 3;
	$unitPosition = "after";
	$unit = "%";
	//$drawGraph = $draw->_graph($monthly_axis, $graphImageName, $bar_width, $xAxisFont, $xAxisPostion, $unitPosition, $unit);
	//echo " <img src='".BASE_URL."/".$graphImageName."' />" ;
	

	$imgb64 = GraphHandler::getImgB64($monthly_axis,  $unit, $graphImageName, 600); //	getImgB64($data, $valueformat)
	//GraphHandler::createGraphImg($data,  $unit, $imgname);
	echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageName . '" />';

	
	echo '<p style="text-align:center">Months in Year 1</p>';
 
 ?>
 <?php


?>
