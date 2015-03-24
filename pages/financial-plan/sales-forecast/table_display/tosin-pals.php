  <?php                
                             
	$sales = new sales_forecast_lib();
	$allSalesDetails = $sales->getAllSales("", "", "");
	$counter = 0;
	
	
	
	foreach($allSalesDetails as $expDetails)
	{ 
			$totaSaleCounter = 0;
			
			for($i=0; $i< count($expDetails['financial_status']); $i++)
			{
				 $arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
			}
			$counter = $counter+1;
			
	}// end foreach  
	 $revenue = $arraySalesSummation;
	
	?>
      <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
        <div class="row row-header singleline">
             <span class="cell label column-0 singleline">
                  <p class="overflowable"> </p>
            </span>
    			<!------------------------------------------
                   Years display
                ------------------------------------------>
                  <?php // loop through and pick out the years
		   			
					$financialYearSF = $sales->startFinancialYear;
					$financialYearSF = $financialYearSF + 1;
			
					foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
					{?>
						<span class="cell data column-1 singleline">
							  <p class="overflowable">FY<?php echo $financialYearSF; ?></p>
						</span>
					<?php $financialYearSF = $financialYearSF+1;
					}
					?>
					<div class="x-clear"></div>
				</div><!--end .singleline-->
         	<!------------------------------------------
               REVENUE
            ------------------------------------------>
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Revenue</p>
                  </span>
                <?php
				$totalSalesCounter = 0;
				foreach($arraySalesSummation as $sumOfAllSales)
                {
					 ?><span class="cell data column-1 singleline">
                    	<?php $totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));?>
                    	<?php $totalSales_format[$totalSalesCounter] = number_format(array_sum($sumOfAllSales), 0, '.', ',');?>
                    <p class="overflowable"><?php echo $sales->defaultCurrency.$totalSales_format[$totalSalesCounter];  ?> </p>
                    </span>
                	<?php 
               		$totalSalesCounter = $totalSalesCounter + 1;
                }
			   ?>
                <div class="x-clear"></div>
             </div><!--end .singleline-->
      </div><!---end .preview-table-->
       
	
     <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
    
		<?php
		foreach($allSalesDetails as $expDetails)
		{
			for($i=0; $i< count($expDetails['financial_status']); $i++)
            {
                 $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
            }
            $counter = $counter+1; 
        }// end foreach ?>    	
        
         <!------------------------------------------
           TOTAL DIRECT COST SECTION
         ------------------------------------------>
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Direct Cost</p>
                  </span>
                <?php
				$totalCostCounter = 0;
                foreach($arrayCostSummation as $sumOfAllCost)
                {
                    ?><span class="cell data column-1 singleline">
                    	<?php $totalDirectCost[$totalCostCounter] = (array_sum($sumOfAllCost));?>
                    	<?php $totalDirectCost_format[$totalCostCounter] = number_format(array_sum($sumOfAllCost), 0, '.', ',');?>
                        <p class="overflowable"><?php echo $sales->defaultCurrency.$totalDirectCost_format[$totalCostCounter];  ?>  </p>	
                    </span>
                <?php 
				$totalCostCounter = $totalCostCounter + 1;	
				}
               ?>
              <div class="x-clear"></div>
             </div><!--end .singleline-->


 	 </div><!---end .preview-table-->
   <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
        <?php include_once(BASE_PATH."/pages/financial-plan/sales-forecast/table_display/gross_margin.php"); ?>
   </div><!--end of .widgetForm-->
  



	<?php
	/*--------------------------------------------------------------------------------
					EXPENSES TABLE
	----------------------------------------------------------------------------------*/
	$expenditure = new expenditure_lib();
	$employee = new employee_lib();
	$allExpDetails = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
	$allEmpDetails = $employee->getAllEmployeeDetails2("", "", ""); // All employees
	$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation
	if($allExpDetails)
	{
		include_once(BASE_PATH."/pages/financial-plan/budget/budget_table.php");
	}
	/*-------------	END BUDGET TABLE DATA	-------------------------------------------*/

?>

	<div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
     <!------------------------------------------
       OPERATNG INCOME 
     ------------------------------------------>
         <div class="row row-group_footer singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Operatng Income</p>
              </span>
            <?php
            $totalCostCounter = 0;
			
            foreach($arrayCostSummation as $sumOfAllCost)
            {
				$operatingIncome[$totalCostCounter] = ($allExpense[$totalCostCounter] - $grossMargin[$totalCostCounter]);
                ?><span class="cell data column-1 singleline">
                    <?php //$allExpense[$totalCostCounter;  ?>
                    <p class="overflowable">(<?php echo $sales->defaultCurrency.number_format($operatingIncome[$totalCostCounter], 0, '.', ','); ?>)  </p>	
                </span>
            <?php 
            $totalCostCounter = $totalCostCounter + 1;	
            }
           ?>
          <div class="x-clear"></div>
        </div><!--end .singleline-->
	 </div><!--end of .widgetForm-->
<br/><br/>

<?php

	/*-----------------------------------------------------------------------------------
					MONTHLY GRAPH
	------------------------------------------------------------------------------------*/
	if($allEmpDetails)
	{ 
		include_once(BASE_PATH."/pages/financial-plan/budget/monthly_graph.php");
		?>
        <p>&nbsp;</p><p>&nbsp;</p>
        <div class="widget_content">
            <h3>Net Profit (or Loss) by Year</h3>
            <div class="clearboth"></div>
        </div><br/>
		
		<?php
		
		
		// TOSIN, Change $eachYear to profitAndLoss array variable 
      	//--------------------------------------------------------
		/*
	    if(isset($eachYear))
        {}
        else{ $eachYear = array("");}
        $draw = new graph_lib(); 
        
        // Yearly graph
        $graphImageName = "net_profit_graph.png";
        $bar_width = 70;
        $xAxisFont = 13;
        $xAxisPostion = 15;
        $unit = html_entity_decode($employee->currencySetting);
        $unitPosition = "before";
        $drawGraph = $draw->_graph($eachYear, $graphImageName, $bar_width, $xAxisFont, $xAxisPostion,$unitPosition ,$unit);
        echo " <img src='".BASE_URL."/".$graphImageName."' />" ;
		*/

	}// End of If statement
?>
<br/><br/><br/><br/>
<?php 
	 if(!empty($_GET['pageid']) && ($_GET['pageid']  == $pageId) && (!empty($_GET['edit'])) && ($_GET['edit'] == "page" ))
	 {
		?>
			<p> <?php echo $introText; ?></p>
			<form method="post" >
				  <div class="rich_textarea">
						<p><textarea id="page_content" name="page_content" type="text">
								<?php echo $getPageContent; ?></textarea></p>
				  </div> 
				 <p><button class="update_page" name="update_page_content" type="submit">
									Update <?php echo $getPageTitle;?></button></p>									
				 <div class="clearboth"></div>
			  </form>
			 <div class="clearboth"></div>
		 <?php 
	 }
	 else{ 
	 ?>
			<?php if(!empty($getPageContent)){?>
				<div class="edit_section">
					<div class="widget_content">
						<h3><?php echo $getPageTitle; ?></h3>
						<div class="click-to-edit" >
							<div class="tuck">
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=page&pageid=<?php echo $pageId;?>">
									<div class="flag">
									<span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
									</div>
								</a>
							</div>
					   </div>
						<div class="clearboth"></div>
						
						<br/>
						<?php echo html_entity_decode( $getPageContent); ?>
						
					</div>
					
				</div><!--end .edit_section-->
			<?php }else{?>
			<div class="section">
				 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=page&pageid=<?php echo $pageId;?>">
					<div class="widget text clean-slate">​
							<h3><?php echo $getPageTitle;?></h3>
							<p>Get started on writing this item</p>
					</div>
				</a>
			</div>
			
	<?php } 
	 }?>
	 
                 
                 
                 
		  <?php
        /*---------------------------------------------------------------
            Get each page secton details 	
        ---------------------------------------------------------------*/
        if($getSectionData)
        {
            // loop through all contents in section and get the details
            foreach($getSectionData as $eachSectionData)
            {
                
                if((isset($_GET['edit'])) && ($_GET['edit'] == "section" ) && (is_numeric($_GET['s_pageid'])) && ($_GET['s_pageid'] == $eachSectionData['section_id']))
                {?>
                    <h3><?php echo $eachSectionData['section_title'];?></h3>
                    <p><?php echo html_entity_decode($eachSectionData['section_desc']);?></p>
                    <form method="post" >
                         <div class="rich_textarea">
                                <textarea id="page_content" name="section_content" type="text" style="width:535px; height:250px;" >
                                        <?php echo $eachSectionData['section_content']; ?></textarea>
                          </div>
                          <br />
                          <input type="hidden" value="<?php echo $eachSectionData['section_id']?>" name="sectionId">
                          
                          <p><button class="update_page" name="update_section_content" type="submit">
                            Update <?php echo $eachSectionData['section_title']?></button></p>
                      </form>		 
                     
                <?php 
                }else
                {
                    if(!empty($eachSectionData['section_content']))
                    {?>
                        <div class="edit_section">
                        <div class="widget_content">
                            <h3><?php echo $eachSectionData['section_title']; ?></h3>
                            <div class="click-to-edit" >
                                <div class="tuck">
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=section&s_pageid=<?php echo $eachSectionData['section_id'];?>&pageid=<?php echo $pageId;?>">
                                        <div class="flag">
                                        <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                                        </div>
                                    </a>
                                </div>
                           </div>
                            <div class="clearboth"></div>
                            
                            <br/>
                            <?php echo html_entity_decode($eachSectionData['section_content']); ?>
                            
                        </div>
                        
                    </div><!--end .edit_section-->
              <?php }
                    else
                    {?>
                         <div class="section">
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=section&s_pageid=<?php echo $eachSectionData['section_id'];?>&pageid=<?php echo $pageId;?>">													<div class="widget text clean-slate">​
                                    <h3><?php echo $eachSectionData['section_title'];?></h3>
                                    <p>Get started on writing this item</p>
                                </div>
                            </a>
                        </div>
                        
                    <?php
                    }
                }
            }// end of foreach
        }// end $getSectionData
    ?>