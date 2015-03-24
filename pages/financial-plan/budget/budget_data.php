<?php
        /*-------------------------------------------------------------
			if $expenditure object returns true
		--------------------------------------------------------------*/
		$expenditure = new expenditure_lib();
		$employee = new employee_lib();
		$allExpDetails = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
		$allPurDetails  = $expenditure->getAllMajorPurchaseDetails("", "", "");

		


		$allEmpDetails = $employee->getAllEmployeeDetails2("", "", ""); // All employees
		$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation


		if($allExpDetails)
		{?>
			<div id="personal_table">
			 <div class="edit_section">
				<div class="widget_content">
					<h3>Budget Table</h3>
					<div class="clearboth"></div>
				</div>
				<div class="click-to-edit" >
				  
					<div class="tuck">
						<a href="<?php echo $_SERVER['PHP_SELF'].'?budget=_expenditure'?>">
							<div class="flag">
							<span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
							</div>
						</a>
					</div>
				  
			   </div>
			</div><!--end .edit_section-->
			<?php include_once(BASE_PATH."/pages/financial-plan/budget/budget_table.php"); ?>
			<div class="x-clear"></div>
			</div><!--end #personal_table-->
			<p>&nbsp;</p><p>&nbsp;</p>
			<?php
			
			if($allEmpDetails)
			{ 
				include_once(BASE_PATH."/pages/financial-plan/budget/monthly_graph.php");?>
				
				<p>&nbsp;</p><p>&nbsp;</p>
				<div class="widget_content">
					<h3>Expenses by Year</h3>
					<div class="clearboth"></div>
				</div><br/>
				<?php
				
				if(isset($eachYear))
				{}
				else{ $eachYear = array("");}

				$data = array();
				$counter = 1;
				foreach ($allExpDetails[0]['financial_status'] as $eachFinStat)
				{
					$data["FY " .$eachFinStat['financial_year']] = $oWebcalc->yearlytotaloperatingexpenses[$counter++];
				
				}
				
				
				// Yearly graph
				$graphImageName = "yearly_expenses_graph" . $_SESSION['bpId'] . ".png";
				$bar_width = 70;
				$xAxisFont = 13;
				$xAxisPostion = 15;
				$unit = html_entity_decode($employee->currencySetting);
				$unitPosition = "before";
				//$drawGraph = $draw->_graph($eachYear, $graphImageName, $bar_width, $xAxisFont, $xAxisPostion,$unitPosition ,$unit);
				//echo " <img src='".BASE_URL."/".$graphImageName."' />" ;

				//$imgb64 = GraphHandler::getImgB64($eachYear,  $unit); //	getImgB64($data, $valueformat)
				//echo '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.$imgb64.'" />';
				$imgb64 = GraphHandler::getImgB64($data,  $unit, $graphImageName, 600); //	getImgB64($data, $valueformat)
				echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageName . '" />';
				
				
			}
		
		}
		/*-----else if $expenditure object returns false-------*/
		else
		{
	?>
	<div class="section">
				 <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?budget=_expenditure">
					<div class="widget text clean-slate">​
							<h3>Buget Table</h3>
							<p>Launch the step-by-step table builder</p>
					</div>
				</a>
			</div>
	
	<?php }// ---- 	end of  if $expenditure -------------------?>
	
