<?php
	
	$getMaxMajorPurchaseId = $expenditure->maxMajorPurchaseId;
	
	
	if(isset($_GET['edit_new_majorPurchaseID'])){
		$getMaxMajorPurchaseId = $_GET['edit_new_majorPurchaseID'];
	}
	
	
	 
	$whereMP = "major_purchases.mp_id = $getMaxMajorPurchaseId" ;
	
	$latestMpDetails = $expenditure->getAllMajorPurchaseDetails($whereMP, "", ""); 
	
	
	$latestMajorPurchaseName = $latestMpDetails[0]['mp_name'];
	
	$selectedStartDate = $latestMpDetails[0]['mp_date']; //$expenditure->startMonth." ".$expenditure->startFinancialYear;
	
	$mpPrice = $latestMpDetails[0]['mp_price']; //$expenditure->startMonth." ".$expenditure->startFinancialYear;
	
	
	$latestListOf12Months = $expenditure->twelveMonthsPlusTwoYrs("", "");
	
	$currency = $expenditure->currencySetting;
	
	
	$latestMpType = $latestMpDetails[0]['mp_depreciate'];
 	if($latestMpType == 1){$avgeDepreciation_yes = "checked='checked'"; $avgeDepreciation_no = "";}
	else{$avgeDepreciation_no = "checked='checked'"; $avgeDepreciation_yes = "";}

	
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE MAJOR PURCHASES
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['upate_m_purhcase']))
	{
		
		if($expenditure->updateMajorPurchase($getMaxMajorPurchaseId))
		{
			$expenditure->global_func->redirect($_SERVER['PHP_SELF']."?budget=_expenditure&pgIndex=1");	
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
		//echo $getMaxMajorPurchaseId;
		if($expenditure->deleteExpenditure($getMaxMajorPurchaseId))
		{
			$newExpenditureUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?budget=_expenditure&pgIndex=1";
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
                            <h3>Tell us about this major purchase</h3>
                            <button class="delete_e"   onClick="if(confirm('Are you sure you want to remove this expenditure?')){return true }else{ return false}" type="submit"  name="delete_expenditure">Delete</button>
                             
                        </div>
                       
                        <div class="expense-budget-entryMethod">
                            <div class="step expense-name">
                                <div class="num">1</div><h4 class="label">What do you want to call this major purchase?</h4>
                                    <input type="text" name="latestMajorPurchaseName" value="<?php echo $latestMajorPurchaseName;?>" maxlength="255" />
                            </div>
                            
                            <div class="x-clear"></div>
                        </div>
                        <div class="expense-budget-entry">
                            
                            <div class="expense-budget-entry-body">
                                    <div class="overall-editor" style="margin-bottom: 0px;">
                                        <div class="step">
                                            <div class="num">
2</div>
                                            <h4 class="label">What's the price of this purchase?</h4>
                                            <div class="step-inner">
                                                <span class="currency">
<?php echo $currency; ?></span><input id="personnel:j_id266:sameAmount" type="text" name="mpPrice" value="<?php echo $mpPrice;?>" class="active-input numeric" maxlength="14" >



<span id="personnel:j_id266:error" class="rich-message"><span class="rich-message-label"></span></span>
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="num">
3</div>
                                            <h4 class="label">When will you make this purchase?</h4>
                                            <div class="step-inner">
                                            <input type="text" name="selectedStartDate" value="<?php echo $selectedStartDate;?>" readonly="readonly" class="numeric" maxlength="14" >
                                            
                                            <select name="month_year_date" size="1" >
                                                <option value=''>Date</option>
                                                    <?php	
                                                        foreach($latestListOf12Months as $eachListOf12Months)
                                                        {
                                                            ?>	
                                                            <option value='<?php echo $eachListOf12Months; ?>'><?php echo $eachListOf12Months; ?></option>
                                                        
                                                        <?php }?>
                                                
                                                
                                            </select>
                                            </div>
                                        </div>
                                        <div class="x-clear"></div>
                                    </div>
                                <div class="x-clear"></div>
    
                                    <div class="step">
                                        <div class="num">4</div>
                                        <h4 class="label">How do you want to depreciate this purchase?</h4>
                                        <div class="step-inner">
                                           
                                            <div class="expense-budget-radio">
                                                <a class="radio-link"  >
                                                   <input type="radio" name="depreciate_type" id="notacontractor" value="1" <?php echo $avgeDepreciation_yes; ?> >
                                                 </a>
                                                 <label for="notacontractor">Use the Average Depreciation Period option</label>
                                                	 <div class="x-clear"></div>
                                                     
                                                <a class="radio-link"  >
                                                    <input type="radio" name="depreciate_type" id="isacontractor" value="0" <?php echo $avgeDepreciation_no; ?> >
                                                </a>
                                                <label for="isacontractor">Do not depreciate this purchase</label>
                                            </div>
                                        </div>
                                    </div>
                                
                                <div class="step">
                                    <div class="step-inner">
                                            <a class="done-editing button button-primary button-submit" href="Javascript:void(0);">
                                                <span class="button-cap"><span>
                                                    <button name="upate_m_purhcase" class="update_employee" type="submit">Update</button>
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
