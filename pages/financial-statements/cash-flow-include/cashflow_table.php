<?php  
	  /**---	UPDATED JUNE 10 2013	---**/
require_once(LIBRARY_PATH . '/web_calc_full.php');
$oWebcalc = new WebCalcFull();
$oWebcalc->build();

$sales = new sales_forecast_lib();
$allSalesDetails	= $sales->getAllSales('', '', '');

?>   

 <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
        <div class="row row-header singleline">
             <span class="cell label column-0 singleline">
                  <p class="overflowable">&nbsp;</p>
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
      
<?php 

$arrayempty = array('','','');

$oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Operations'), $arrayempty));

$tmpdata = $oWebcalc->yearlynetprofit;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Net Profit'), $oWebcalc->farraynumber($tmpdata)));      

$tmpdata = $oWebcalc->yearlydepreciation;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Depreciation and Amortization'), $oWebcalc->farraynumber($tmpdata)));



$tmpdata = $oWebcalc->yearlychangeinaccountsreceivable;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Change in Accounts Receivable'), $oWebcalc->farraynumber($tmpdata)));
		
$tmpdata = $oWebcalc->yearlychangeinaccountspayable;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Change in Accounts Payable'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlynetcashflowfromoperations;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Net Cash Flow From Operations'), $oWebcalc->farraynumber($tmpdata)));

$oWebcalc->writeWebTableRowSpacer();


$oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Investing and Finance'), $arrayempty));

$tmpdata = $oWebcalc->yearlyassetspurchasedorsold;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Total Major Purchases'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlychangeinlongtermdebt;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Long-Term Debt'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlynetcashflowfrominvesting;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Net Cash Flow'), $oWebcalc->farraynumber($tmpdata)));


$oWebcalc->writeWebTableRowSpacer();	

		
$tmpdata = $oWebcalc->yearlycashatbeginningofperiod;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Cash at Beginning of Period'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlynetchangeincash;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Net Change in Cash'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlycashatendofperiod;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Cash at End of Period'), $oWebcalc->farraynumber($tmpdata)));
		


?>
      