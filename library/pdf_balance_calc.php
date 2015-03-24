<?php

    

    $financialYearSF = $sales->startFinancialYear;
    $financialYearSF = $financialYearSF + 1;
    $years = array('');
    
    //init year scope
    
    if($allSalesDetails) 
	{	
		foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
		{				
			$years[] = "FY" . $financialYearSF;
            $financialYearSF = $financialYearSF+1;
		}
	}
	else {
		$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
		
		for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
		{
		    $years[] = "FY" . $financialYearSF;
	        $financialYearSF = $financialYearSF + 1;
		}
    }
		
	
	//init cash in hand
	
	$cash = array ("Cash");
	
	for($eachY = 0; $eachY < count($newCashInHand); $eachY++ )
    {
                    if($newCashInHand[$eachY] < 0)
					{
						$open_bracket  = OPEN_BRACKET;
						$closed_bracket  = CLOSED_BRACKET;
						$cancelNegative = -1;
					}
					else
					{
						$open_bracket  = "";
						$closed_bracket  = "";
						$cancelNegative = 1;
					}
					
					$cash[] = $open_bracket . $sales->defaultCurrency 
					    . number_format(($newCashInHand[$eachY] * $cancelNegative), 0, '.', ',')
					    . $closed_bracket;
               
                $totalCostCounter = $totalCostCounter + 1;
     }
				
	//calculate monthly cash in hand
    foreach($allcashProjection as $projection)
    {
    
     
    	for($i = 0; $i < 12; $i++)
    	{
    		$counterstr 		= str_pad($i+1,2,"0",STR_PAD_LEFT);
    		$amountreceivemonthly[$i]		= $projection['limr_month_' . $counterstr];
    		
    		
    	}
    }
    
    	//echo highlight_string(var_export($amountreceivemonthly, TRUE));
    
    //end monthly cash in hand
    
    
				
	if(count($newCashInHand)<=0)
	{
		for($eachYnoValue = 0; $eachYnoValue < $_numberOfFinancialYrForcasting; $eachYnoValue++ )
		{	
            $cash[] = $sales->defaultCurrency . "0";
		}
	}
	
	
	
	$accountreceivable = array('Accounts Receivable');
	
	$open_bracket  = "";
	$closed_bracket = "";
	$cancelNegative = 1;
				
				
	if(!isset($accountReceivable_allYears))
	{
					$accountReceivable_allYears = array();
					for($yrs = 0; $yrs < $_numberOfFinancialYrForcasting; $yrs++)
					{
	
                        $accountreceivable[] = $sales->defaultCurrency . "0";
			$accountreceivable_raw[] = 0;

                   
					}
	}
	else{
					for($yrs = 0; $yrs < count($accountReceivable_allYears); $yrs++)
					{
						
						$open_bracket   = "";
	        		    $closed_bracket = "";
    				    $cancelNegative = 1;
						
						
						if(count($accountReceivable_allYears) > 0)
						{
							if($accountReceivable_allYears[$yrs] < 0)
							{
								$open_bracket  = OPEN_BRACKET;
								$closed_bracket  = CLOSED_BRACKET;
								$cancelNegative = -1;
							}
						}
						
						$accountreceivable[] =  $open_bracket 
						    . $sales->defaultCurrency . number_format(($accountReceivable_allYears[$yrs] * $cancelNegative), 0, '.', ',') 
						    . $closed_bracket;
						$accountreceivable_raw[] = $accountReceivable_allYears[$yrs];
						
					
					 
					}
	}
	
	
	$currentassets = array("Total Current Assets");
	
	
	$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
	for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
	{
	                $open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
	
	
					if(count($accountReceivable_allYears) > 0)
					{
						$totalCurrentAssets[$e_year] = $accountReceivable_allYears[$e_year] + $newCashInHand[$e_year];
						
						if($totalCurrentAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
						
						$currentassets[] =  $open_bracket 
						    . $sales->defaultCurrency . number_format(($totalCurrentAssets[$e_year] * $cancelNegative), 0, '.', ',')  
						    . $closed_bracket;						
					}
					else
					{
						$currentassets[] =  $sales->defaultCurrency . "0";
					}
	}
	
	
	//calculate long term assets
	$lib = new expenditure_lib();
	$numbersyrOfFinancialForecast = $lib->numberOfFinancialYrForcasting;
	$major_purchases_details = $lib->getAllMajorPurchaseDetails('', 'mp_date','');
	$dyears = array();
	foreach ($major_purchases_details as $purchase) {
		list($pm, $py) = explode(' ', $purchase['mp_date']);
		if ( ! isset($dyears[$py])) {
			$dyears[$py] = 0;
		}
	
		if ($purchase['mp_depreciate']) {
			$dyears[$py] = $purchase['mp_price'];
		}
	}
	$major_purchase = array_values($dyears);
	$long_term_assets = array();
	
	$p = .20;
	
	for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ) {
		$total_major_purchase = 0;
		for ($i = 0; $i < $e_yr; $i++) {
			if (isset($major_purchase[$i]))
				$total_major_purchase += $major_purchase[$i];
		}
	
		if (isset($data[$e_yr - 1])) {
			$total_major_purchase -= $data[$e_yr - 1];
		}
	
		$long_term_assets[$e_yr] = $total_major_purchase;
		$data[$e_yr] = $total_major_purchase * $p;
	}
	/*
	foreach ($major_purchases_details as $purchase) {
		list($pm, $py) = explode(' ', $purchase['mp_date']);
		if ( ! isset($dyears[$py])) {
			$dyears[$py] = 0;
		}
	
		if ($purchase['mp_depreciate']) {
			$dyears[$py] = $purchase['mp_price'];
		}
	}
	$major_purchase = array_values($dyears);
	$long_term_assets = array();
	
	$p = .20;
	
	for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ) {
		$total_major_purchase = 0;
		for ($i = 0; $i < $e_yr; $i++) {
			if (isset($major_purchase[$i]))
				$total_major_purchase += $major_purchase[$i];
		}
	
		if (isset($data[$e_yr - 1])) {
			$total_major_purchase -= $data[$e_yr - 1];
		}
	
		$long_term_assets[$e_yr] = $total_major_purchase;
		$data[$e_yr] = $total_major_purchase * $p;
	}
	*/	
	
	
	$longtermassets = array("Long Term Assets");
    //note: add empty row before
    //note no data to pick up for long term assets				
	$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
	$acuLAssets		= 0;
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
    				
    				
					if(isset($long_term_assets))
					{
						$acuLAssets += $long_term_assets[$e_year];
						
						if($long_term_assets[$e_year] < 0 )
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
					
					    $longtermassets[] = $open_bracket . $sales->defaultCurrency . $acuLAssets . $closed_bracket;
					
					}
					else
					{
						 $longtermassets[] = $sales->defaultCurrency . 0 ;
					}
				}// End of loop

			  
             
        $depreciations = array("Accumulated Depreciation");
    //note: value in website is hardcoded to zero
             
     $_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
     $acuLAssets = 0;     
     $p = .20;
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
				
				    $open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
    				
    				$acuLAssets += $long_term_assets[$e_year];
    				$acuDep	= $acuLAssets * $p;
    				
						
						if(-$acuDep  < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						 //$depreciations[] = $open_bracket . $sales->defaultCurrency 
						 //. number_format(($totalCurrentAssets[$e_year] * $cancelNegative), 0, '.', ',')
						 //. $closed_bracket;
						  $depreciations[] = $open_bracket . $sales->defaultCurrency . $acuDep . $closed_bracket;
					
					
	}        
  
  
  
  $totallongtermassets = array("Total Long-Term Assets");
  //note: harcoded in website
  $totallongtermassetsdata = array();
  $_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
  $acuLAssets 	= 0;
  $p 			= 0.20;
  
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
						$acuLAssets += $long_term_assets[$e_year];
						$acuDep	= $acuLAssets * $p;
					
						$open_bracket   = "";
	        		    $closed_bracket = "";
    				    $cancelNegative = 1;
				
						
						$totallongtermassetsdata[$e_year] = $acuLAssets-$acuDep;
						
						if($totallongtermassetsdata[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						//$totallongtermassets[] = $open_bracket . $sales->defaultCurrency 
						 //. number_format(($totalCurrentAssets[$e_year] * $cancelNegative), 0, '.', ',')
						 //. $closed_bracket;
						 
						 $totallongtermassets[] = $open_bracket . $sales->defaultCurrency . $totallongtermassetsdata[$e_year] . $closed_bracket;
						 
					
				}
  

    $totalassets = array("Total Assets");
  //note: add empty line before
  
  if(isset($totalCurrentAssets))
		{
        	$totalAssets = $totalCurrentAssets;
		}
		else
		{
			$totalAssets = array();
		}
		
    		
    /*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					if(count($totalAssets) > 0)
					{
						$open_bracket   = "";
	        		    $closed_bracket = "";
    				    $cancelNegative = 1;
				
    				    $totalAssets[$e_year] += $totallongtermassetsdata[$e_year];
												
						
						if($totalAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						$totalassets[] = $open_bracket . $sales->defaultCurrency 
						 . number_format(($totalAssets[$e_year] * $cancelNegative), 0, '.', ',')
						 . $closed_bracket;
					}
					else
					{
					    $totalassets[] = $sales->defaultCurrency . 0 ;
					}
				}
  
  
  $accountpayable = array("Account Payable");
  //note: add empty line before
  
    //var_dump($Total_accountPayable_allYears_bdgt);
  
  	for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					if(isset($Total_accountPayable_allYears_bdgt))
					{
						$open_bracket   = "";
	        		    $closed_bracket = "";
    				    $cancelNegative = 1;
				
												
						
						if($Total_accountPayable_allYears_bdgt[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						$accountpayable[] = $open_bracket . $sales->defaultCurrency 
						 . number_format(($Total_accountPayable_allYears_bdgt[$e_year] * $cancelNegative), 0, '.', ',')
						 . $closed_bracket;
					    $accountpayable_raw[] = $Total_accountPayable_allYears_bdgt[$e_year];
					}
					else
					{
					    $accountpayable[] = $sales->defaultCurrency . 0 ;
					    $accountpayable_raw[] = 0;
					}
				}
  
  
  $salestaxespayable = array("Sales Taxes Payable");
  // note: hard coded to zero in website
  
  
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
					
					if(isset($totalCurrentAssets))
					{
						if($totalCurrentAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
					
					    $salestaxespayable[] = $open_bracket . $sales->defaultCurrency . 0 . $closed_bracket;
					
					}
					else
					{
						 $salestaxespayable[] = $sales->defaultCurrency . 0 ;
					}
				}// End of loop
  
  
  
  $shorttermdebt = array("Short-Term Debt");
  //note: hard coded to zero in website
  
   /*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
					
					if(isset($totalCurrentAssets))
					{
						if($totalCurrentAssets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
					
					    $shorttermdebt[] = $open_bracket . $sales->defaultCurrency . 0 . $closed_bracket;
					
					}
					else
					{
						 $shorttermdebt[] = $sales->defaultCurrency . 0 ;
					}
				}// End of loop
  
  
  
  
  $totalcurrentliability = array("Total Current Liabilities");
   //note: hard coded to zero in website
   
   /*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
					
					if(isset($Total_accountPayable_allYears_bdgt))
					{
						if($Total_accountPayable_allYears_bdgt[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
					
					    $totalcurrentliability[] = $open_bracket . $sales->defaultCurrency
					    . number_format(($Total_accountPayable_allYears_bdgt[$e_year] * $cancelNegative), 0, '.', ',')
					    . $closed_bracket;
					    
					    
					}
					else
					{
						 $totalcurrentliability[] = $sales->defaultCurrency . 0 ;
					}
				}// End of loop
  
  
  
  
  $longtermdebt = array("Long-Term Debt");
  //note add empty line before
  
  	$loanTakenMinusPaymentMade;
	$array_interestIncured;
	$total_long_term_assets = array();
  
  
 
    $operatingIncome = array();
	if(!isset($allExpense)){$allExpense = array();}
				
				
  for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
    {
					if((count($grossMargin) > 0) and (count($allExpense)))
					{
						$open_bracket   = "";
	        		    $closed_bracket = "";
    				    $cancelNegative = 1;
				
						$operatingIncome[$e_year] = ($grossMargin[$e_year] - $allExpense[$e_year]);
						
						
			
						
						$total_long_term_assets[$e_year] = (($loanTakenMinusPaymentMade[$e_year] + $array_interestIncured[$e_year]) - $operatingIncome[$e_year]);
						
						$total_long_term_assets[$e_year] *= -1; 
						
						if($total_long_term_assets[$e_year] < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
						
						$longtermdebt[] = $open_bracket . $sales->defaultCurrency 
						 . number_format(($total_long_term_assets[$e_year] * $cancelNegative), 0, '.', ',')
						 . $closed_bracket;
					}
					else
					{
					    $longtermdebt[] = $sales->defaultCurrency . 0 ;
					}
				}
  
				
				
  $totalliability = array("Total Liabilities");
  //note: add empty row before
  //hardcoded to zero in web
  
  
 	for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
					$open_bracket   = "";
	        		$closed_bracket = "";
    				$cancelNegative = 1;
					
    				
    				
    				
					if(isset($total_long_term_assets))
					{
						$tmptotal = $Total_accountPayable_allYears_bdgt[$e_year] + $total_long_term_assets[$e_year];
						if($tmptotal < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
					
					
					    $totalliability[] = $open_bracket . $sales->defaultCurrency 
						 . number_format(($tmptotal * $cancelNegative), 0, '.', ',')
						 . $closed_bracket;
					
					}
					else
					{
						 $totalliability[] = $sales->defaultCurrency . 0 ;
					}
				}// End of loop
  
  
             

						