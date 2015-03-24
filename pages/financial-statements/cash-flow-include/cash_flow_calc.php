<?php
		 include(LIBRARY_PATH . '/web_calc.php');
		include('../financial-plan/budget/all_calculations/major_purchases_calc.php');
		 $webcalc = new WebCalc();
		 $webcalc->build();
		$accountReceivable_allYears = $webcalc->balancesheetdata['balaccreceivable'];
                $yrAccountPayable = $webcalc->balancesheetdata['balaccpayable'];
		$balLongTermDebt 	= $webcalc->profitlossdata['ns']['balLongTermDebt'];

		$years = 3;
		$change_receivable = array();
		$change_payable = array();

		$assets_purchased = array();
		$change_longterm = array();
		$investing_and_finance = array();

		$beg_of_period = array();
		$net = array();
		$end_of_period = array();
		for ($i = 0; $i < $years; $i++) {
			if ($i == 0) {
				$change_receivable[$i] = 0 - $accountReceivable_allYears[$i];
				$change_payable[$i] = 0 + $yrAccountPayable[$i];
			}
			else {
				$change_receivable[$i] = $accountReceivable_allYears[$i - 1] - $accountReceivable_allYears[$i];
				$change_payable[$i] = $yrAccountPayable[$i] - $yrAccountPayable[$i - 1];
			}

			$operations[$i] = $array_netProfitFormat[$i]
				+ $array_depreciation[$i]
				+ $change_receivable[$i]
				+ $change_payable[$i];


			$assets_purchased[$i] = 0 - $total_yearly_major_purchases[$i];
			if ($i == 0) {
				$change_longterm[$i] = $balLongTermDebt[$i];
			}
			else {
				$change_longterm[$i] = $balLongTermDebt[$i] - $balLongTermDebt[$i - 1];
			}

			$investing_and_finance[$i] = $assets_purchased[$i] + $change_longterm[$i];


			if ($i == 0) {
				$beg_of_period[$i] = 0;
			}
			else {
				$beg_of_period[$i] = $net[$i - 1];
			}
			$net[$i] = $operations[$i] + $investing_and_finance[$i];
			$end_of_period[$i] = $beg_of_period[$i] + $net[$i];
		}
?>
