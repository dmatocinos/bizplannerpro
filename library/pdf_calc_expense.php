<?php 




$monthrows 	= $this->profitlossdata['monthlyrevenuerows'];		// monthlyrevenue rows - includes label
$months 	= $this->salesdata['months'];						// mos eg. Jan - Dec 
$years 		= $this->salesdata['years'];						// sales years
$monthrows 	= $this->profitlossdata['monthlydirectcostrows'];	// monthly direct cost rows - includes label
$monthrows 	= $this->salesdata['monthlyGrossMargin'];			// monthly gross margin rows - includes label	
$monthrows 	= $this->salesdata['monthlyGrossMPercentage'];		// monthly gross margin % - no label


$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];			//monthlytotalsalary no label
$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];	//monhtlytotalrelated salaray no label

$monthlyotherexpenses 		= $this->expensesdata['monthlyotherexpenses'];				//array of other expenses each row includes label

//echo highlight_string(var_export($monthlytotalexpenses, TRUE));
/* example loop of monthly other expenses
foreach($monthlyotherexpenses as $key=>$value) {
	$thtml->addLTDRow(array_merge(array($key),$this->farraynumber($value)), null);
}
*/


$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];		//monthly total operating expenses no label

$monthlyrows = $this->profitlossdata['monthlyinterestincurredrows'];			//monthly interestincurred rows include labels
$monthlyrows = array_merge(array('Interest Incurred'), $this->farraynumber($monthlyrows));

//add depreciation
$monthlyvals = $this->profitlossdata['monthly_accudepreciation'];


$monthlygrossmargin 		= $this->salesdata['monthlyGrossMargin'];
$montylyinterestincurred 	= $this->profitlossdata['monthlyinterestincurredrows'];
$monthlyrows = array();
$tmprows	 = array();

$expenditure    = new expenditure_lib();
$incomeTaxRate  =  $expenditure->incomeTaxRate;

$tmptotalexpense = array();

$depreciation = $this->profitlossdata['monthly_accudepreciation'];

//echo "tax rate: " . $incomeTaxRate;

for($i = 0; $i < 12; $i++)
{
	$totalexpense 	= $monthlytotalexpenses[$i];
	$grossmargin 	= $monthlygrossmargin[$i];
	$interestincur	= $montylyinterestincurred[$i];
		
	$monthlyrows[$i] = ($grossmargin - $totalexpense);		
	$tmprows[$i] = (($monthlyrows[$i] * $incomeTaxRate) / 100); //operating income * taxrate/100
		
	
	if($monthlyrows[$i] < 0) {
	$monthlyrows[$i] = 0;
	}
	
	$tmptotalexpense[$i] = $interestincur + $tmprows[$i] + $depreciation[$i] + $monthlytotalexpenses[$i]; //income tax + interest incurred + depreciation
	
}

/*
echo '<br>$monthlytotalexpenses: ';
echo highlight_string(var_export($monthlytotalexpenses, TRUE));
echo '<br>$monthlygrossmargin: ';
echo highlight_string(var_export($monthlygrossmargin, TRUE));
echo highlight_string(var_export($monthlytotalexpenses, TRUE));
echo '<br>$montylyinterestincurred: ';
echo highlight_string(var_export($montylyinterestincurred, TRUE));
echo '<br>$tmprows: ';
echo highlight_string(var_export($tmprows, TRUE));

*/

	$this->profitlossdata['monthlyincometax'] = $tmprows;


		//$this->profitlossdata['ns']['monthlyincometax'] = $tmprows; //incometax no label



//calculate net profit

			
		$monthlydirectcost	= array_slice($this->profitlossdata['monthlydirectcostrows'], 1);

		$revenue = $this->profitlossdata['monthlyrevenuerows'];

		$tmprows	 = array();
		$monthlyrows = array();


		$pltotalexpense = array();

		for($i = 0; $i < 12; $i++)
		{
		$expense 			= $monthlydirectcost[$i] + $monthlytotalexpenses[$i] + $monthlyincometaxes[$i];
		$pltotalexpense[]	= $expense;
		$monthlyrows[$i] 	= $revenue[$i+1] - $expense;
		$tmprows[$i] 		= $revenue[$i+1] > 0 ? ($monthlyrows[$i] / $revenue[$i+1] * 100) : 0 ;
		}

		$this->profitlossdata['ns']['MonthlyTotalExpenses'] 	= $tmptotalexpense; //incometax no label, $this->profitlossdata['ns'] is a member variable
		
		$this->profitlossdata['monthlynetprofit'] = $monthlyrows;
		//highlight_string(var_export($revenue,true));
		//highlight_string(var_export($monthlytotalexpenses,true));
		//highlight_string(var_export($monthlyincometaxes,true));

		$this->expensesdata['balYearlyTotalExpenses'] = $pltotalexpense;


		//highlight_string(var_export($this->expensesdata['balYearlyTotalExpenses'],true));

// YEARLY DATA		
		
		
		$this->salesdata['netprofit'] = $monthlyrows;
		
		$years 		= $this->salesdata['years'];

		$tmprows 	= $this->profitlossdata['yearlyrevenuerows'];		//rows includes label		

		$tmprows 	= $this->profitlossdata['yearlydirectcostrows'];	//rows include lable

		$tmprows 	= $this->salesdata['grossMarginRaw'];				//no label
	
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];

		$yearlyOperatingTotalExpenses	= $this->expensesdata['yearlyTotalExpenses']; // no label	

		$tmprows = $this->profitlossdata['yearlyoperatingincomerows'];

		$TotalExpenses = array();

		$this->profitlossdata['yearlyinterestincurredrows'][1] = $this->number(array_sum($montylyinterestincurred));

		for($i = 1; $i < 4; $i++ ) {
					//direct cost
			$dcost 		= str_replace(array($this->salesdata['currency'],','), '', $this->profitlossdata['yearlydirectcostrows'][$i]);
			$dcost 		= floatval($dcost);
			$iincur 	= str_replace(array($this->salesdata['currency'],',','(',')'), '', $this->profitlossdata['yearlyinterestincurredrows'][$i]);
			$iincur 	= floatval($iincur);
			$dep		= $this->profitlossdata['yearlydepreciation'][$i-1];
			$itax 		= str_replace(array($this->salesdata['currency'],','), '', $this->profitlossdata['yearlyincometaxrows'][$i]);
			$itax		= floatval($itax);
														
			$totaloperatingexpense 	= $yearlyOperatingTotalExpenses[$i-1];
			$TotalExpenses[] 		= $dcost + $iincur + $dep + $itax + $totaloperatingexpense;
		}


	//echo highlight_string(var_export($TotalExpenses, TRUE));
	//echo highlight_string(var_export($this->profitlossdata['yearlyinterestincurredrows'], TRUE));
	//echo highlight_string(var_export($this->profitlossdata['yearlydirectcostrows'], TRUE));
	//echo highlight_string(var_export($this->profitlossdata['yearlydepreciation'], TRUE));
	//echo highlight_string(var_export($this->profitlossdata['yearlyincometaxrows'], TRUE));

	$this->expensesdata['balYearlyTotalExpenses'] = $TotalExpenses;

	
