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
                  <p class="overflowable">As of Period's End</p>
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
$tmpdata = $oWebcalc->yearlycash;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Cash'), $oWebcalc->farraynumber($tmpdata)));      

$tmpdata = $oWebcalc->yearlyaccountsreceivable;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Accounts Receivable'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotalcurrentassets;
$oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Total Current Assets'), $oWebcalc->farraynumber($tmpdata)));

$oWebcalc->writeWebTableRowSpacer();

$tmpdata = $oWebcalc->yearlylongtermassets;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Long Term Assets'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlyaccudepreciation;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Accumulated Depreciation'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotallongtermassets;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Total Long-Term Assets'), $oWebcalc->farraynumber($tmpdata)));


$oWebcalc->writeWebTableRowSpacer(); 

$tmpdata = $oWebcalc->yearlytotalassets;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Total Assets'), $oWebcalc->farraynumber($tmpdata)));


$oWebcalc->writeWebTableRowSpacer();

$tmpdata = $oWebcalc->yearlyaccountspayable;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Accounts Payable'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotalcurrentliabilities;
$oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Total Current Liabilities'), $oWebcalc->farraynumber($tmpdata)));

$oWebcalc->writeWebTableRowSpacer();

$tmpdata = $oWebcalc->yearlylongtermdebt;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Long-Term Debt'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotalliabilities;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Total Liabilities'), $oWebcalc->farraynumber($tmpdata)));

$oWebcalc->writeWebTableRowSpacer();

$tmpdata = $oWebcalc->yearlynetinvestment;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Paid-in-capital'), $oWebcalc->farraynumber($tmpdata)));


$tmpdata = $oWebcalc->yearlyretainedearnings;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Retained Earnings'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlyearnings;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Earnings'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotalownerequity;
$oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Total Owner Equity'), $oWebcalc->farraynumber($tmpdata)));

$tmpdata = $oWebcalc->yearlytotalliabilityandEquity;
$oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Total Liabilities & Equity'), $oWebcalc->farraynumber($tmpdata)));




?>
      