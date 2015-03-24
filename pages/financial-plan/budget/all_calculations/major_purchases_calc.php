		<?php
			$yearly_major_purchases = array();
			$total_yearly_major_purchases = array(0, 0, 0);
			
			//global_lib::log($allPurDetails);
          	foreach($allPurDetails as $purDetails)
			{		
				if (strpos($purDetails['mp_date'], '(Year 2)') !== FALSE) {
					$yearlytotal = $purDetails['mp_price'];
				
					$yearly_major_purchases[$purDetails['mp_name']] = array(
						$expenditure->defaultCurrency . '0',
						$expenditure->defaultCurrency .number_format($yearlytotal, 0, '.', ','),
						$expenditure->defaultCurrency . '0'
					);
					
					
					$total_yearly_major_purchases[1] += $yearlytotal;
				}
				else if (strpos($purDetails['mp_date'], '(Year 3)') !== FALSE) {
					$yearlytotal = $purDetails['mp_price'];
				
					$yearly_major_purchases[$purDetails['mp_name']] = array(
						$expenditure->defaultCurrency . '0',
						$expenditure->defaultCurrency . '0',
						$expenditure->defaultCurrency .number_format($yearlytotal, 0, '.', ',')
					);
					
					
					$total_yearly_major_purchases[2] += $yearlytotal;
				}
				else {
					$yearlytotal = $purDetails['mp_price'];
				
					$yearly_major_purchases[$purDetails['mp_name']] = array(
						$expenditure->defaultCurrency .number_format($yearlytotal, 0, '.', ','),
						$expenditure->defaultCurrency . '0',
						$expenditure->defaultCurrency . '0'
					);
					
					
					$total_yearly_major_purchases[0] += $yearlytotal;
				}
			} 
        ?>

