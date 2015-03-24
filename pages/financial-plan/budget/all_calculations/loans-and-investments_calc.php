<?php
$cashProjection = new loansInvestments_lib();

	if(isset($_GET['edit_loanInvestID']))
	{
		(int)$cashProjectionID = $_GET['edit_loanInvestID'];	
		$whereEdit = " loan_investment.li_id != ".$cashProjectionID;
		$allcashProjection = $cashProjection->getAllCashProjections($whereEdit, "", "");
	}
	
	
	else if(isset($_GET['add']) and ($_GET['add'] == "new_projection"))
	{
		(int)$cashProjectionID = $cashProjection->maxEmployeeId;
		$whereEditLatest = "loan_investment.li_id != ".$cashProjectionID;
		$allcashProjection = $cashProjection->getAllCashProjections($whereEditLatest, "", "");
	}
	else
	{
		$allcashProjection = $cashProjection->getAllCashProjections("", "", "");
	}
	
	$currency = $cashProjection->defaultCurrency;
	
?>


	<?php 
	
	if($allcashProjection)
	{
		$_arrayOfYrlyCalcInterest = array();
		$_arrayOfYrlyCalcInterestCounter = 0;
		
		
		// Amount Receive Yearly
		$_array_recieveAmountYrly = array();
		$_array_recieveAmountYrlyCounter1 = 0;
		
		// Amount Paid Yearly
		$_array_paymentAmountYrly = array();
		$_array_paymentAmountYrlyCounter1 = 0;
		
		
		foreach($allcashProjection as $cashProjectionDetails)
		{
		?>						
		
                 <?php
                    
                    /*----------------------------------------------------------------------------------------
                    
                        Amount Received Section
                    
                    ----------------------------------------------------------------------------------------*/
                    $zeroPrefix = 0;
                    $zeroPrefix = 0;
                    $zeroCounter = 0;
                    $oneToTwelveCounterLoan = 1;
                    for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
                    {
                        if($oneToTwelveCounterLoan < 10)
                        {	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
                            $oneToTwelveCounterLoan = $zeroPrefix.$oneToTwelveCounterLoan;
                        }
                        
                        /*---	Fomat figure and remove seperated commas	---*/
                        $amountReceiveWithoutCommas[$e_month] = number_format($cashProjectionDetails["limr_month_".$oneToTwelveCounterLoan], 0, '.', '');
                        
                        /*---	Fomat figure and seperate thousands with commas	---*/
                        $amountReceiveWithCommas[$e_month] = number_format($cashProjectionDetails["limr_month_".$oneToTwelveCounterLoan], 0, '.', ',');
                        
                        /*---	Add to counter until it gets to 12	---*/
                        $oneToTwelveCounterLoan = $oneToTwelveCounterLoan + 1; 
                        
                    }
                ?>
                            
                            
                      <?php
					  	$_array_recieveAmountYrlyCounter2 = 1;
					  	foreach($cashProjectionDetails['financial_receive'] as $e_financialStatus)
                        {
							$_array_recieveAmountYrly[$_array_recieveAmountYrlyCounter1]["yr_".$_array_recieveAmountYrlyCounter2] = $e_financialStatus['lir_total_per_yr'];
							$_array_recieveAmountYrlyCounter2 = ($_array_recieveAmountYrlyCounter2 + 1);
						} 
						$_array_recieveAmountYrlyCounter1 = ($_array_recieveAmountYrlyCounter1 + 1);
						?>               
                                    
           
            <!--New table -->
            
                   
                           <?php
						   
						   		/*----------------------------------------------------------------------------------------
								
									Monthly Interest for the amount Paid back Section 
								
								----------------------------------------------------------------------------------------*/
							 	$zeroPrefix = 0;
                               	$zeroCounter = 0;
								$loanCounter = 0;
								$interestRate = 0;
								$paymentsMadeBeforeLoaningCounter = 0;
								$paymentsMadeBeforeLoaning = array();
								$addUpPaymentCounter = 0;
								$addUpPayment = array();
								$addUpLoan = 0;
								
								$paymentsOverPaidCounter = 0;
								$paymentsOverPaid = array();
								$addAllPaymentsToALevel = 0;
								$tosin = 0;
								$femi = 0;
								$tope = 0;
								$getAllLoan = 0;
								$monthFirstLoanWasTakenOut_ = 0;
								$inBtwnOrExactPayBack = 0;
                               	$oneToTwelveCounterPayment = 1;
								$countNumbersOfPayments = 0; // loop control
								$counter_FirstPaymentBeforeOverpaying = 0; // loop control
								$first_payment_box_location = 0; /*--- Declare this use it should incase it never changes---*/
							   	$calculatedYearInterest = array();
							    $monthlyLoanBoxLocation_ = array();
							   	$sumOfLoansAtEachPaymentBoxLocation = array();
							   	$sumOfPaymentsAfterALoanIsTakeOut = array();
								$_paybackAddOn = 0;
								
								
								
								/*---	Set the ---*/
								$cashProjection->allData($cashProjectionDetails);
								
								/*---	At this junction, get the first loan box's location, so that you can deduct the first_payment_box_location from it ---*/
								$interestRate = $cashProjectionDetails['loan_invest_interest_rate'];
													 
								for($e_month = 12; $e_month > $zeroCounter; $e_month-- )
                                {
									if($oneToTwelveCounterPayment < 10)
									{	/*---	Add zero to the back of $e_month to make it fit with the month array	---*/
										$oneToTwelveCounterPayment = $zeroPrefix.$oneToTwelveCounterPayment;
									}
									
									/*---	Format figure and remove seperated commas	---*/
									$amountPaidBackWithoutCommas[$e_month] = number_format($cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment], 0, '.', '');
									
									/*---	Format figure and seperate thousands with commas	---*/
									$amountPaidBackWithCommas[$e_month] = number_format($cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment], 0, '.', ',');
									
									
									/*---	Check if amount received (loan) in each loan box is greater than 0	---*/
									if($amountReceiveWithoutCommas[$e_month] >0)
									{
										/*---	Get loan box loction	---*/
										$loan_box_location = $e_month;
										
										$monthlyLoanBoxLocation_[$loanCounter] = $e_month;
										
										$monthFirstLoanWasTakenOut_ = $monthlyLoanBoxLocation_[0]; // Get me the first month a loan was taken out
										
										//echo "<hr/>".$monthlyLoanBoxLocation_[$loanCounter]."<hr/>";
										
										//echo "<hr/>Loan Counter--->".$e_month;//."<hr/> Loan is ".$amountReceiveWithoutCommas[$e_month]."<hr/>";
										//echo $addUpLoan[$addUpLoanCounter] = $amountReceiveWithoutCommas[$e_month];
										$loanCounter = ($loanCounter + 1);
									}
									
									
									/*---	Check if payment value in each box is greater than 0 i.e pay back ?	---*/
									if($amountPaidBackWithoutCommas[$e_month] > 0)
									{ 
									
										
										//echo "<hr/> Differece --> ".($amountPaidBackWithoutCommas[$e_month] + $amountPaidBackWithoutCommas[$e_month])."<hr/>";
										if($monthFirstLoanWasTakenOut_ == 0)
										{
											/*---	
												If at First, there is no loan taken while payment was made
												do not calculate any interest. Just display the amount entered the same way
											---*/
												$paymentsMadeBeforeLoaning[$paymentsMadeBeforeLoaningCounter] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];
												
											  	$paymentsMadeBeforeLoaningCounter = ($paymentsMadeBeforeLoaningCounter + 1);
										}
										/*--- Calculation for the FIRST BOX of payment after at least a loan has been taken out.  ---*/
										elseif($monthFirstLoanWasTakenOut_ > 0)
										{
											
											$addUpPayment[$addUpPaymentCounter] = $amountPaidBackWithoutCommas[$e_month];
											
											if($addUpPaymentCounter > 0)
											{
												$addAllPaymentsToALevel += $addUpPayment[$addUpPaymentCounter-1];
												
												
											}
											
											$upto = $e_month;
											$getAllLoan = $cashProjection->TotalLoanTaken($upto);
											
											$differenceBtwnLoanAndPayBack = (($getAllLoan) - ($addAllPaymentsToALevel));
											
											//echo "<hr/><hr/><hr/>". $differenceBtwnLoanAndPayBack . "<hr/><hr/><hr/>";
											
											$addUpPaymentCounter =  ($addUpPaymentCounter + 1);
											
											
											//echo "<hr/><hr/><hr/> --)> <strong>".$differenceBtwnLoanAndPayBack."</strong> <(-- <hr/><hr/><hr/>";
											
											/*--- if the amount you are paying back has reached or over the amount you loaned	---*/
											if($differenceBtwnLoanAndPayBack <= 0)
											{ 
												$finalResult = 0;
												$cashProjection->PaymentTrick($e_month);
										
												$_PaymentBoxLocationNow = $cashProjection->nowPaymentBoxLocation;
												$_PaymentBoxLocationPrev = $cashProjection->previousPaymentBoxLocation;
												$_LoanBoxLocationPrev = $cashProjection->previousLoanBoxLocation;
												
												$cashProjection->LoanTakenUpto($_PaymentBoxLocationPrev);
												$immediate_PrevPayment = $cashProjection->YesThereWasAPayment;
												$immediate_PrevLoan = $cashProjection->YesThereIsALoan;
												
												$immediate_PrevPayment = str_replace(",", "", $immediate_PrevPayment);
												$immediate_PrevLoan = str_replace(",", "", $immediate_PrevLoan);
												
												$_diffInPrevLoanAndPayment = ($immediate_PrevLoan - $immediate_PrevPayment);
												
												
												//echo "<hr/><hr/><hr/>". $_diffInPrevLoanAndPayment . "<hr/><hr/><hr/>";
												
												//echo "<hr/><hr/><hr/> Previous Payments ".$_diffInPrevLoanAndPayment." for month " .$e_month. " (---<hr/><hr/><hr/>";
												
												$paymentsOverPaid[$paymentsOverPaidCounter] = $cashProjectionDetails["limp_month_".$oneToTwelveCounterPayment];	
												
												//echo "<hr/>".$_diffInPrevLoanAndPayment."<hr/>" ;
												if(($_diffInPrevLoanAndPayment > 0) and ($e_month != 12))
												{
													
													$_PaymentBoxLocationNow;
													//echo "<hr/>Prev Paym box location --)> ".$_PaymentBoxLocationPrev."<hr/>";
													//echo "<hr/>Prev loan box T location --)> ".$_LoanBoxLocationPrev."<hr/>";
													//echo "<hr/> Current Month ".$e_month."<hr/>";
													$diff_inLastLoanAndNowPayment_boxLocation =    ($_LoanBoxLocationPrev - $_PaymentBoxLocationNow);
													$diff_inLastPaymentAndNowPayment_boxLocation = ($_PaymentBoxLocationPrev - $_PaymentBoxLocationNow);
													
													if($diff_inLastLoanAndNowPayment_boxLocation < $diff_inLastPaymentAndNowPayment_boxLocation)
													{
														$diffInCalc = $diff_inLastLoanAndNowPayment_boxLocation;
													}
													else
													{
														$diffInCalc = $diff_inLastPaymentAndNowPayment_boxLocation;
													}
													
													// Variable use to made addons Calculation
													$diffInCalc;
													$immediate_PrevPayment;
													$immediate_PrevLoan;
													
													$diffInMonthlyPrevLoanAndPrevPayment = ($immediate_PrevLoan - $immediate_PrevPayment);
													$MonthlyInterest = $cashProjection->calculateMonthlyInterest($diffInMonthlyPrevLoanAndPrevPayment, $diffInCalc);
													$_IntRate = ($interestRate / 100);
													 
													$finalResult = ($MonthlyInterest * $_IntRate);
													//$finalResult = 0;
													
													// $finalResult = number_format($finalResult, 0, '.', ',');
													//echo "<hr/>----- 0> ".$finalResult."<hr/>";
													//echo "<hr/><hr/><hr/><hr/> --) Prev Payment = ".$diffInCalc." month location ".$e_month." of loan ".$immediate_PrevLoan."<hr/><hr/><hr/><hr/>";
													
													 
												}
												else
												{
													$finalResult = 0;
												}
												$paymentsOverPaid[$paymentsOverPaidCounter] = (($paymentsOverPaid[$paymentsOverPaidCounter]) + $finalResult);
												
												$counter_FirstPaymentBeforeOverpaying = ($counter_FirstPaymentBeforeOverpaying + 1);
												$paymentsOverPaidCounter = ($paymentsOverPaidCounter + 1);
											}
											else /*---	The amount you are paying back has NOT reached or over 0	---*/
											{
												
												
												
												
												if($countNumbersOfPayments == 0)
												{
													/* --- 	Get self box location  -----*/
													 $first_payment_box_location = $e_month;
													
													/*---	Call The function to calculate each months interest  (Capital replayment + interest Payable)	---*/
													
													$capitalPLUSinterestPayable_first = $cashProjection->monthlyInterestExpectedPayment( $first_payment_box_location, "", $amountPaidBackWithoutCommas[$e_month], $amountReceiveWithoutCommas, $interestRate);									$calculatedYearInterest[0] = $capitalPLUSinterestPayable_first;
														
													//$calculatedYearInterest[$e_month] = $capitalPLUSinterestPayable_others;
														
													/*---	Control the loop to only call the above function ONES, because the calculation for the fisrt is different from the rest	---*/
													$countNumbersOfPayments = $countNumbersOfPayments + 1 ;
													
												}
												else /*---	Calculate for the REMAINING BOXES that have there values above 0 ---*/
												{
													
													/*---If true it means there has been an earlier payment made ----*/
													if($first_payment_box_location > 0)
													{
														
														/*---	Get self box locations---*/
														$others_payment_box_location = $e_month;
														//echo "<hr/><hr/><hr/> --- > First loan location ". $first_payment_box_location ." ". $others_payment_box_location . " Other loans box location < ----<hr/><hr/><hr/>";
														/*---	Amount paid back	---*/	
														$amountPaidBack_ = $amountPaidBackWithoutCommas[$e_month];
													
														/*---	Call The fuunction to calculate each months interest  (Capital replayment + interest Payable)	---*/
														$capitalPLUSinterestPayable_others = $cashProjection->monthlyInterestExpectedPayment($first_payment_box_location, $others_payment_box_location, $amountPaidBack_, $amountReceiveWithoutCommas, $interestRate);
														
														$calculatedYearInterest[0] = $capitalPLUSinterestPayable_first;
														
														$calculatedYearInterest[$e_month] = $capitalPLUSinterestPayable_others;
														
													}
												}
											}// End of the amount you are paying back has NOT reached or over 0	
										}
										
									}
									else
									{
										
									}
									
									/*---	Add to counter until it gets to 12 ( LOOP CONTROL ) ---*/
									$oneToTwelveCounterPayment = $oneToTwelveCounterPayment + 1; 
								}
							?>
                        
                        
                        
                        
                      <?php
						/*-------------------------------------------------------------------------------
							
							Yearly Interest Calculattion for amount paid back
						
						-------------------------------------------------------------------------------*/
						$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	$_paymentMadeWithoutInterest = 0;
						$_paymentsOverPaid = 0;
						$_array_paymentAmountYrlyCounter2 = 1;
					  	/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
					 	for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
						{
							/*---	
								If Financial Yr is 1, ignore the calculation cos it as already been done via the 12 months one.	
								Just focus on the ones after the first yr of payment
							---*/
							
								
							if ($e_year == 0)
							{
								
								
								$_paymentMadeWithoutInterest = number_format(array_sum($paymentsMadeBeforeLoaning), 0, '.', ''); // fine
								$_paymentsOverPaid = number_format(array_sum($paymentsOverPaid), 0, '.', '');
								$inBtwnOrExactPayBack = number_format($inBtwnOrExactPayBack, 0, '.', '');
								/*
								echo "<hr/>".array_sum($calculatedYearInterest)."<hr/>";
								echo "<hr/>".$_paymentMadeWithoutInterest."<hr/>";
								echo "<hr/>".$_paymentsOverPaid."<hr/>";
								echo "<hr/>".$inBtwnOrExactPayBack."<hr/>";
								*/
								$_TotalYrlyInterst = (array_sum($calculatedYearInterest) + $_paymentMadeWithoutInterest + $_paymentsOverPaid + $inBtwnOrExactPayBack);
								
								$_arrayOfYrlyCalcInterest[$_arrayOfYrlyCalcInterestCounter] = $_TotalYrlyInterst;
								
								
								// Here//////
								$_array_paymentAmountYrly[$_array_paymentAmountYrlyCounter1]["yr_".$_array_paymentAmountYrlyCounter2] =  number_format($_TotalYrlyInterst, 0, '.', '');
								
								
							}
							elseif($e_year > 0)
							{
								// Year 2 and 3 ....
								$upToTheCurrentYr = $e_year;
								
								$loanTakenOut = $cashProjectionDetails['financial_receive'][$e_year]['lir_total_per_yr'];
								
								$yrlyPayBack = $cashProjectionDetails['financial_payment'][$e_year]['lip_total_per_yr'];
								
								$YrlyInterest = $cashProjection->CalculateYrlyInterest($interestRate, $_paymentMadeWithoutInterest, $cashProjectionDetails['financial_receive'], $cashProjectionDetails['financial_payment'],  $upToTheCurrentYr, $yrlyPayBack);
								
                                if($yrlyPayBack == 0)
								{
									$YrlyInterest = 0;
								}
								
								$_array_paymentAmountYrly[$_array_paymentAmountYrlyCounter1]["yr_".$_array_paymentAmountYrlyCounter2] = number_format($YrlyInterest, 0, '.', '');
								
								
								//$_arrayOfYrlyCalcInterest[$_arrayOfYrlyCalcInterestCounter] = $YrlyInterest;
								
							}
							$_array_paymentAmountYrlyCounter2 = ($_array_paymentAmountYrlyCounter2 + 1);
							
							$_interestIncured["years_0".($e_year+1)][$_arrayOfYrlyCalcInterestCounter] = ((number_format($_arrayOfYrlyCalcInterest[$_arrayOfYrlyCalcInterestCounter], 0, '.', '')) -  ($cashProjectionDetails['financial_payment'][($e_year)]['lip_total_per_yr']));
							
							//echo "<hr/><hr/><hr/><hr/><strong>" . array_sum($_interestIncured["years_0".($e_year+1)]) . " = = </strong><hr/><hr/><hr/><hr/>";
							
						}
						$_array_paymentAmountYrlyCounter1 = ($_array_paymentAmountYrlyCounter1 + 1);
						$_arrayOfYrlyCalcInterestCounter = $_arrayOfYrlyCalcInterestCounter + 1;
						
						
						
        }
		//print_r($_array_recieveAmountYrly); 
		//print_r($_array_paymentAmountYrly);
		$_interestIncured;
		
		
	}// end of if
    ?>  
    
