<div class="widget_content">
    <h3>Expenses by Month</h3>
    <div class="clearboth"></div>
</div><br/>
  <?php
							 
	// $eachMonth_ // An array that holds the data in order to sum them together
	
	$each_month_counter = 0;
	
	//use to get 12months from Jan
	$list12Months2 = $employee->twelveMonths(date('Y'), "Jan");
	
	
	/*--------------------------------------------------------------------------
	 	Employees Salary loop
	 ---------------------------------------------------------------------------*/
	foreach($allEmpDetails as $monthlySalary)
	{
		for($c = 0; $c<12; $c++)
		{
			// $monthsInNumbers i.e 01 ... 12
			$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
			
			$eachMonth_[$c][$each_month_counter]  = $monthlySalary["month_".$monthsInNumbers];
		}
		
		$each_month_counter = $each_month_counter+1;
	
	}
	
	/*--------------------------------------------------------------------------
	 	Repeat Employees Salary loop but multiply it with $monthlyReleatedExp 
	 ---------------------------------------------------------------------------*/
	if(isset($personalRelatedExpenses))
	{
		$monthlyReleatedExp = ($personalRelatedExpenses / 100);
	}
	else
	{
		$monthlyReleatedExp = 0;
	}
	
	foreach($allEmpDetails as $monthlySalary)
	{
		for($c = 0; $c<12; $c++)
		{
			$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
			
			// This is where the multiplication take place for each salary month data
			$eachMonth_[$c][$each_month_counter]  = ($monthlySalary["month_".$monthsInNumbers] * $monthlyReleatedExp);
		}
		
		$each_month_counter = $each_month_counter+1;
	}

	/*--------------------------------------------------------------------------
	 	Expenditure loop
	 ---------------------------------------------------------------------------*/
	foreach($allExpDetails as $monthlyExpenses)
	{
		for($b = 0; $b<12; $b++)
		{
			// $monthsInNumbers i.e 01 ... 12
			$monthsInNumbers = date("m", strtotime($list12Months2[$b]));	
			//echo $b."<br/>";
			 $eachMonth_[$b][$each_month_counter]  = $monthlyExpenses["month_".$monthsInNumbers];
		}
		
		$each_month_counter = $each_month_counter+1;
	}
	
	/*--------------------------------------------------------------------------
	 	Get start month and loop to get every other 12 months
	 ---------------------------------------------------------------------------*/
	$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
	$list12Months = $employee->twelveMonths($start_years, $start_month);
	$monthCounter = 0;
	foreach($list12Months as $monthList)
	{
		$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
		$monthly_axis[$allExpensesMonths[$monthCounter]] = array_sum($eachMonth_[$monthCounter]);
		
		//$oWebcalc is instantiated in budget.php
		$monthly_axis[$allExpensesMonths[$monthCounter]] = $oWebcalc->monthlytotaloperatingexpenses[$monthCounter+1];
		
		
		$monthCounter = $monthCounter+1;
	}
	//print_r($monthly_axis);
	
	
	// Create Monthly Graph 
	//$draw = new graph_lib(); 
	$graphImageName = "monthly_expenses_graph" . $_SESSION['bpId'] . ".png";
	$bar_width = 20;
	$xAxisFont = 2;
	$xAxisPostion = 3;
	$unit = html_entity_decode($employee->currencySetting);
	$unitPosition = "before";
	//$drawGraph = $draw->_graph($monthly_axis, $graphImageName, $bar_width, $xAxisFont, $xAxisPostion, $unitPosition, $unit);
	//echo " <img src='".BASE_URL."/".$graphImageName."' />" ;

	
	$imgb64 = GraphHandler::getImgB64($monthly_axis,  $unit, $graphImageName, 600); //	getImgB64($data, $valueformat)
	//GraphHandler::createGraphImg($data,  $unit, $imgname);
	echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageName . '" />';
	
	//$imgb64 = GraphHandler::getImgB64($monthly_axis,  $unit); //	getImgB64($data, $valueformat)
	//echo '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.$imgb64.'" />';

	
?>
