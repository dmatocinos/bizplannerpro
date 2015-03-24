<?php 
	$introText = "";
    $pageTitle = "Profit and Loss Statement";
	include_once("fs_template_top.php");  
	
	require_once(LIBRARY_PATH . '/web_calc_full.php');
	$oWebcalc = new WebCalcFull();
	$oWebcalc->build();
	
	?>
      <a class="intro-block-toggle expanded" href="javascript:void(0);" id="ext-gen13"><span>Hide Instructions</span></a>
                             
                            <div id="introText" class="intro-block dim-action-intro-block" style="display: block; ">
                                <span class="tip"></span>
                                <div class="widget-content"><p>The profit and loss statement (also known as the "income statement") is the most common of the standard financial reports that bankers and investors will expect to see in your business plan. It shows your revenues, your expenses, and the difference between the two — that is, your net profit or "bottom line." Is your company going to make more money that it spends?</p>

                                <p>Note that the Profit and Loss Statement here is not directly editable. It is a read-only display of information from other sources. To change the P&L, go to the more detailed tables in the Financial Plan sections, and make your changes there. The P&L will update automatically.</p>
                                		
                                    <span class="clear"></span>
                                </div>
                              </div>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                       <p> <?php  $currentPageData->DisplayAllMsgs('','');  ?></p>
                       <?php 

	
	$sales = new sales_forecast_lib();
	$allSalesDetails = $sales->getAllSales("", "", "");
	$counter = 0;
	
	if($allSalesDetails > 0)
	{
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
		$allExpense = array();
		$allExpense = 0;
        if($allExpDetails)
        {
        	
        	$inprofitandlosstablepage = true; //set to hide major purchases        	
            include_once(BASE_PATH."/pages/financial-plan/budget/budget_table.php");
        }

	?>
    
    
        <!--------------------	INTERESR INCURRED SECTION	------------------------->
<?php 
            include_once("operatng_income.php"); 
?>
        <!--------------------------------------------------------------------------->
    
     <?php include_once("all-financial-statements_calc.php"); ?>
       
    
   
      <div class="preview-table salesForecast-preview table-4-columns profit_and_loss">    
        <!--------------------	INTEREST INCURRED SECTION	------------------------->
          
            <?php 
				if(isset($_interestIncured))
				{
				//include_once("interest_incure_2.php"); 
				}
			?>
        <!--------------------------------------------------------------------------->
        <!--------------------Depreciation and Amortization ------------------------------------->
	<?php //include_once("depreciation_and_amortization.php") ?>
        
        <!--------------------	INCOME TAXES	------------------------------------->
            <?php //include_once("income_taxes.php"); ?>
        <!--------------------------------------------------------------------------->
       
        <!--------------------	NET PROFIT	----------------------------------------->
            <?php //include_once("net_profit.php"); ?>
        <!--------------------------------------------------------------------------->
       
        <!--------------------	NET PROFIT / SALES	--------------------------------->
            <?php //include_once("net_profit_sales.php"); ?>
        <!--------------------------------------------------------------------------->
        
        
             <?php
     
     $tmpdata = $oWebcalc->yearlyinterest;
     $oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Interest Incurred'), $oWebcalc->farraynumber($tmpdata)));
     
     $tmpdata = $oWebcalc->yearlydepreciation;
     $oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Depreciation and Amortization'), $oWebcalc->farraynumber($tmpdata)));
  
     $tmpdata = $oWebcalc->yearlyincometax;
     $oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Income Taxes'), $oWebcalc->farraynumber($tmpdata)));
     
     $tmpdata = $net_profit = $oWebcalc->yearlynetprofit;
     $oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Net Profit'), $oWebcalc->farraynumber($tmpdata)));
     
     $tmpdata = $oWebcalc->yearlynetprofitpercent;
     $oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Net Profit/Sales'), $oWebcalc->farraypercent($tmpdata)));
     ?>  
        
  	</div><!--end of .preview-table-->
       	<link rel="stylesheet" type="text/css" href="../../../assets/css/src/bootstrap.min.css">
	<!-- Product Recommendation -->
	<?php if($net_profit[1] >= 100000) : ?>

		<div class="modal fade" id="product_recommendation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="product_recommendation">Did you know...</h4>
		      </div>
		      <div class="modal-body">
				<div class="row">
					<div class="span12" style="padding: 15px;">
						<h4>
							This client is suitable for the Darwin Corporation Tax mitigation structure.
							<b>You can earn comission <?php echo $_SESSION['commission'] * 100; ?>%</b> for successfully referring this client whilst
							helping them minimise their Corporation Tax exposure.
						</h4>
						<br>
						<h4>
							Click <a href="http://www.contractorspro.co.uk/about-us">here</a> to learn more about Darwin and how both you and your client benefit
						</h4>
					</div>
				</div>
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		
		<script type="text/javascript" language="javascript" src="../../../assets/js/jquery-1.10.2.min.js"></script>			
		<script type="text/javascript" language="javascript" src="../../../plugins/bootstrap/js/bootstrap.min.js"></script>			
		<script type="text/javascript">
			$(document).ready(function () {
				$('#product_recommendation_modal').modal('show');
				$('.modal-backdrop').removeClass('modal-backdrop');

				<?php $url = "http://virtualfdpro.practiceprodemo.co.uk/recommend"; ?>
				$.ajax({
					type: "POST",
					url: "<?php echo $url; ?>",
					data: {
						bp_id : <?php echo $_SESSION['bpId'] ?>, 
						bp_user_id : <?php echo $_SESSION['bp_user_id']; ?>,
						bp_name : "<?php echo $_SESSION['bpName'] ?>", 
						commission : <?php echo $_SESSION['commission'] ?>, 
						net_profit : <?php echo $net_profit[1] ?>, 
					}
				})
				.done(function( msg ) {
				});
							
			});
		</script>

	<?php endif; ?>

       
            
     
    <br/><br/>
<br/><br/><br/><br/>
	<?php
		$sales = new sales_forecast_lib();
		$allSalesDetails = $sales->getAllSales("", "", "");
		if($allSalesDetails)
		{
			// Graph for sales by months
 			include_once(BASE_PATH."/pages/financial-plan/sales-forecast/sales_monthly_graph.php");
			
			echo "<br/><br/><br/><br/>";
			
			include_once(BASE_PATH."/pages/financial-plan/sales-forecast/gross_margin_monthly_graph.php");
		}// end of if($allEmpDetail)
	?>
    
	<?php
	require_once(LIBRARY_PATH . '/web_calc_full.php');
	$oWebcalc = new WebCalcFull();
	$oWebcalc->build();
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
            
            $datay = array();
            //echo highlight_string(var_export($array_netProfitSales, TRUE));            
            $data = array();
            
           //echo highlight_string(var_export($allSalesDetails[0], TRUE));
            
            $financialYearSF = $sales->startFinancialYear;
            $financialYearSF = $financialYearSF + 1;
            $count = 1;
            foreach ($allSalesDetails[0]['financial_status'] as $key => $eachFinStat)
			{            						
            	$data["FY" . $financialYearSF] = $oWebcalc->yearlynetprofit[$count++];
            	$financialYearSF++;
            	
            }
            
            //echo highlight_string(var_export($data, TRUE));
            
            //$unit is set in financial plan/budget/monthly_graph
            $imgname = "pflsyearlygraph" . $_SESSION['bpId'] . ".png";
            $imgb64 = GraphHandler::getImgB64($data,  $unit, $imgname, 600); //	getImgB64($data, $valueformat)
            //GraphHandler::createGraphImg($data,  $unit, $imgname);
            echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $imgname . '" />';
            
            
    
        }// End of If statement
    ?>

<?php }// end of if($allSalesDetails > 0) ?>


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
									Save and Continue <?php echo $getPageTitle;?></button></p>									
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
    
    
                            
                            
<?php include_once("fs_template_bottom.php");  ?>>>
