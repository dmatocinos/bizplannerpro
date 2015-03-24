<?php
	$whereEmployee = "expenditure.exp_id = $getExpenditureId" ;
	
	$latestEmpDetails      = $expenditure->getAllExpenditureDetails($whereEmployee, "", "");
	$latestEmplyeeName     = $latestEmpDetails[0]['expenditure_name'];
	$selectedStartDate     = $latestEmpDetails[0]['expenditure_start_date']; //$expenditure->startMonth." ".$expenditure->startFinancialYear;
    $expected_change       = $latestEmpDetails[0]['expected_change'];
    $percentage_of_change  = $latestEmpDetails[0]['percentage_of_change'];
	$latestListOf12Months  = $expenditure->twelveMonths("", "");
	$currency              = $expenditure->currencySetting;
	$latestIsItPayPerPrice = $latestEmpDetails[0]['financial_status'][0]['pay_per_year'];

	if($latestIsItPayPerPrice == 1)
	{
		 $selectYr = "selected='selected'"; $selectMn ="";
		 
		 // assign the yearly amount of the last of the 3 to $latestMonthlyOrYearlyPayment beacuse you want that to be displayed on the form
		 $latestMonthlyOrYearlyPayment = $latestEmpDetails[0]['financial_status'][2]['total_per_yr']; 
		
	}
	else
	{
		$selectMn = "selected='selected'"; $selectYr="";
		
		// assign the last month's amount to $latestMonthlyOrYearlyPayment 
		//because it will have the monthly payment amount even if the first has zero
		$latestMonthlyOrYearlyPayment = $latestEmpDetails[0]['month_12'];
	}

	
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE EMPLOYEE DETAILS
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['upate_expenditure']))
	{
		
		if($expenditure->updateExpenditure($getExpenditureId))
		{
			$expenditure->global_func->redirect($_SERVER['PHP_SELF']."?budget=_expenditure");	
		}
		
		else
		{
			$expenditure->allmsgs = "Expenditure could not be updated. Please try again.";
			$expenditure->color = "red";
		}
	}
	
	
	// If delete button
	else if(isset($_POST['delete_expenditure']))
	{
		//echo $getExpenditureId;
		if($expenditure->deleteExpenditure($getExpenditureId))
		{
			$newExpenditureUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure";
			$global_func->redirect($newExpenditureUrl);
		}
		else
		{
			$expenditure->allmsgs = "There was an error deleteing expenditure. Please contact your administartor";
			$expenditure->color = "red";
		}
	}
	/*-----------------------------------------------------------------------------------------------*/

?>

<p>&nbsp;</p>
				

                                                    
<div id="personnel:j_id258">
    <div id="expense-budget-list-wrapper"><div id="personnel:expense-budget-list" class="dim-action-expense-budget-list">
        <form method="post" action="" >	
            <div id="personnel:j_id266:expense-item" class="expense-item selected-expense">
                <div class="expense-budget-edit" rel="24fec7e6-b2f7-4a75-bfa9-787fe0cdb1df">
                    <div class="item-header">
                        <h3>Tell us about this expense</h3>
                        <button class="delete_e"   onClick="if(confirm('Are you sure you want to remove this expenditure?')){return true }else{ return false}" type="submit"  name="delete_expenditure">Delete</button>
                             
                     </div>
                       
                    <div class="expense-budget-entryMethod">
                        <div class="step expense-name">
                            <div class="num">1</div>
                            <h4 class="label">What do you want to call this expense?</h4>
                            <input type="text" name="latestEmplyeeName" value="<?php echo $latestEmplyeeName;?>" maxlength="255" />
                         </div>
                            
                         <div class="x-clear"></div>
                     </div>
                     <div class="expense-budget-entry">
                          <div class="expense-budget-entry-body">
                               <div class="overall-editor" style="margin-bottom: 0px;">
                                    <div class="step">
                                         <div class="num">2</div>
                                         <h4 class="label">How much is it?</h4>
                                         <div class="step-inner">
                                            <span class="currency"><?php echo $currency; ?></span>
                                            <input id="personnel:j_id266:sameAmount" type="text" name="personnel:j_id266:sameAmount" value="<?php echo $latestMonthlyOrYearlyPayment;?>" class="active-input numeric" maxlength="14" >

                                            <select name="how_you_pay" class="entry-period" size="1">	
                                                <option value="per_month" <?php echo $selectMn; ?> >Per Month</option>
                                                <option value="per_year" <?php echo $selectYr; ?> >Per Year</option>
                                            </select>

                                            <span id="personnel:j_id266:error" class="rich-message"><span class="rich-message-label"></span></span>
                                         </div>
                                     </div>
                                     <div class="step">
                                         <div class="num">3</div>
                                         <h4 class="label">When does it start?</h4>
                                         <div class="step-inner">
                                            <input type="text" name="selectedStartDate" value="<?php echo $selectedStartDate;?>" readonly="readonly" class="numeric" maxlength="14" >
                                           
                                            <select name="month_year_date" size="1" >
                                                <option value=''>Date</option>
                                                <?php foreach($latestListOf12Months as $eachListOf12Months) : ?>	
                                                   <option value='<?php echo $eachListOf12Months; ?>'><?php echo $eachListOf12Months; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                     </div>
                                     <div class="step">
                                         <div class="num">4</div>
                                         <h4 class="label">Expected Price Change Due to Inflation</h4>
                                         <div class="step-inner">
                                            <select name="expected_change" size="1" >
                                                <option value='increase'>Increase</option>
                                                <option value='decrease'>Decrease</option>
                                            </select>
                                        </div>
                                     </div>
                                     <div class="step">
                                         <div class="num">5</div>
                                         <h4 class="label">Forecasted Percentage Change Due to Inflation</h4>
                                         <div class="step-inner">
                                            <input type="text" name="percentage_of_change" value="<?php echo $percentage_of_change;?>" class="numeric" maxlength="3" >
                                            <span class="currency">&nbsp;%</span>
                                        </div>
                                     </div>
                                     <div class="x-clear"></div>
                                </div>
                                <div class="x-clear"></div>
                                <div class="step">
                                    <div class="step-inner">
                                            <a class="done-editing button button-primary button-submit" href="Javascript:void(0);">
                                                <span class="button-cap"><span>
                                                    <button name="upate_expenditure" class="update_employee" type="submit">Update</button>
                                                </span></span>
                                            </a>
                                    </div>
                                </div>
                                <div class="x-clear"></div>
                            </div>
                            
                        </div>
                        <script type="text/javascript">
                            bpo.widgetPage.personnel.initEditor();
                        </script>
                    </div>
                    
                    </div>
                  </form>
                </div>
            </div>
        </div>
