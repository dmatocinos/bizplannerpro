<?php
	// Update 08/June/2013
    	$_loanInvestment = new loansInvestments_lib();
	
		$_yrlyCalcInterest = array();
		$array_interestIncuredCounter = 0;
		$array_interestIncured = array();
		$currency = $sales->defaultCurrency;
		
		list($array_interestIncured, $monthly_interest_incurred) = $_loanInvestment->getInterestIncurred();
		/* --- Return $array_interestIncured;	---*/
?>
    
      
