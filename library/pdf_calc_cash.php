<?php 
		//monthly cash in hand
		$monthlytotalsales			= $this->salesdata['monthlyTotalSales'];
		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		$monthlygrossmargin 		= $this->salesdata['monthlyGrossMargin'];
		$monthylyinterestincurred 	= $this->profitlossdata['monthlyinterestincurredrows'];
		$monthlyreceive 			= $this->loansdata['$monthlyreceive'];
		$monthlypayment				= $this->loansdata['$monthlypayment'];
		$monthlycash				= array();
		$tmploans					= $this->loansdata['monthly']['loansrows'];
		$tmploans					= array_slice($tmploans[0], 1);
		
		$monthlypurchase 			= $this->profitlossdata['monthly_detail_purchases'];
		
		
		$cashsettinglib = new cashFlowProjection_lib();
		
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			$cashsetting 	= $cashsettinglib->Payments($businessPlanId);
			if ($cashsetting.length) {
				$cashsetting = $cashsetting[0];
			}
		}
		
		//echo highlight_string(var_export($cashsetting, TRUE));
		
		
		$percentoncredit 	= $cashsetting['percentage_sale']/100;
		$daystocollect		= $cashsetting['days_collect_payments'];
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		$collectpercent = array();
		$collectdays	= array();
		
		for($i = 0; $i < 20; $i++) {
			$collectpercent[] 	= ($i * 5)/100;
			$collectdays[]		= ($i * 30);
		}
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsReceivable 		= array();
		$MonthlyAccountsReceivable[-1] 	= 0;
		$TotalAccountsReceivable		= array();
		$TotalAccountsReceivable[-1]	= 0;
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $monthlytotalsales[$j];
			} else {
				$tmprow[$j] = $monthlytotalsales[$j]*(1-$percentoncredit);
			}
		
		}
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
		//echo highlight_string(var_export($cashcollected[0], TRUE));
		
		
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			//$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
		
			$MonthlyAccountsReceivable[$i-1] 	= $monthlytotalsales[$i-1] - $cashcollectedcurrent[$i-1];
			$TotalAccountsReceivable[$i-1]		= $TotalAccountsReceivable[$i-2] + $monthlytotalsales[$i-1] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsReceivable[$i-1] : 0 );
				}
			}
		
		}
		
		
		$TotalAccountsReceivable = array_slice($TotalAccountsReceivable,1);
		
		$this->expensesdata['TotalAccountsReceivable'] = $TotalAccountsReceivable;
		
		
		$cashcollectedreceivable 		= $cashcollected;
		$totalcashcollectedreceivable 	= $totalcashcollected;
		//echo highlight_string(var_export($cashcollectedreceivable, TRUE));
		
		//echo highlight_string(var_export($totalcashcollectedreceivable, TRUE));
		
		//Calculate monthly payable
		$percentoncredit 	= $cashsetting['percentage_purchase']/100;
		$daystocollect		= $cashsetting['days_make_payments'];
		
		$tmptotalDirectCost	= $this->salesdata['monthlyTotalDirectCost'];
		
		$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];
		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		
		$totalExpenses = array();
		
		for($j = 0; $j < 12; $j++ ){
			$totalExpenses[] = $tmptotalDirectCost[$j] + $monthlytotalexpenses[$j] - $monthlytotalsalary[$j];
		}
		
		
		
		
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsPayable 		= array();
		$MonthlyAccountsPayable[-1] 	= 0;
		$TotalAccountsPayable			= array();
		$TotalAccountsPayable[-1]		= 0;
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $totalExpenses[$j];
			} else {
				$tmprow[$j] = $totalExpenses[$j] * (1-$percentoncredit);
			}
		
		}
		
		
		
		
		
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
		
		
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			$MonthlyAccountsPayable[$i-1] 	= $totalExpenses[$i-1] - $cashcollectedcurrent[$i-1];
			$TotalAccountsPayable[$i-1]		= $TotalAccountsPayable[$i-2] + $totalExpenses[$i-1] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsPayable[$i-1] : 0 );
				}
			}
		
		}
		
		
		
		
		
		$MonthlyAccountsPayable = array_slice($MonthlyAccountsPayable,1);
		$TotalAccountsPayable = array_slice($TotalAccountsPayable,1);
		
		$totalcashcollectedpayable = $totalcashcollected;
		
		//echo highlight_string(var_export($totalcashcollectedpayable, TRUE));
		
		$this->loansdata['MonthlyAccountsPayable'] = $MonthlyAccountsPayable;
		
		
		//calculate monthly cash
		$monthlycash = array();
		
		$mhtml = "<table>";
		
		$incometax = $this->profitlossdata['monthlyincometax'];
		
		
		for($i = 0; $i < 12; $i++) {
		
			if ($i>0) {
				$monthlycash[$i] = $monthlycash[$i-1];
			}
		
			$monthlycash[$i] = $monthlycash[$i] + $monthlyreceive[$i] - $monthlypayment[$i] + $totalcashcollectedreceivable[$i];
			$monthlycash[$i] = $monthlycash[$i] - $totalcashcollectedpayable[$i] - $monthlytotalsalary[$i] - $monthlytotalrelatedexpenses[$i];
			$monthlycash[$i] = $monthlycash[$i] - $monthylyinterestincurred[$i] - $monthlypurchase[$i] - $incometax[$i];
		
		}
		
		
		/*
		echo '<br>monthlyreceive: '; 
		echo highlight_string(var_export($monthlyreceive, TRUE));
		echo '<br>$monthlypayment: ';
		echo highlight_string(var_export($monthlypayment, TRUE));
		echo '<br>$totalcashcollectedreceivable: ';
		echo highlight_string(var_export($totalcashcollectedreceivable, TRUE));
		echo '<br>$totalcashcollectedpayable: ';
		echo highlight_string(var_export($totalcashcollectedpayable, TRUE));
		echo '<br>$monthlytotalsalary: ';
		echo highlight_string(var_export($monthlytotalsalary, TRUE));
		echo '<br>$monthlytotalrelatedexpenses: ';
		echo highlight_string(var_export($monthlytotalrelatedexpenses, TRUE));
		echo '<br>$monthylyinterestincurred: ';
		echo highlight_string(var_export($monthylyinterestincurred, TRUE));
		echo '<br>$monthlypurchase: ';
		echo highlight_string(var_export($monthlypurchase, TRUE));
		echo '<br>$incometax: ';
		echo highlight_string(var_export($incometax, TRUE));
		*/
		
		$this->loansdata['monthlycash'] = $monthlycash;
		
		
		/*
		 for($i = 0; $i < 12; $i++) {
		$tmpoperatingIncomes = $margin - $monthlytotalexpenses[$i];
		
		$tmploan[$i] = $tmploansrows[$i] + $monthlyreceive[$i] - $monthlypayment[$i] + $tmpoperatingIncomes;
		
		if ($i==1) {
		$tmploan[$i] = $tmploan[$i] + $tmploan[0];
		} elseif ( $i != 0 ) {
		$tmploan[$i] = $tmploan[$i] + ($tmploan[$i-1] - $tmploan[$i-2]);
		}
		
		$monthlycash[] = ($tmploan[$i] - $monthlyreceive[$i]) + $monthlypayment[$i] - $monthlyincometax[$i];
			
		
		}
		*/
		//$this->loansdata['monthlycash'] = $monthlycash;
		
		//fill empty keys
		for($i = 0; $i < 12; $i++) {
			if (isset($accountReceivable_allMonths[$i+1])) {
				$accountReceivable_allMonths[$i] = $accountReceivable_allMonths[$i+1];
				unset ($accountReceivable_allMonths[$i+1]);
			} elseif (isset($accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)])) {
				$accountReceivable_allMonths[$i] = $accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)];
				unset ($accountReceivable_allMonths[str_pad($i+1,2,"0",STR_PAD_LEFT)]);
			} else {
				$accountReceivable_allMonths[$i] = 0 ;
			}
		}
		
		//$this->loansdata['accountReceivable_allMonths'] = $accountReceivable_allMonths;
		
		$this->loansdata['accountReceivable_allMonths'] = $TotalAccountsReceivable;
		
		//echo highlight_string(var_export($accountReceivable_allMonths, TRUE));
		//$accountReceivable_allMonths
		//end monthly cash in hand
		
		//total current assets
		$totalcurrentassets_monthly = array();
		for($i = 0; $i < 12; $i++) {
			$totalcurrentassets_monthly[$i] = $monthlycash[$i] + $TotalAccountsReceivable[$i];
		}
		
		$yrAccountReceivable = array();
		
		
		
		$balTotalSales = array();
		
		$tarray = $this->salesdata['yrlyTotalSales'];
		
		$currency = $this->salesdata['currency'];
		
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balTotalSales[] = floatval($value);
		}
		
		$balTotalExpenses = array();
		
		$tarray = $this->salesdata['yrlyTotalCosts'];
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balTotalExpenses[] = floatval($value);
		}
		//echo highlight_string(var_export($TotalAccountsReceivable, TRUE));
		//echo highlight_string(var_export($yrAccountPayable, TRUE));
		
		
		$yrAccountReceivable[0] = $TotalAccountsReceivable[11];
		$yrAccountReceivable[1] = $yrAccountReceivable[0]/$balTotalSales[1]*$balTotalSales[2];
		$yrAccountReceivable[2] = $yrAccountReceivable[0]/$balTotalSales[1]*$balTotalSales[3];
		
		$yrAccountPayable = array();
		$yrAccountPayable[0] = $TotalAccountsPayable[11];
		$yrAccountPayable[1] = $yrAccountPayable[0]/$balTotalExpenses[1]*$balTotalExpenses[2];
		$yrAccountPayable[2] = $yrAccountPayable[0]/$balTotalExpenses[1]*$balTotalExpenses[3];
		
		$expectedloanspayment = array(0,0,0);
		
		$this->loansdata['totalcurrentassets_monthly'] = $totalcurrentassets_monthly;
		
		
		$major_purchase = $this->profitlossdata['yearlymajor_purchase'];
		
		
		
		
		
		
		
		/*=========================================*/
	
		
		/*
		highlight_string(var_export($TotalExpenses, TRUE));
		
		echo '<br>yearly interest incurred: ' ;
		highlight_string(var_export($this->profitlossdata['yearlyinterestincurredrows'], TRUE));
		echo '<br>yearly direct cost incurred: ' ;
		highlight_string(var_export($this->profitlossdata['yearlydirectcostrows'], TRUE));
		echo '<br>yearly depreciation: ' ;
		highlight_string(var_export($this->profitlossdata['yearlydepreciation'], TRUE));
		echo '<br>yearly incometax: ' ;
		highlight_string(var_export($this->profitlossdata['yearlyincometaxrows'], TRUE));
		*/
		
		//$this->expensesdata['balYearlyTotalExpenses'] = $TotalExpenses;
		
		$yearlyTotalExpenses = $this->expensesdata['balYearlyTotalExpenses'];
		
		
		/*========================================*/
		
		
		
		
		$tmpvals  = $this->profitlossdata['yearlydepreciation'];
		
		
		$yrAmountReceive1 = $this->loansdata['yearly']['totalrows'];
		
		$yrAmountReceive = array();
		
		$tarray = $yrAmountReceive1;
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$yrAmountReceive[] = floatval($value);
		}
		
		
		
		
		
		
		$balRevenue = array();
		
		$tarray = $this->profitlossdata['yearlyrevenuerows'];
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balRevenue[] = floatval($value);
		}
		
		$balmoloansdata1 = $this->loansdata['monthly']['totalrows'];
		$balmoloansdata	 = array();
		$tarray = $balmoloansdata1;
		foreach($tarray as $key=>$value) {
			$value = str_replace(array($currency,','), '', $value);
			$balmoloansdata[] = floatval($value);
		}
		
		
		$expectedloanspayment[0] = array_sum($balmoloansdata);
		
		$balRevenue 		= array_slice($balRevenue, 1);
		$yrAmountReceive 	= array_slice($yrAmountReceive, 1);
		
		
		$cash = array();
		
		$cash[1] = $this->loansdata['monthlycash'][11];
		
		
		$cash[2] = $cash[1] 
		+ $yrAccountReceivable[0]		
		+ $balRevenue[1]
		- $yrAccountReceivable[1]
		+ $yrAmountReceive[1]
		- $expectedloanspayment[1]
		- $major_purchase[1]
		- $yrAccountPayable[0]
		- $yearlyTotalExpenses[1]
		+ $yrAccountPayable[1]
		+ $tmpvals[1];
				
				
		$cash[3] = $cash[2] 
		+ $yrAccountReceivable[1]		
		+ $balRevenue[2]
		- $yrAccountReceivable[2]
		+ $yrAmountReceive[2]
		- $expectedloanspayment[2]
		- $major_purchase[2]
		- $yrAccountPayable[1]
		- $yearlyTotalExpenses[2]
		+ $yrAccountPayable[2]
		+ $tmpvals[2];
		
		
		/*
		echo '<br>cash 1<br>';
		echo $cash[2] . '<br>';
		echo $yrAccountReceivable[1] . '<br>';
		echo $balRevenue[2] . '<br>';
		echo $yrAccountReceivable[2] . '<br>';
		echo $yrAmountReceive[2] . '<br>';
		echo $expectedloanspayment[2] . '<br>';
		echo $major_purchase[2] . '<br>';
		echo $yrAccountPayable[1] . '<br>';
		echo $yearlyTotalExpenses[2] . '<br>';
		echo $yrAccountPayable[2] . '<br>';
		echo $tmpvals[2] . '<br>';
		*/
		
		
		
		$this->balancesheetdata['balcash'] = $cash;
		$this->balancesheetdata['balaccreceivable'] = $yrAccountReceivable;
		$this->balancesheetdata['balaccpayable'] = $yrAccountPayable;
		
		//highlight_string(var_export($cash, TRUE));
		
		
		
		
		
		/*
		echo '<br>$yrAccountReceivable: ';
		highlight_string(var_export($yrAccountReceivable, TRUE));
		echo '<br>$balRevenue: ';
		highlight_string(var_export($balRevenue, TRUE));
		echo '<br>$yrAccountReceivable: ';
		highlight_string(var_export($yrAccountReceivable, TRUE));
		echo '<br>$yrAmountReceive: ';
		highlight_string(var_export($yrAmountReceive, TRUE));
		echo '<br>$expectedloanspayment: ';
		highlight_string(var_export($expectedloanspayment, TRUE));
		echo '<br>$major_purchase: ';
		highlight_string(var_export($major_purchase, TRUE));
		echo '<br>$yrAccountPayable: ';
		highlight_string(var_export($yrAccountPayable, TRUE));
		echo '<br>$yearlyTotalExpenses: ';
		highlight_string(var_export($yearlyTotalExpenses, TRUE));
		echo '<br>$yrAccountPayable: ';
		highlight_string(var_export($yrAccountPayable, TRUE));
		echo '<br>$tmpvals: ';
		highlight_string(var_export($tmpvals, TRUE));
		*/
		$balTotalCurrentAssets = array();
		for($i = 0; $i < 3; $i++) {
			$balTotalCurrentAssets[$i] = $cash[$i+1] + $yrAccountReceivable[$i];
		}
		
		$monthly_totallongassets = $this->profitlossdata['monthly_totallongassets'];
		
		$balLongTermsAssets = array();
		$balLongTermsAssets[] = $monthly_totallongassets[11];
		for($i = 1; $i < 3; $i++) {
			$balLongTermsAssets[$i] = $balLongTermsAssets[$i-1] + $major_purchase[$i];
		}
		
		$monthly_accudepreciation = $this->profitlossdata['monthly_balaccudepreciation'];
		
		$balAccuDepreciation = array();
		$balAccuDepreciation[0] = $monthly_accudepreciation[11];
		$balAccuDepreciation[1] = $balAccuDepreciation[0] + $tmpvals[1];
		$balAccuDepreciation[2] = $tmpvals[0] - $tmpvals[1] - $tmpvals[2];
		
		
		$balTotalLongTermsAssets = array();		
		for($i = 0; $i < 3; $i++) {
			$balTotalLongTermsAssets[$i] = $balLongTermsAssets[$i] + $balAccuDepreciation[$i];
		}
		
		$balTotalAssets = array();
		for($i = 0; $i < 3; $i++) {
			$balTotalAssets[$i] = $balTotalCurrentAssets[$i] + $balTotalLongTermsAssets[$i];
		}
		
		$this->profitlossdata['ns']['balTotalCurrentAssets'] = $balTotalCurrentAssets; 
		$this->profitlossdata['ns']['balLongTermsAssets']	 = $balLongTermsAssets;
		$this->profitlossdata['ns']['balAccuDepreciation']	 = $balAccuDepreciation;
		$this->profitlossdata['ns']['balTotalLongTermsAssets']	 = $balTotalLongTermsAssets;
		$this->profitlossdata['ns']['balTotalAssets']	 		= $balTotalAssets;
		
		
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $this->loansdata['$monthlyreceive'][$i] - 	$this->loansdata['$monthlypayment'][$i];
		}
		
		$balSalesTax 		= array(0,0,0);
		$balShortTermDebt 	= array(0,0,0); 
		$balTotalCurrentLiability = array(); //value should be account payable + sales tax + short term debt, but for now sales tax and short term debt values are zero
		
		for($i = 0; $i < 3; $i++) {
			$balTotalCurrentLiability[] = $yrAccountPayable[$i] + $balSalesTax[$i] + $balShortTermDebt[$i];
		}
		
		
		
		$balLongTermDebt 	= array();
		$balLongTermDebt[0] =  $tmpvalues[11];
		for($i = 1; $i < 3; $i++) {
			$balLongTermDebt[$i] =  $balLongTermDebt[$i-1] +  $yrAmountReceive[$i] - $expectedloanspayment[$i] ;
		}
		
		
		$balTotaLiabilities = array();
		for($i = 0; $i < 3; $i++) {
			$balTotaLiabilities[] = $balTotalCurrentLiability[$i] + $balLongTermDebt[$i];
		}
		
		
		$this->profitlossdata['ns']['balTotalCurrentLiability']	 	= $balTotalCurrentLiability;
		$this->profitlossdata['ns']['balLongTermDebt']	 			= $balLongTermDebt;
		$this->profitlossdata['ns']['balTotaLiabilities']	 		= $balTotaLiabilities;
		
		
		
		//monthly retained earnings and earnings
		$balMonthlyEarnings = $this->salesdata['netprofit'];
			
		
		$balMonthlyRetainedEarnings 	= array();
		$balMonthlyRetainedEarnings[0] = 0;
		for ($i = 1; $i < 12; $i++ ) {
			$balMonthlyRetainedEarnings[$i] = $balMonthlyRetainedEarnings[$i-1] + $balMonthlyEarnings[$i-1];
		}
		
		$balNetProfit1 = $this->profitlossdata['yearlynetprofitrows'];
		$balNetProfit = array();
		foreach($balNetProfit1 as $key=>$value) {
			$value = str_replace(array($currency,',',')','('), '', $value);
			$balNetProfit[] = floatval($value);
		}
		
		$balNetProfit = array_slice($balNetProfit,1);
		
		
		$balEarnings		= array();
		$balEarnings[0]		=  $balEarnings[11] + $balMonthlyRetainedEarnings[11];
		$balEarnings[1]		=  $balNetProfit[1];
		$balEarnings[2]		=  $balNetProfit[2];
		
		$balRetainedEarnings = array();
		$balRetainedEarnings[0] =  0;
		
		for ($i = 1; $i < 3; $i++ ) {
			$balRetainedEarnings[$i] = $balRetainedEarnings[$i-1] + $balEarnings[$i-1];
		}
		
		
		$balTotalOwnerEquity = array();
		for ($i = 0; $i < 3; $i++ ) {
			$balTotalOwnerEquity[$i] = $balRetainedEarnings[$i] + $balEarnings[$i];
		}
		
		$balTotalLiabilitiesAndEquities = array();
		for ($i = 0; $i < 3; $i++ ) {
			$balTotalLiabilitiesAndEquities[$i] = $balTotalOwnerEquity[$i] + $balTotaLiabilities[$i];
		}
		
		$this->profitlossdata['ns']['balEarnings']	 			= $balEarnings;
		$this->profitlossdata['ns']['balRetainedEarnings']	 	= $balRetainedEarnings;
		$this->profitlossdata['ns']['balTotalOwnerEquity']	 	= $balTotalOwnerEquity;
		$this->profitlossdata['ns']['balTotalLiabilitiesAndEquities']	= $balTotalLiabilitiesAndEquities;
		
		
		