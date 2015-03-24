<?php
	// Update 08/June/2013
?>
            <div class="row row-item singleline">
              <span class="cell label column-0 singleline">
                      <p class="overflowable">Interest Incurred</p>
              </span>
              <?php
				// loop through this for number of years
				foreach($array_interestIncured as $value)
				{
					$value = global_lib::formatDisplayWithBrackets($value, $sales->defaultCurrency);
					
					echo '<span class="cell data column-1 singleline">
						  <p class="overflowable">' . $value . '</p>
					</span>';
				} 
				
			?>
				
            <div class="x-clear"></div>
        </div><!--end .singleline-->

   