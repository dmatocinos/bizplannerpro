<?php
	// Updated - 08/June/2013
	
	/*------------------------------------------------------------------------------------------*/
	/* All files involved in calculation of the whole software									*/
	/*------------------------------------------------------------------------------------------*/                    
	
	/* Use Web Calc Full PJJ1 19 May 2014 */
	require_once(LIBRARY_PATH . '/web_calc_full.php');
	$oWebcalc = new WebCalcFull();
	$oWebcalc->build();
	$incometax = $oWebcalc->farraynumber($oWebcalc->yearlyincometax);
	
	define("BUDGET", BASE_PATH."/pages/financial-plan/budget" ,true)	;					
						
	include_once(BUDGET."/all_calculations/revenue_calc.php");
		
	include_once(BUDGET."/all_calculations/direct-cost_calc.php");
	 
	include_once(BUDGET."/all_calculations/gross_margin_calc.php");
	
	include_once(BUDGET."/all_calculations/total-expenses_calc.php");
	
	include_once(BUDGET."/all_calculations/loans-and-investments_calc.php");
	
	include_once(BUDGET."/all_calculations/interest-incurred_calc.php");
    
?>


<div class="page taxes" style="display: block; ">
                	<div id="expenseBudget:taxes">
                          
                            <div class="page-body"><div id="expenseBudget:j_id654" class="intro-block expanded">
                  <div class="widget-content">
                     <h3>Enter your estimated rate for income taxes</h3>
                     <p>If your business is profitable in a given year, you will need to pay a variety of taxes on that profit. Enter an overall tax rate to include in your plan. This estimated rate should cover all applicable income taxes — federal, state, local, etc. Don't stress too much about this. This is business planning, not tax planning. It's good to include a reasonable allotment for taxes. If you're not sure what to put, though, a 30% rate is probably close. These taxes typically apply only when you are profitable. Any year without a profit should show zero taxes.<br><br>Note that this rate is only for income taxes. Employee-related taxes like payroll and social welfare taxes are covered in the Personnel Plan table. Other taxes, such as property taxes, are generally best added as miscellaneous expenses.</p>
                 </div></div>
                 
                 
            <div class="line-item selected-expense"><div id="expenseBudget:j_id659" class="header">
                    <h3>Income Tax Rate</h3></div>
                <div class="content">
                     <div class="overall-editor step single-step" style="margin-bottom: 0px;">
                         <div class="tax-rate-percent">
                             <div class="num">1</div>
                             <h4 class="label">Enter your estimated rate for income taxes</h4>
                             
                             <div class="step-inner">
                              <form class="plain_form" method="post">
                                <input id="expenseBudget:input-taxrate" type="text" name="expenseBudget:input-taxrate" value="<?php echo $incomeTaxRate;?>" class="decimals-three" maxlength="7" />
                                 	<span class="percent">%</span>
                                 	<div class="x-clear"></div><br/>
                                 <button type="submit" name="update_burden_rate">Update Income Tax Rate</button>
                               </form>
                                  <div class="x-clear"></div> <br/>
                                 
                                 <!--
                                  <p style="padding-top: 20px;">Here are your estimated income taxes, based on the rate above:</p>
                                 <div class="x-clear"></div>
                                 
                                 
                                 <div id="expenseBudget:taxtable">
                                       <div class="expense financial-table period-year financial-year-editor">
                                           <div class="head">
                                               <div class="row">
                                               	<?php 
													$yrsOfFinancialForecast = $expenditure->financialYear();
													for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
													 {?>	
														<div class="column column-year">
															<div class="td">FY<?php echo $yrsOfFinancialForecast[$e_yr]; ?></div>
														</div>
													 <?php } ?>   
                             					</div>
                                           </div>
                                           <div class="body">
                                               <div class="row values">
                                                   
                                                   <?php
                                                   	for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
													{
														//echo $_SESSION['sessionOfarrayOfYrlyCalcInterest'];	
													}
													
													
													// Define arrays 
													
													$array_totalExpenses = array();
													$array_grossMargin = array();
													$array_estimatedIncomeTax = array();
													$array_eachYrEstimatedIncomeTax = array();
													
													//echo $incomeTaxRate;
													
													/*--- Get Array of Interest Incured	---*/
													if(isset($array_interestIncured)){$array_interestIncured =  $array_interestIncured;}
													
													
													/*--- Get Array of total Expenses	---*/
													if(isset($allExpense)){$array_totalExpenses = $allExpense;}
													
													/*--- Get Array of Gross Margin	---*/
													if(isset($grossMargin)){$array_grossMargin = $grossMargin;}
													
													//loop through this for number of years
													for($e_year = 0; $e_year < count($yrsOfFinancialForecast); $e_year++ )
													{
														/*---	Remove all comas for proper calculation---*/
														
														
														if(empty($array_interestIncured))
														{
															/*---	create arrays based on the number of financial year and set value to 0	---*/
															for($e_year_at = 0; $e_year_at < count($yrsOfFinancialForecast); $e_year_at++ )
															{
																$array_interestIncured[$e_year_at] = 0;
															}
														//	$array_interestIncured[$e_year] = 	str_replace(",", "", $array_interestIncured[$e_year]);
														}
														if(count($array_totalExpenses) > 0)		{$array_totalExpenses[$e_year] = 	str_replace(",", "", $array_totalExpenses[$e_year]);}
														if(count($array_grossMargin) > 0)		{$array_grossMargin[$e_year] = 	str_replace(",", "", $array_grossMargin[$e_year]);}
														
														
														if((count($array_interestIncured) > 0) and (count($array_totalExpenses) > 0) and (count($array_grossMargin) > 0))
														{
															$array_estimatedIncomeTax[$e_year] = ($array_grossMargin[$e_year] - $array_totalExpenses[$e_year] - $array_interestIncured[$e_year]); 
															
															$array_eachYrEstimatedIncomeTax[$e_year] = (($array_estimatedIncomeTax[$e_year] * $incomeTaxRate) / 100);
															
															$array_eachYrEstimatedIncomeTax[$e_year] = number_format($array_eachYrEstimatedIncomeTax[$e_year], 0, '.', ',');
														}
														else
														{
															$array_eachYrEstimatedIncomeTax[$e_year] = 0;
														}
														if($array_eachYrEstimatedIncomeTax[$e_year] < 0)
														{
															$array_eachYrEstimatedIncomeTax[$e_year] = 0;
														}
														?>
                                                        <div class="column column-year">
															<div class="td">
															  	<p class="display-only"><?php echo $incometax[$e_year+1] ?></p>
															</div>
                                                        </div>
													<?php 
													} 	
														
													?>
                	    
                                               </div>
                                           </div>
                                       </div>
                                  </div> --> <!--end #expenseBudget -->
                             </div><!--end .step-inner-->
                                    
                                    
                                    
                                    
                             <div class="x-clear"></div>
                         </div>
                     </div>
                 </div>
            </div><!--end .line-item selected-expense-->
                            </div>
                            <div class="page-footer">
                                <div class="left">

                                </div>
                                <div class="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="button button-primary continue">
                                        <span class="button-cap">
                                            <span>I'm Done</span>
                                        </span></a>
                                </div>
                            </div>
                            <span class="clear"></span><br/>
                        </div>
                    </div>
                    
                    
