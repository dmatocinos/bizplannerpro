<?php
	
	

	$whereLoanInvest = "loan_investment.li_id = $getLoanInvestId" ;
	
	$allCashProjections = $cashProjection->getAllCashProjections($whereLoanInvest, "", "");
	
	$latestLoanInvestName = $allCashProjections[0]['loan_invest_name'];
	
	$type_of_funding = $allCashProjections[0]['type_of_funding'];
	
	//highlight_string(var_export($allCashProjections, true));
	
	$loan_invest_interest_rate = $allCashProjections[0]['loan_invest_interest_rate']; //$cashProjection->startMonth." ".$cashProjection->startFinancialYear;
	$loan_invest_years_to_pay  =  $allCashProjections[0]['loan_invest_years_to_pay'];
	$loan_invest_pays_per_years =  $allCashProjections[0]['loan_invest_pays_per_years'];
	
	$latestListOf12Months = $cashProjection->twelveMonths("", "");
	
	$currency = $cashProjection->currencySetting;
	
	
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE Loan Investment DETAILS
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['upate_projection']))
	{
		
		if($cashProjection->_updateProjection($getLoanInvestId))
		{
			$cashProjection->global_func->redirect($_SERVER['PHP_SELF']."?projection=_loan_invest");	
		}
		
		else
		{
			$cashProjection->allmsgs = "Expenditure could not be updated. Please try again.";
			$cashProjection->color = "red";
		}
	}
	
	
	// If delete button
	else if(isset($_POST['delete_expenditure']))
	{
		//echo $getLoanInvestId;
		if($cashProjection->deleteLoanInvestProjection($getLoanInvestId))
		{
			$newExpenditureUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?projection=_loan_invest";
			$global_func->redirect($newExpenditureUrl);
		}
		else
		{
			$cashProjection->allmsgs = "There was an error deleteing expenditure. Please contact your administartor";
			$cashProjection->color = "red";
		}
	}
	/*-----------------------------------------------------------------------------------------------*/

?>

<p>&nbsp;</p>
<style>
.tableBuilder .selected-expense .period-month input.financial-period-value {
	width: 45px;
}

</style>
				

                                                    
<div id="personnel:j_id258">
    <div id="expense-budget-list-wrapper"><div id="personnel:expense-budget-list" class="dim-action-expense-budget-list">
       
        <form method="post" action="" >	
            <div id="personnel:j_id266:expense-item" class="expense-item selected-expense">
                    <div class="expense-budget-edit" rel="24fec7e6-b2f7-4a75-bfa9-787fe0cdb1df">
                        <div class="item-header">
                            <h3>Tell us about this funding source</h3>
                            <button class="delete_e"   onClick="if(confirm('Are you sure you want to remove this expenditure?')){return true }else{ return false}" type="submit"  name="delete_expenditure">Delete</button>
                             
                        </div>
                       
                        <div class="expense-budget-entryMethod">
                            <div class="step expense-name">
                                <div class="num">1</div><h4 class="label">What do you want to call this funding source?</h4>
                                    <input type="text" name="latestLoanInvestName" value="<?php echo $latestLoanInvestName;?>" maxlength="255" />
                            </div>
                            
                            <div class="x-clear"></div>
                        </div>
                        <div class="expense-budget-entryMethod">
                            <div class="step expense-name">
                                <div class="num">2</div><h4 class="label">What type of funding is this?</h4>
                                    <input type="hidden" name="type_of_funding" value="Investment" /> Investment
                                    
                                    <!-- 
                                    <input type="radio" name="type_of_funding" value="Loan" <?php echo ($type_of_funding=="Loan"?"checked=checked":""); ?> /> Loan
                                    <input type="radio" name="type_of_funding" value="Investment" <?php echo ($type_of_funding=="Investment"?"checked=checked":""); ?> /> Investment
                                     -->
                            </div>
                            
                            <div class="x-clear"></div>
                        </div>
                       
                        <?php include ("includes_files/receive_monthly_project.php");?>
                        
                         <!--?php include ("includes_files/payment_monthly_project.php");?-->
                        
                        
                        <div class="expense-budget-entry">
                            
                            <div class="expense-budget-entry-body">
                                    <div class="overall-editor" style="margin-bottom: 0px;">
                                        <div class="step">
                                            <div class="num">4</div>
                                            <h4 class="label">What interest rate do you expect to pay for this funding?</h4>
                                            <div class="step-inner">
                                            <input type="text" name="loan_invest_interest_rate" value="<?php echo $loan_invest_interest_rate;?>" class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14" > %
                                            </div>
                                        </div>
                                        <div class="x-clear"></div>
										<div class="step">
                                            <div class="num">5</div>
                                            <h4 class="label">How many years do you expect to pay for this funding?</h4>
                                            <div class="step-inner">
                                            <input type="text" name="loan_invest_years_to_pay" value="<?php echo $loan_invest_years_to_pay;?>" class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14" >
                                            </div>
                                        </div>
                                        <div class="x-clear"></div>
										<div class="step">
                                            <div class="num">6</div>
                                            <h4 class="label">How many payments do you expect to do in a year?</h4>
                                            <div class="step-inner">
                                            <input type="text" name="loan_invest_pays_per_years" value="<?php echo $loan_invest_pays_per_years;?>" class="financial-period-value active-input numeric decimals-one nonnegative" maxlength="14" >
                                            </div>
                                        </div>
                                        <div class="x-clear"></div>
                                    </div>
                                <div class="x-clear"></div>

                                
                                
                                <div class="step">
                                    <div class="step-inner">
                                            <a class="done-editing button button-primary button-submit" href="Javascript:void(0);">
                                                <span class="button-cap"><span>
                                                    <button name="upate_projection" class="update_employee" type="submit">Save and Continue</button>
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