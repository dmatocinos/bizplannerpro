<div class="row row-item singleline">
	<span class="cell label column-0 singleline">
	      <p class="overflowable">Change in Accounts Payable</p>
	</span>
	<?php for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ): ?>
	<span class="cell data column-1 singleline">
	<?php
		$val = $change_payable[$e_yr];
		if ($val < 0) {
			$valf = $val * -1;
		}
		else {
			$valf = $val;
		}
		$valf = number_format($valf, 0, '.', ',');
		$valf = $currency . $valf;
		if ($val < 0) {
			$valf = "({$valf})";
		}
	?>
		<p class="overflowable"><?php echo $valf; ?></p>
	</span>
	<?php endfor ?>
	<div class="x-clear"></div>
</div><!--end .singleline-->

