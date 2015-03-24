<div class="row row-item singleline">
	<span class="cell label column-0 singleline">
		<p class="overflowable">Depreciation and Amortization</p>
	</span>

	<?php 
	for ($i = 1; $i < 4; $i++) {
		echo '
			<span class="cell data column-1 singleline">
				<p class="overflowable">' . $major_purchases_data['yearlydepreciationrows'][$i] . '</p>
			</span>
		';
	} 
	?>
	<div class="x-clear"></div>
</div><!--end .singleline-->
