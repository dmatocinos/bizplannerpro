<?php
	// 08/June/2013
	
		/*--------------------------------------------------------------------------------------------------------------------------
			Cash In Hand = the sum of (Deduct amount receive from amount paid back each year) under Loan and Investment section 
			Deduct Account Receiveable from Totol of cah in hand as well.
			Add Account Payable to the total
			Minus Income Tax
		---------------------------------------------------------------------------------------------------------------------------*/
		
        //print_r($_array_recieveAmountYrly); 
		//print_r($_array_paymentAmountYrly);
		//print_r($grossMargin);
		//print_r($allExpense);
		//print_r($array_eachYrEstimatedIncomeTax);
		
		$eachYrlyCalc = array();
		
		$totalYrOne = array();
		$totalYrTwo = array();
		$totalYrThree = array();
		$loanTakenMinusPaymentMade = array();
		$newCashInHand = array();
		
		
		
		if(isset($_array_recieveAmountYrly))
		{
			
			// Amount reveiev minus payment made
			for($fundSection1 = 0; $fundSection1 < count($_array_recieveAmountYrly); $fundSection1 ++)
			{
				
				for($fundSection2 = 1; $fundSection2 <= count($_array_recieveAmountYrly[$fundSection1]); $fundSection2 ++)
				{
					//echo $_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2]."<br/>";
					
					($_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2] - $_array_paymentAmountYrly[$fundSection1]["yr_".$fundSection2])."<br/>";
					$eachYrlyCalc[$fundSection1]["yr_".$fundSection2] = ($_array_recieveAmountYrly[$fundSection1]["yr_".$fundSection2] - $_array_paymentAmountYrly[$fundSection1]["yr_".$fundSection2]);
					
					if($fundSection2 == 1)
					{
						$totalYrOne[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
					elseif($fundSection2 == 2)
					{
						$totalYrTwo[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
					elseif($fundSection2 == 3)
					{
						$totalYrThree[$fundSection1] = $eachYrlyCalc[$fundSection1]["yr_".$fundSection2];
					}
				}
			}
		}
		
		
		/*---	Calculate the Cash In hands	---*/
		for($x = 0; $x < count($grossMargin); $x++)
		{
			$operatingIncomes = ($grossMargin[$x] - $allExpense[$x]);
			if($x == 0) // Year one
			{
				$loanTakenMinusPaymentMade[$x]  = (array_sum($totalYrOne) +  $operatingIncomes);
			}
			elseif($x == 1) // year 2
			{
				$loanTakenMinusPaymentMade[$x] = (array_sum($totalYrTwo) +  $operatingIncomes);
				$loanTakenMinusPaymentMade[$x] = ($loanTakenMinusPaymentMade[$x] + $loanTakenMinusPaymentMade[0]);
				
			}
			elseif($x == 2) // year 3
			{
				$loanTakenMinusPaymentMade[$x] = (array_sum($totalYrThree) +  $operatingIncomes);
				// Add the original calculation of year 2 by taking year one from year two
				$loanTakenMinusPaymentMade[$x] = ($loanTakenMinusPaymentMade[$x] + ($loanTakenMinusPaymentMade[1] - $loanTakenMinusPaymentMade[0]));
			}
			
		}
			
		//print_r($loanTakenMinusPaymentMade);
		//print_r(array_sum($totalYrOne));
		
		
		
		/*---	Deduct Account receivabel from the total	i.e ($loanTakenMinusPaymentMade - $accountReceivable_allYears) --- 
				Add Account Payable 
		*/
		for($yrs = 0; $yrs < count($loanTakenMinusPaymentMade); $yrs++)
		{
			$newCashInHand[$yrs] = (($loanTakenMinusPaymentMade[$yrs] - $accountReceivable_allYears[$yrs]) + $Total_accountPayable_allYears_bdgt[$yrs] - $array_eachYrEstimatedIncomeTax[$yrs]);
		}
		
		/*---	Return array $newCashInHand ---*/
	?>
         