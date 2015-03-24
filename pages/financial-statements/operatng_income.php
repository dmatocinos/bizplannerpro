
        <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
         <!------------------------------------------	
           OPERATNG INCOME 
         ------------------------------------------>
              <?php
              $tmpdata = $oWebcalc->yearlyoperatingincome;
  $oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Operating Income'), $oWebcalc->farraynumber($tmpdata)));          
?>
            <!--
             <div class="row row-group_footer singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Operating Income</p>
                  </span>
                <?php
                $totalCostCounter = 0;
                
				
				
                foreach($arrayCostSummation as $sumOfAllCost)
                {
                    $operatingIncome[$totalCostCounter] = ($grossMargin[$totalCostCounter] - $allExpense[$totalCostCounter]);
					
					?><span class="cell data column-1 singleline">
                        <?php //$allExpense[$totalCostCounter;  ?>
                        <p class="overflowable"><?php echo global_lib::formatDisplayWithBrackets($operatingIncome[$totalCostCounter], $sales->defaultCurrency); ?></p>	
                    </span>
                <?php 
                $totalCostCounter = $totalCostCounter + 1;	
                }
               ?>
               
              <div class="x-clear"></div>
            </div>--><!--end .singleline-->
      </div><!--end of .widgetForm-->   
