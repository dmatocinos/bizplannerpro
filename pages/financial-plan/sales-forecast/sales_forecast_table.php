<div id="personal_table">
     <div class="edit_section">
        <div class="widget_content">
            <h3>Sales Forecast Table</h3>
            <div class="clearboth"></div>
        </div>
        <div class="click-to-edit" >
          
            <div class="tuck">
                <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?table=forecast'?>">
                    <div class="flag">
                    <span class="click-to-edit-text" id="ext-gen6"> &nbsp;</span> 
                    </div>
                </a>
            </div>
          
       </div>
    </div><!--end .edit_section-->
    
    
     <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
               
        <div class="row row-header singleline">
             <span class="cell label column-0 singleline">
                  <p class="overflowable"> </p>
            </span>
            
           <?php
           	$financialYearSF = $sales->startFinancialYear;
			$financialYearSF = $financialYearSF + 1;
		   ?>
           <?php // loop through and pick out the years
		   	
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
        
         <?php
			$counter = 0;
			$arraySalesSummation = array();
			$arrayCostSummation = array();
		
		?>
        
        <!-----------------------------------------------------
        	UNIT SALE SECTION  Calculation loop	
       	------------------------------------------------------>
         <div class="row row-group_header singleline">
            <span class="cell label column-1 singleline">
                <p class="overflowable">Unit Sales</p>
            </span>
        </div>
        <?php
        // This loop is just to display the empty cells for expenses
		foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
		{?>
			<span class="cell data column-1 singleline">
				<p class="overflowable"></p>
			</span>
	  <?php } 
        foreach($allSalesDetails as $expDetails)
        {?>
            <div class="row row-group_item singleline"> 
                <span class="cell label column-0 singleline">
                  <p class="overflowable"><?php echo $expDetails['sales_forecast_name']?></p>
                </span>
                <?php 
                foreach($expDetails['financial_status'] as $finDetails)
                {?>
                    <span class="cell data column-1 singleline">
                          <p class="overflowable"><?php echo number_format($finDetails['total_per_yr'], 0, '.', ','); ?></p>
                    </span>
                    
                <?php 
                } 
                ?>
                <div class="x-clear"></div>
            </div><!--end .singleline-->		 
            
            
            <?php 
        }// end foreach ?>
      
        <!-----------------------------------------------------
        	PRICE PER UNIT SECTION  Calculation loop	
       	------------------------------------------------------>
       	<div class="row row-group_header singleline">
                <span class="cell label column-1 singleline">
                    <p class="overflowable">Price Per Unit</p>
                </span>
            </div>
         <?php
		    // This loop is just to display the empty cells for expenses
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{?>
			<span class="cell data column-1 singleline">
				<p class="overflowable"></p>
			</span>
	  <?php } 
	  
			foreach($allSalesDetails as $expDetails)
			{?>
				<div class="row row-group_item singleline"> 
					<span class="cell label column-0 singleline">
					  <p class="overflowable"><?php echo $expDetails['sales_forecast_name']?></p>
					</span>
					<?php 
					foreach($expDetails['financial_status'] as $finDetails)
					{?>
						<span class="cell data column-1 singleline">
							  <p class="overflowable"><?php echo $sales->defaultCurrency.number_format($expDetails['price'], 0, '.', ','); ?></p>
						</span>
						
					<?php 
					} 
					?>
					<div class="x-clear"></div>
				</div><!--end .singleline-->		 
				
				
				<?php 
			}// end foreach ?>
        <!-----------------------------------------------------
        	SALES SECTION  Calculation loop	
       	------------------------------------------------------>
       	<div class="row row-group_header singleline">
                <span class="cell label column-1 singleline">
                    <p class="overflowable">Sales</p>
                </span>
            </div>
         <?php
		    // This loop is just to display the empty cells for expenses
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{?>
			<span class="cell data column-1 singleline">
				<p class="overflowable"></p>
			</span>
	  <?php } 
	  
	  		
			foreach($allSalesDetails as $expDetails)
			{ ?>
				<div class="row row-group_item singleline"> 
					<span class="cell label column-0 singleline">
					  <p class="overflowable"><?php echo $expDetails['sales_forecast_name']?></p>
					</span>
					<?php 
					$totaSaleCounter = 0;
					foreach($expDetails['financial_status'] as $finDetails)
					{?>
						<span class="cell data column-1 singleline">
                        		<?php $totaSale[$totaSaleCounter] = ($finDetails['total_per_yr'] * $expDetails['price']); ?>
							  <p class="overflowable"><?php echo $sales->defaultCurrency.number_format($totaSale[$totaSaleCounter], 0, '.', ','); ?></p>
                        </span>
						
					<?php $totaSaleCounter = $totaSaleCounter + 1;
					} 
					for($i=0; $i< count($expDetails['financial_status']); $i++)
					{
						 $arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
					}
					$counter = $counter+1;
					?>
					<div class="x-clear"></div>
				</div><!--end .singleline-->		 
				<?php 
			}// end foreach  
			?>
            
            <!------------------------------------------
               TOTAL SALES SECTION
             ------------------------------------------>
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Sales</p>
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
              
              <!------------------------------------------
               DIRECT COST PER UNIT SECTION
             ------------------------------------------>
             <div class="row row-group_header singleline">
                <span class="cell label column-1 singleline">
                    <p class="overflowable">Direct Cost Per Unit</p>
                </span>
            </div>
         <?php
		    // This loop is just to display the empty cells for expenses
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{?>
			<span class="cell data column-1 singleline">
				<p class="overflowable"></p>
			</span>
	  <?php } 
	  
			foreach($allSalesDetails as $expDetails)
			{?>
				<div class="row row-group_item singleline"> 
					<span class="cell label column-0 singleline">
					  <p class="overflowable"><?php echo $expDetails['sales_forecast_name']?></p>
					</span>
					<?php 
					foreach($expDetails['financial_status'] as $finDetails)
					{?>
						<span class="cell data column-1 singleline">
							  <p class="overflowable"><?php echo $sales->defaultCurrency.number_format($expDetails['cost'], 0, '.', ','); ?></p>
						</span>
						
					<?php 
					} 
					?>
					<div class="x-clear"></div>
				</div><!--end .singleline-->		 
				<?php 
			}// end foreach ?>
			<!------------------------------------------
               DIRECT COST SECTION
             ------------------------------------------>
             <div class="row row-group_header singleline">
                <span class="cell label column-1 singleline">
                    <p class="overflowable">Direct Cost</p>
                </span>
            </div>
         <?php
		    // This loop is just to display the empty cells for expenses
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{?>
			<span class="cell data column-1 singleline">
				<p class="overflowable"></p>
			</span>
	  <?php } 
	  
			foreach($allSalesDetails as $expDetails)
			{?>
				<div class="row row-group_item singleline"> 
					<span class="cell label column-0 singleline">
					  <p class="overflowable"><?php echo $expDetails['sales_forecast_name']?></p>
					</span>
					<?php 
					foreach($expDetails['financial_status'] as $finDetails)
					{?>
						<span class="cell data column-1 singleline">
                        		<?php $totaCost = ($finDetails['total_per_yr'] * $expDetails['cost']); ?>
							  <p class="overflowable"><?php echo $sales->defaultCurrency.number_format($totaCost, 0, '.', ','); ?></p>
						</span>
					<?php 
					}
					for($i=0; $i< count($expDetails['financial_status']); $i++)
					{
						 $arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
					}
					$counter = $counter+1; 
					?>
					<div class="x-clear"></div>
				</div><!--end .singleline-->		 
				<?php 
			}// end foreach ?>
             <!------------------------------------------
               TOTAL DIRECT COST SECTION
             ------------------------------------------>
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Total Direct Cost</p>
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
             
            <?php include_once(BASE_PATH."/pages/financial-plan/sales-forecast/table_display/gross_margin.php"); ?>
        
        </div><!--end of .widgetForm-->
    <div class="x-clear"></div>
 </div><!--end #personal_table-->
 <p>&nbsp;</p><p>&nbsp;</p>
<!--------------------------------------------------------
 	SALES YEARLY GRAPH
 ---------------------------------------------------------->
   <div class="widget_content">
    <h3>Sales by Year</h3>
    <div class="clearboth"></div>
</div><br/>
<?php
	$financialYearSales = $sales->startFinancialYear;
	$financialYearSales = $financialYearSales + 1;
	$yearSalesCounter = 0; 		
	foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
	{
		
		//$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
		
		// Strip the commas from the digit that gets to thousand
		$totalSales[$yearSalesCounter] = str_replace(",", "", $totalSales[$yearSalesCounter]);

		$each_Sales_Year["FY".$financialYearSales] = $totalSales[$yearSalesCounter] ;
		$yearSalesCounter = $yearSalesCounter + 1;
		$financialYearSales = $financialYearSales + 1;

	}
	
	//$draw = new graph_lib(); 
										
	// Total Sales Yearly Graph
	$graphImageNameSale = "yearly_graph_sales" . $_SESSION['bpId'] . ".png";
	$bar_width = 70;
	$xAxisFont = 13;
	$xAxisPostion = 10;
	$unit = html_entity_decode($sales->currencySetting);
	$unitPosition = "before";
	//$drawGraph = $draw->_graph($each_Sales_Year, $graphImageNameSale, $bar_width, $xAxisFont, $xAxisPostion,$unitPosition ,$unit);
	//echo " <img src='".BASE_URL."/".$graphImageNameSale."' />" ;


	//$imgb64 = GraphHandler::getImgB64($each_Sales_Year, $unit); //	getImgB64($data, $valueformat) %01.0f
	//echo '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.$imgb64.'" />';

	$imgb64 = GraphHandler::getImgB64($each_Sales_Year,  $unit, $graphImageNameSale, 600); //	getImgB64($data, $valueformat)
	//GraphHandler::createGraphImg($data,  $unit, $imgname);
	echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageNameSale . '" />';

	
?>
 <p>&nbsp;</p><p>&nbsp;</p>
 <?php
 	// Graph for sales by months
 	include_once("sales-forecast/sales_monthly_graph.php");
 ?>
 <!--------------------------------------------------------
 	GROSS MARGIN YEARLY GRAPH
 ---------------------------------------------------------->
  <div class="widget_content">
    <h3>Gross Margin(%) by Year</h3>
    <div class="clearboth"></div>
</div><br/>
<?php
	$financialYearGM = $sales->startFinancialYear;
	$financialYearGM = $financialYearGM + 1;
	$monthCounterGM = 0; 		
	foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
	{
		
		// Strip the commas from the digit that gets to thousand
		$grossMarginPercentage[$monthCounterGM] = str_replace(",", "", $grossMarginPercentage[$monthCounterGM]);
		
		//$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
		$each_Gross_Margin_Year["FY".$financialYearGM] = $grossMarginPercentage[$monthCounterGM] ;
		$monthCounterGM = $monthCounterGM + 1;
		$financialYearGM = $financialYearGM + 1;
	}
	
	//$draw = new graph_lib(); 
										
	// Gross Margin Yearly Graph
	$graphImageName = "yearly_graph_gross_margin" . $_SESSION['bpId'] . ".png";
	$bar_width = 70;
	$xAxisFont = 13;
	$xAxisPostion = 10;
	$unit = "%";
	$unitPosition = "after";
	//$drawGraph = $draw->_graph($each_Gross_Margin_Year, $graphImageName, $bar_width, $xAxisFont, $xAxisPostion,$unitPosition ,$unit);
	//echo " <img src='".BASE_URL."/".$graphImageName."' />" ;

	//$imgb64 = GraphHandler::getImgB64($each_Gross_Margin_Year,  $unit); //	getImgB64($data, $valueformat)
	//echo '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.$imgb64.'" />';

	$imgb64 = GraphHandler::getImgB64($each_Gross_Margin_Year,  $unit, $graphImageName, 600); //	getImgB64($data, $valueformat)
	echo '<img style="margin:auto; width: 600; " src="' . IMAGE_GRAPH_URL . $graphImageName . '" />';
	
?>
 
 <p>&nbsp;</p><p>&nbsp;</p>
