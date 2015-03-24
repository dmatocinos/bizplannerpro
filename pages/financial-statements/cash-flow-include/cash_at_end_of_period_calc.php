<div class="row row-group_header singleline">
	<span class="cell label column-1 singleline">
	</span>
	<span class="cell data column-1 singleline">
		<p class="overflowable"></p>
	</span>
	<span class="cell data column-1 singleline">
		<p class="overflowable"></p>
	</span>
	<span class="cell data column-1 singleline">
		<p class="overflowable"></p>
	</span>

</div>


<div class="row row-item singleline">
	<span class="cell label column-0 singleline">
		<p class="overflowable">Cash at Beginning of Period</p>
	</span>
	<?php for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ): ?>
	<span class="cell data column-1 singleline">
	<?php
		$val = $beg_of_period[$e_yr];
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

<div class="row row-item singleline">
	<span class="cell label column-0 singleline">
		<p class="overflowable">Net Change in Cash</p>
	</span>
	<?php for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ): ?>
	<span class="cell data column-1 singleline">
	<?php
		$val = $net[$e_yr];
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

<div class="row row-group_footer singleline">
	<span class="cell label column-0 singleline">
		<p class="overflowable">Cash at End of Period</p>
	</span>
	<?php for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ ): ?>
	<span class="cell data column-1 singleline">
	<?php
		$val = $end_of_period[$e_yr];
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
