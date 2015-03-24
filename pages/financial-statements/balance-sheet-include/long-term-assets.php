<?php 
	  /**---	UPDATED JUNE 11 2013	---**/
	  
    $lib = new expenditure_lib();
	$numbersyrOfFinancialForecast = $lib->numberOfFinancialYrForcasting;	
	$major_purchases_details = $lib->getAllMajorPurchaseDetails('', 'mp_date','');
	$years = array();
	foreach ($major_purchases_details as $purchase) {
		list($pm, $py) = explode(' ', $purchase['mp_date']);
		if ( ! isset($years[$py])) {
			$years[$py] = 0;
		}

		if ($purchase['mp_depreciate']) {
			$years[$py] = $purchase['mp_price'];
		}
	}
	$major_purchase = array_values($years);
	$long_term_assets = array();

	$p = .20;

	for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ) {
		$total_major_purchase = 0;
		for ($i = 0; $i < $e_yr; $i++) {
			if (isset($major_purchase[$i]))
				$total_major_purchase += $major_purchase[$i];
		}

		if (isset($data[$e_yr - 1])) {
			$total_major_purchase -= $data[$e_yr - 1];
		}

		$long_term_assets[$e_yr] = $total_major_purchase;
		$data[$e_yr] = $total_major_purchase * $p;
	}
      
      
?>
        <div class="row row-spacer singleline">
		<?php // display empty line 
            for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
            {
		?>
                  <span class="cell label column-0 singleline">
                          <p class="overflowable"> </p>
                  </span>
        <?php
            }
		?>            
            <div class="x-clear"></div>
        </div>
                    
                    
         <!------------------------------------------	
         Long Term Assets
         ------------------------------------------>
              <div class="row row-item singleline">
                  <span class="cell label column-0 singleline">
                          <p class="overflowable">Long Term Assets</p>
                  </span>
                <?php
                
				$open_bracket  = "";
				$closed_bracket = "";
				$cancelNegative = 1;
								
				$acuLAssets = 0;
				
				/*---	Loop through for number of financial years (i. e 3 or 5)	---*/
				for($e_year = 0; $e_year < $_numberOfFinancialYrForcasting; $e_year++ )
				{
					
						$acuLAssets += $long_term_assets[$e_year];
					
						if( $acuLAssets < 0)
						{
							$open_bracket  = OPEN_BRACKET;
							$closed_bracket  = CLOSED_BRACKET;
							$cancelNegative = -1;
						}
				?>
					<span class="cell data column-1 singleline">
						<p class="overflowable"><?php echo $open_bracket;?><?php echo $sales->defaultCurrency . $acuLAssets . $closed_bracket;?> </p>	
					</span>
				<?php 					
				}// End of loop
				
				
				
			   ?>
              <div class="x-clear"></div>
            </div><!--end .singleline-->
    
