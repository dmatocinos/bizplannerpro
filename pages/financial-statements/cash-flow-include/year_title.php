<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
?>   

 <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
        <div class="row row-header singleline">
             <span class="cell label column-0 singleline">
                  <p class="overflowable"></p>
            </span>
                <!------------------------------------------
                   Years display
                ------------------------------------------>
                  <?php // loop through and pick out the years
                    
                    $financialYearSF = $sales->startFinancialYear;
                    $financialYearSF = $financialYearSF + 1;
                	if($allSalesDetails) 
					{	
						foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
						{?>
							<span class="cell data column-1 singleline">
								  <p class="overflowable">FY<?php echo $financialYearSF; ?></p>
							</span>
						<?php $financialYearSF = $financialYearSF+1;
						}
					}
					else
					{
						$_numberOfFinancialYrForcasting = $cashProjection->numberOfFinancialYrForcasting;
					 	
						
						for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
						{?>
							<span class="cell data column-1 singleline">
								  <p class="overflowable">FY<?php echo $financialYearSF; ?></p>
							</span>
						<?php   $financialYearSF = $financialYearSF + 1;
						}
                    }
					?>
                    <div class="x-clear"></div>
                </div><!--end .singleline-->
      </div><!---end .preview-table-->
