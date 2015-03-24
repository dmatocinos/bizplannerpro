<?php
	$whereEmployee = "employee.emplye_id = $getEmployeeId" ;
	
	$latestEmpDetails = $employee->getAllEmployeeDetails2($whereEmployee, "", "");
	
	$latestEmplyeeName = $latestEmpDetails[0]['emplye_name'];
	
	$selectedStartDate = $latestEmpDetails[0]['employee_start_date']; //$employee->startMonth." ".$employee->startFinancialYear;
	
	
	$latestListOf12Months = $employee->twelveMonths("", "");
	
	$currency = $employee->currencySetting;
	
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

	$latestEmployeeType = $latestEmpDetails[0]['emplye_type'];
 	if($latestEmployeeType == "regular"){$regular_employee = "checked='checked'"; $contract_employee = "";}
	else{$contract_employee = "checked='checked'"; $regular_employee = "";}
	
	/*----------------------------------------------------------------------------------------------------
		EDIT / DELETE EMPLOYEE DETAILS
	*-----------------------------------------------------------------------------------------------------*/
	
	// if update button
	if(isset($_POST['upate_employee']))
	{
		
		if($employee->updateEmployee($getEmployeeId))
		{
			$employee->global_func->redirect($_SERVER['PHP_SELF']."?personnel=_employee");	
		}
		
		else
		{
			$employee->allmsgs = "Employee could not be updated. Please try again.";
			$employee->color = "red";
		}
	}
	
	
	// If delete button
	else if(isset($_POST['delete_employee']))
	{
		//echo $getEmployeeId;
		if($employee->deleteEmployee($getEmployeeId))
		{
			$newEmployeeUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?personnel=_employee";
			$global_func->redirect($newEmployeeUrl);
		}
		else
		{
			$employee->allmsgs = "There was an error deleteing employee. Please contact your administartor";
			$employee->color = "red";
		}
	}
	/*-----------------------------------------------------------------------------------------------*/

?>

<p>&nbsp;</p>
				

                                                    
<div id="personnel:j_id258">
    <div id="expense-budget-list-wrapper"><div id="personnel:expense-budget-list" class="dim-action-expense-budget-list">
            
            <!--<div id="expense-budget-reorder" class="list-reorder x-hide-display"><div id="personnel:expense-budget-reorder">
                    <ul>
                            <li rel="24fec7e6-b2f7-4a75-bfa9-787fe0cdb1df">
                                <div class="line-item">
                                    <div class="drag-handle"></div>
                                    <div class="line-item-name">New Employee</div>
                                </div>
                            </li>
                    </ul></div>
            </div>-->
                                        <a href="#" id="personnel:j_id266:j_id267" name="personnel:j_id266:j_id267" onclick="bpo.blockUI();bpo.setTableListItemBusy($j(this).next('div.expense-item'));;A4J.AJAX.Submit('personnel',event,{'oncomplete':function(request,event,data){bpo.unblockUI()},'similarityGroupingId':'expenseEditEvent','parameters':{'personnel:j_id266:j_id267':'personnel:j_id266:j_id267'} ,'eventsQueue':'personnelEditEvent','actionUrl':'/plan'} );return false;" style="display: none;">Edit</a>
                                        
                                        <form method="post" action="" >	
                                        	<div id="personnel:j_id266:expense-item" class="expense-item selected-expense">
                                                    <div class="expense-budget-edit" rel="24fec7e6-b2f7-4a75-bfa9-787fe0cdb1df">
                                                        <div class="item-header">
                                                            <h3>Tell us about this employee</h3>
                                                            <button class="delete_e"   onClick="if(confirm('Are you sure you want to remove this employee?')){return true }else{ return false}" type="submit"  name="delete_employee">Delete</button>
                                                             
                                                        </div>
                                                        <div class="expense-budget-entryMethod">
                                                            <div class="step expense-name">
                                                                <div class="num">
    1</div>
    															
                                                                <h4 class="label">What do you want to call this person or role?</h4>
                                                                	<input type="text" name="latestEmplyeeName" value="<?php echo $latestEmplyeeName;?>" maxlength="255" />
                                                                
                                                            </div>
                                                            <!--
                                                            <div class="step">
                                                                <div class="num">
    2</div>
                                                                <h4 class="label">How will you pay this person?</h4>
                                                                <div class="step-inner"><a class="radio-link" href="#" id="personnel:j_id266:j_id294" name="personnel:j_id266:j_id294" onclick="bpo.blockUI();$j('.expense-budget-entry').fadeTo(0,0.4);;A4J.AJAX.Submit('personnel',event,{'oncomplete':function(request,event,data){bpo.unblockUI();},'similarityGroupingId':'personnel:j_id266:j_id294','parameters':{'ajaxSingle':'personnel:j_id266:j_id294','personnel:j_id266:j_id294':'personnel:j_id266:j_id294'} ,'eventsQueue':'personnelEvent','actionUrl':'/plan'} );return false;">
                                                                            <input type="radio" name="entrymethod" id="same_amount" checked="checked"></a>
                                                                    <label for="same_amount">Set salary or amount</label><a class="radio-link" href="#" id="personnel:j_id266:j_id300" name="personnel:j_id266:j_id300" onclick="bpo.blockUI();$j('.expense-budget-entry').fadeTo(0,0.4);;A4J.AJAX.Submit('personnel',event,{'oncomplete':function(request,event,data){bpo.unblockUI();},'similarityGroupingId':'personnel:j_id266:j_id300','parameters':{'personnel:j_id266:j_id300':'personnel:j_id266:j_id300','ajaxSingle':'personnel:j_id266:j_id300'} ,'eventsQueue':'personnelEvent','actionUrl':'/plan'} );return false;">
                                                                            <input type="radio" name="entrymethod" id="different_amounts"></a>
                                                                    <label for="different_amounts">Different amounts over time</label>
                                                                </div>
                                                            </div>
                                                            -->
                                                            
                                                            
                                                            <div class="x-clear"></div>
                                                        </div>
                                                        <div class="expense-budget-entry">
                                                        	
                                                            <div class="expense-budget-entry-body">
                                                                    <div class="overall-editor" style="margin-bottom: 0px;">
                                                                        <div class="step">
                                                                            <div class="num">
    2</div>
                                                                            <h4 class="label">How much will you pay them?</h4>
                                                                            <div class="step-inner">
                                                                                <span class="currency">
    <?php echo $currency; ?></span><input id="personnel:j_id266:sameAmount" type="text" name="personnel:j_id266:sameAmount" value="<?php echo $latestMonthlyOrYearlyPayment;?>" class="active-input numeric" maxlength="14" >
    
    <select name="how_you_pay" class="entry-period" size="1">	
    
        <option value="per_month" <?php echo $selectMn; ?> >Per Month</option>
        <option value="per_year" <?php echo $selectYr; ?> >Per Year</option>
    </select>

<span id="personnel:j_id266:error" class="rich-message"><span class="rich-message-label"></span></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="step">
                                                                            <div class="num">
    3</div>
                                                                            <h4 class="label">When will they start?</h4>
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
                                                                    <h4 class="label">Is this a regular employee or contract hire?</h4>
                                                                    <div class="step-inner">
                                                                        <p class="field-description">The burden rate in the next tab (which covers payroll taxes and benefits) will not be applied to contract workers.</p>
                                                                        <div class="expense-budget-radio">
                                                                            <a class="radio-link" href="#" >
                                                                               <input type="radio" name="employ_type" id="notacontractor" value="regular" <?php echo $regular_employee; ?> >
                                                                             </a>
                                                                             <label for="notacontractor">Regular employee</label>
                                                                            
                                                                            <a class="radio-link" href="#" >
                                                                                <input type="radio" name="employ_type" id="isacontractor" value="contract" <?php echo $contract_employee; ?> >
                                                                            </a>
                                                                        	<label for="isacontractor">Contract hire</label>
                                                                    	</div>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="step-inner">
                                                                    	    <a class="done-editing button button-primary button-submit" href="Javascript:void(0);">
                                                                            	<span class="button-cap"><span>
                                                                                	<button name="upate_employee" class="update_employee" type="submit">Update</button>
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