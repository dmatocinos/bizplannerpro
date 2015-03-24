
    
     <div id="widgetForm:j_id265:j_id274:j_id276:0:preview-table-table" class="preview-table salesForecast-preview table-4-columns">
            
            
       <?php

        //$oWebcalc is instantiated in budget.php
       
        $emptyarray = array('','','');
        $fyyears	= $oWebcalc->fyyears;
        
        $oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Expenses'), $fyyears));
        
        $tmpdata = $oWebcalc->yearlytotalsalary;
        $oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Salary'), $oWebcalc->farraynumber($tmpdata)));
        
        $tmpdata = $oWebcalc->yearlyemployeeexpenses;
        $oWebcalc->writeWebTableRow( 'row-item', array_merge(array('Employee Related Expenses'), $oWebcalc->farraynumber($tmpdata)));
        
        foreach($oWebcalc->yearlyexpenses as $expense) {
        	$oWebcalc->writeWebTableRow( 'row-item', $oWebcalc->farraynumber($expense));
        	
        }
        
              
        $tmpdata = $oWebcalc->yearlytotaloperatingexpenses;
        $oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Total Expenses'), $oWebcalc->farraynumber($tmpdata)));
       
        if (!isset($inprofitandlosstablepage)) {
        
	        $oWebcalc->writeWebTableRowSpacer();
	        
	        $oWebcalc->writeWebTableRow( 'row-group_header', array_merge(array('Major Purchases'), $emptyarray));
	        
	        
	        foreach($oWebcalc->yearlymajorpurchases as $purchase) {
	        	$oWebcalc->writeWebTableRow( 'row-item', $oWebcalc->farraynumber($purchase));
	        	 
	        }
	        
	        $tmpdata = $oWebcalc->yearlytotalmajorpurchases;
	        $oWebcalc->writeWebTableRow( 'row-group_footer', array_merge(array('Total Major Purchases'), $oWebcalc->farraynumber($tmpdata)));
        
        }
        
        ?>
			
            
        </div><!--end of .widgetForm-->

        
        
        
