<?php

require_once(LIBRARY_PATH . '/pdf.php'); require_once(LIBRARY_PATH .
'/jpgraph_lib.php');

class WebCalc {

	public $grapher 		= null;
	public $graphdatabank = null;
	public $salesdata 	= array();
	public $expensesdata = array();
	public $employeedata = array();
	public $loansdata	= array();
	public $profitlossdata = array();
	public $balancesheetdata = array();
	public $cashflowdata = array();
	
	public function __construct()
	{
		$title = '';
		$this->grapher = new GraphHandler();
		$this->graphdatabank = new GraphDataHandler();
		$this->profitlossdata['ns'] = array();
		
	}

	public function build()
	{
		$page_repo = new page_lib();
		$user = $page_repo->getCurrentUser();
		$user = $user[0];

		

		$top_menus = $page_repo->topMenus();

		
		foreach ($top_menus as $top_menu) {

			$this->addSubMenu($page_repo, $this->pdf, $top_menu);
		}


	}

	protected function addSubMenu($page_repo, $pdf, $page)
	{
		$title 		= $page['pagetitle'];
		$appendixprefix = ($title!="Appendix"?"":"Appendix");
		$appendixstarted = false;
		foreach ($page_repo->getMenu($page) as $menu) {
			// Check for renderer method. If none, use default
			$fn = 'render' .$appendixprefix. str_replace(' ', '', $menu['pagetitle']);
			if ( ! method_exists($this, $fn)) {
				$fn = 'renderDefault';
			}
			if ($appendixprefix=="Appendix") { 
				if (!$appendixstarted) {
					$appendixstarted = true;
				} else {
					
				}
				
				
			}
			
			$this->$fn($page_repo, $pdf, $menu);

			//$this->addSubMenu($page_repo, $pdf, $menu);
		}
	}

	protected function renderDefault($page_repo, $pdf, $menu)
	{
		$this->renderPageContent($page_repo, $pdf, $menu);
		$this->renderDefaultSections($page_repo, $pdf, $menu);
	}

	protected function renderDefaultSections($page_repo, $pdf, $menu)
	{
		
	}

	protected function renderPageTitle($page_repo, $pdf, $menu)
	{
		
	}

	protected function renderPageContent($page_repo, $pdf, $menu)
	{
		
	}
	

	// FINANCIAL PLAN

	protected function renderSalesForecast($page_repo, $pdf, $menu)
	{
		$sales_forecast_lib = new sales_forecast_lib();
		$sales = $sales_forecast_lib->getAllSales('', '', '');
		
		

		$fy_start = $sales_forecast_lib->startFinancialYear;
		$th = array('');
		foreach ($sales[0]['financial_status'] as $each_fin_stat) {
			$th[] = 'FY' . ++$fy_start;
		}
		$this->th = $th;
		

		$unit_sales 		= array();
		$price_per_unit 	= array();
		$product_sales 		= array();
		$direct_cost_per_unit 	= array();
		$direct_cost 		= array();
		$totalSale 			= array();

		$years = array_slice($th, 1);
		//for appendix later on
		$products				= array(); //product name, array() of monthlyvalues
		$monthlyUnitSales		= array();
		$monthlyPricePerUnit 	= array();
		$monthlyProductSales	= array();        
		$monthlyDirectCostPerUnit 	= array();
		$monthlyDirectCost		= array();
		
		$monthlyTotalSales		= array();
		$monthlyTotalDirectCost	= array();
		$monthlyGrossMargin		= array();			
		$monthlyGrossMPercentage= array();

		

		$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$list12Months = $sales_forecast_lib->twelveMonths($start_years, $start_month);
		$months					= $list12Months;
		$monthCounter = 0;
		$productCounter = 0;
		//

		foreach ($sales as $exp_details) {
			$us_td 		= $ppu_td = $ps_td = $dcpu_td = $dc_td = array();
			$us_td[] 	= $exp_details['sales_forecast_name'];
			$ppu_td[] 	= $exp_details['sales_forecast_name'];
			$ps_td[] 	= $exp_details['sales_forecast_name'];
			$dcpu_td[] 	= $exp_details['sales_forecast_name'];
			$dc_td[] 	= $exp_details['sales_forecast_name'];

			$totalSaleCounter = 0;

			foreach ($exp_details['financial_status'] as $fin_details) {
				$us_td[] = $this->number($fin_details['total_per_yr']);

				$ppu_td[] = $this->currency($sales_forecast_lib, $exp_details['price']);

				if ( ! isset($totalSale[$totalSaleCounter])) {
					$totalSale[$totalSaleCounter] = 0;
				}
				$sale = ($fin_details['total_per_yr'] * $exp_details['price']);
				$totalSale[$totalSaleCounter] += $sale;

				$ps_td[] = $this->currency($sales_forecast_lib, $sale);

				$dcpu_td[] = $this->currency($sales_forecast_lib, $exp_details['cost']);

				$cost = ($fin_details['total_per_yr'] * $exp_details['cost']);
				if ( ! isset($totalCost[$totalSaleCounter])) {
					$totalCost[$totalSaleCounter] = 0;
				}
				$totalCost[$totalSaleCounter] += $cost;
				$dc_td[] = $this->currency($sales_forecast_lib, $cost);

				$totalSaleCounter = $totalSaleCounter + 1;
			}

			$unit_sales[] 		= $us_td;
			$price_per_unit[] 	= $ppu_td;
			$product_sales[] 	= $ps_td;
			$direct_cost_per_unit[] = $dcpu_td;
			$direct_cost[] 		= $dc_td;

			//set monthly values
			$products[$productCounter] = array();
			$products[$productCounter]['name'] = $exp_details['sales_forecast_name'];
			$monthlyUnitSales		= array();
			$monthlyPricePerUnit 	= array();
			$monthlyProductSales	= array();        
			$monthlyDirectCostPerUnit 	= array();
			$monthlyDirectCost		= array();
			

			$monthCounter = 0;

			foreach($list12Months as $monthList)
			{
				
				// $monthsInNumbers i.e 01 ... 12						
				$monthsInNumbers 		= str_pad($monthCounter+1,2,"0",STR_PAD_LEFT); //pad with leading 0						

				//array_push($datax, $allExpensesMonths[$monthCounter]);
				//array_push($datay, array_sum($eachSaleMonth_[$monthCounter]));

				$monthlyUnitSales[$monthCounter]		= $exp_details['month_'.$monthsInNumbers];
				$monthlyPricePerUnit[$monthCounter] 	= $exp_details['price'];
				$monthlyProductSales[$monthCounter]		= $exp_details['price'] * $exp_details['month_'.$monthsInNumbers];
				$monthlyTotalSales[$monthCounter]		+= $monthlyProductSales[$monthCounter];
				$monthlyDirectCostPerUnit[$monthCounter]= $exp_details['cost'];
				$monthlyDirectCost[$monthCounter]		= $exp_details['cost'] * $exp_details['month_'.$monthsInNumbers];
				$monthlyTotalDirectCost[$monthCounter]	+= $monthlyDirectCost[$monthCounter];
				
				
				
				$monthCounter = $monthCounter+1;
			}
			
			$products[$productCounter]['monthlyUnitSales'] 		= $monthlyUnitSales;
			$products[$productCounter]['monthlyPricePerUnit'] 	= $monthlyPricePerUnit;
			$products[$productCounter]['monthlyProductSales'] 	= $monthlyProductSales;
			$products[$productCounter]['monthlyDirectCostPerUnit'] 	= $monthlyDirectCostPerUnit;
			$products[$productCounter]['monthlyDirectCost'] 		= $monthlyDirectCost;

			$productCounter++;

		}

		for($mo=0;$mo<12;$mo++) {
			$monthlyGrossMargin[]		= $monthlyTotalSales[$mo] - $monthlyTotalDirectCost[$mo];
			$monthlyGrossMPercentage[]	= $monthlyGrossMargin[$mo]/$monthlyTotalSales[$mo]*100;
		}
		
		//keep values 
		$this->salesdata['products']			= $products;
		$this->salesdata['monthlyTotalSales'] 	= $monthlyTotalSales;				
		$this->salesdata['monthlyTotalDirectCost'] 	= $monthlyTotalDirectCost;
		$this->salesdata['monthlyGrossMargin'] 	= $monthlyGrossMargin;
		$this->salesdata['monthlyGrossMPercentage'] 	= $monthlyGrossMPercentage;
		$this->salesdata['months'] 				= $months;
		$this->salesdata['years'] 				= $years;
		$this->salesdata['currency']			= $sales_forecast_lib->defaultCurrency;


		$this->salesdata['yrlyUnitSales']	= $unit_sales;
		$this->salesdata['yrlyUnitPrices']	= $price_per_unit;
		$this->salesdata['yrlyProdSales']	= $product_sales;
		
		$this->salesdata['yrlyUnitCost']	= $direct_cost_per_unit;
		$this->salesdata['yrlyCosts']		= $direct_cost;
		$this->salesdata['yrlyTotalCosts']	= $total_direct_cost;


		$total_sales = array('Total Sales');
		$total_direct_cost = array('Total Direct Cost');
		$gross_margin = array('Gross Margin');
		$gross_margin_percentage = array('Gross Margin %');
		$this->salesdata['grossMarginRaw'] = array();
		for ($i = 0; $i < 3; $i++) {
			$total_sales[] = $this->currency($sales_forecast_lib, $totalSale[$i]);
			$total_direct_cost[] = $this->currency($sales_forecast_lib, $totalCost[$i]);
			$gm = $totalSale[$i] - $totalCost[$i];
			$gross_margin[] = $this->currency($sales_forecast_lib, $gm);
			$this->salesdata['grossMarginRaw'][] = $gm;
			$gross_margin_percentage[] = $this->percentage(($gm * 100) / $totalSale[$i]);
		}
		
		
		//keep a reference to sales data to be used later on
		$this->salesdata['yrlyTotalCosts']		= $total_direct_cost;
		$this->salesdata['yrlyGrossMargin'] 	= $gross_margin;
		$this->salesdata['yrlyGMPercentage'] 	= $gross_margin_percentage;
		$this->salesdata['yrlyTotalSales']		= $total_sales;
		
			

		//keep a reference to sales data to be used later on
		$this->salesdata['gross_margin'] = $gross_margin;
		$this->salesdata['gross_margin_percentage'] = $gross_margin_percentage;

		

		

		//echo highlight_string(var_export($thtml->getHTML(), TRUE));
		//echo '<br>';
		


		//keep data to graphdatabank
		$this->graphdatabank->salesobj  = $sales_forecast_lib;
		$this->graphdatabank->sales     = $sales;
		$this->graphdatabank->totalsales = $totalSale;
		$this->graphdatabank->grossmargin = $gross_margin_percentage;

		

	}

	private function writeGrossMarginRows($gross_margin, $gross_margin_percentage, &$table, $long = null)
	{
		
		
	}

	protected function renderHumanResources($page_repo, $pdf, $menu)
	{
		$this->writePPlanTable($pdf);
		
	}
	
	
	
	
	private function writePPlanTable($pdf) {
		
		$employee = new employee_lib();
		$allEmpDetails = $employee->getAllEmployeeDetails2("", "", "");

		if(!$allEmpDetails) return;
		

		$years = array('');
		
		foreach ($allEmpDetails[0]['financial_status'] as $eachFinStat)
		{

			$years[] = "FY" . $eachFinStat['financial_year'];

		}
		
		
		$counter        = 0;
		$arraySummation = "";
		
		
		$employees 		= array();
		
		
		//echo highlight_string(var_export($allEmpDetails, TRUE));
		
		$monthlysalarytotal = array();
		
		foreach($allEmpDetails as $empDetails)
		{
			$td = array();
			
			$td[] = $empDetails['emplye_name'];
			foreach($empDetails['financial_status'] as $finDetails)
			{
				$td[] = $employee->defaultCurrency.$finDetails['total_per_yr'];
			}
			

			for($i=0; $i< count($empDetails['financial_status']); $i++)
			{
				$arraySummation[$i][$counter]  = $empDetails['financial_status'][$i]['total_per_yr'];
			}
			
			$counter = $counter+1;
			
			$monthlysalary 	= array();

			


			for($mo=0; $mo < 12; $mo++)
			{		
				// $monthsInNumbers i.e 01 ... 12						
				$monthsInNumbers 		= str_pad($mo+1,2,"0",STR_PAD_LEFT); //pad with leading 0	
				$monthlysalary[$mo]		= $empDetails['month_'.$monthsInNumbers];
				$monthlysalarytotal[$mo]	+= $monthlysalary[$mo]; 

			}
			
			$employees[] = array('name'=>$empDetails['emplye_name'], 'monthlysalary'=>$monthlysalary, 'yrlysalary'=>$td);
			
			
		}
		
		$this->employeedata['employees'] = $employees;
		$this->employeedata['monthlysalarytotal'] = $monthlysalarytotal;
		
		//echo highlight_string(var_export($employees, TRUE));
		$td = array();
		$td = array("Total");
		
		foreach($arraySummation as $total)
		{
			$td[] = $employee->defaultCurrency.array_sum($total);
		}
		
		
		$this->employeedata['yrlyTotal'] = $td;
		
		
	}
	
	

	protected function renderBudget($page_repo, $pdf, $menu)
	{
		
		$expenditure    = new expenditure_lib();
		$employee       = new employee_lib();
		$allExpDetails  = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
		$allEmpDetails  = $employee->getAllEmployeeDetails2("", "", ""); // All employees
		$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation
		$arraySummation = array();
		$yearexpenses   = array();

		$empexpenses    = array();

		
		

		$this->initExpenses($allExpDetails, $allEmpDetails, $arraySummation, $yearexpenses, $empexpenses, $expenditure, $employee);

		$this->expensesdata['employeeexpenses'] = $empexpenses;

		//keep data to graphdatabank
		$this->graphdatabank->employee                  = $employee;
		$this->graphdatabank->allEmpDetails             = $allEmpDetails;
		$this->graphdatabank->allExpDetails             = $allExpDetails;
		$this->graphdatabank->personalRelatedExpenses   = $personalRelatedExpenses;
		$this->graphdatabank->yearexpenses              = $yearexpenses;

		
		$this->writeBudgetTable($pdf );
		

		//var_dump($arraySummation);

		// Related Expenses calculation
		(int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];

		//get monthly expenses

		

	}


	private function writeBudgetTable($pdf ) {
				
		
	}

	private function writeBudgetExpensesRows(&$table, $expenses){
		
	}

	

	protected function renderCashFlowProjections($page_repo, $pdf, $menu)
	{
				
		
	}
	

	protected function renderLoansandInvestments($page_repo, $pdf, $menu)
	{

		$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");

		if($allloanInvestmentProjection)
		{
			$this->writeLoansandInvestmentsTable($_loanInvestment, $allloanInvestmentProjection, $pdf );
		}

		
	}

	private function writeLoansandInvestmentsTable($_loanInvestment, $allloanInvestmentProjection, $pdf ) {

		$th = array('');
		$yrsOfFinancialForecast = $_loanInvestment->financialYear();
		for($e_yr = 0; $e_yr < count($yrsOfFinancialForecast); $e_yr++ )
		{
			$th[] = 'FY' . $yrsOfFinancialForecast[$e_yr];

		}

		$yearlydata = array();
		$monthlydata= $this->loansdata['monthly'] 	= array();
		
		$yearlydata['yearsrows'] 			= $th;
		$yearlydata['loansrows']			= array();
		$yearlydata['loansdetailrows']	= array();
		
		

		//echo highlight_string(var_export($allloanInvestmentProjection, TRUE));
		
		$monthlycolumnname 				= 'limr_month_';
		$monthlydata['loansrows']		= array();
		$monthlydata['loansdetailrows']	= array();
		$monthlydata['totalrows']	 	= array("Total Amount Received");
		
		$monthlyreceive = array();
		$monthlypayment = array();
		
		$tmploans = $allloanInvestmentProjection[0];
		
		for($i = 1; $i < 13; $i++) {			
			
			$monthlyreceive[] = $tmploans['limr_month_' . str_pad($i,2,"0",STR_PAD_LEFT)];
			$monthlypayment[] = $tmploans['limp_month_' . str_pad($i,2,"0",STR_PAD_LEFT)];
		}
		
				
		$this->loansdata['$monthlyreceive'] = $monthlyreceive;
		$this->loansdata['$monthlypayment'] = $monthlypayment;
		
		
		foreach($allloanInvestmentProjection as $expDetails)
		{

			$td = array($expDetails['loan_invest_name']);

			foreach($expDetails['financial_receive'] as $finDetails)
			{
				$td[] = $_loanInvestment->defaultCurrency.number_format($finDetails['lir_total_per_yr'], 0, '.', ',');

			}

			$details = $expDetails['type_of_funding'] . " at " . $expDetails['loan_invest_interest_rate'] . "% interest";
			
			$td[0] .= '<br><span style="font-family: arialmt">' . $details . '</span>';
			
			
			$yearlydata['loansrows'][] = $td;
			
			
			//$details = $expDetails['type_of_funding'] . " at " . $expDetails['loan_invest_interest_rate'] . "% interest";
			//$table->addTDRow(array($details,"","",""));

			$yearlydata['loansdetailrows'][] 	= array($details,"","","");
			
			
			
			for($i=0; $i< count($expDetails['financial_receive']); $i++)
			{
				$arraySummation[$i][$counter]  = $expDetails['financial_receive'][$i]['lir_total_per_yr'];
			}

			
			$tmpmonthlyloanscols 	= array($expDetails['loan_invest_name']);
			$tmpmonthlydetailcols 	= array($details);
			
			
			//keep monthly data
			for($i = 0; $i < 12 ; $i++)
			{
				$strindex 				= str_pad($i+1,2,"0",STR_PAD_LEFT);
				$tmpmonthlyloanscols[] 	= $expDetails[$monthlycolumnname . $strindex];
				$tmpmonthlydetailcols[] = "";				
				$monthlydata['totalrows'][$i+1] += $expDetails[$monthlycolumnname . $strindex];
			}
			
			
			
			
			$monthlydata['loansrows'][] 		= $tmpmonthlyloanscols;
			$monthlydata['loansdetailrows'][] 	= $tmpmonthlydetailcols;		
			
			
			$counter = $counter+1;

		}// end foreach

		$td = array("Total Amount Received");

		foreach($arraySummation as $total)
		{
			$td[] = $_loanInvestment->defaultCurrency . number_format(array_sum($total), 0, '.', ',');
		}

		

		
		$yearlydata['totalrows'] = $td;
		
		
		$this->loansdata['yearly'] 	= $yearlydata;
		$this->loansdata['monthly'] = $monthlydata;
		
		//echo highlight_string(var_export($yearlydata, TRUE));

		//echo highlight_string(var_export($monthlydata, TRUE));
		
		
		
	}

	// FINANCIAL STATEMENT

	protected function renderProfitandLossStatement($page_repo, $pdf, $menu)
	{

		/* here we make use of the stored graph data */

		//$this->renderImage($pdf, 'Sales By Month', 'monthly_graph_sales.png');
		//$this->renderImage($pdf, 'Gross Margin(%) By Month', 'monthly_graph_gross_margin.png');
		//$this->renderImage($pdf, 'Expenses By Month', 'monthly_graph.png');

		$this->writePAndLTable($pdf);

		
	}

	private function writePAndLTable($pdf) {

		

		$sales = new sales_forecast_lib();
		$allSalesDetails = $sales->getAllSales("", "", "");

		$yeartotal = 0;
		
		//echo highlight_string(var_export($allSalesDetails, TRUE));
		
		if($allSalesDetails > 0)
		{
			
			
			
			$monthrows = array('Revenue');
			
			
			foreach($allSalesDetails as $expDetails)
			{
				$totaSaleCounter = 0;

				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					$arraySalesSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['price']);
				}
				
				
				
				for($i = 0; $i < 12; $i++){
						
					$stri = str_pad($i+1,2,"0",STR_PAD_LEFT);
					$monthrows[$i+1] += ($expDetails["month_" . $stri] * $expDetails['price']);
				
					
				}
				
				//echo highlight_string(var_export($tmprows, TRUE));
				
				$counter = $counter+1;
			}// end foreach

			$this->profitlossdata['monthlyrevenuerows'] = $monthrows;
			//echo highlight_string(var_export($this->profitlossdata['monthlyrevenuerows'], TRUE));
			
			$revenue = $arraySalesSummation;
			$th = array("");

			$financialYearSF = $sales->startFinancialYear;
			$financialYearSF = $financialYearSF + 1;

			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				$th[] = "FY" . $financialYearSF++;
			}

			

			$td = array("Revenue");

			
			
			$totalSalesCounter = 0;
			foreach($arraySalesSummation as $sumOfAllSales)
			{
				$totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));
				$totalSales_format[$totalSalesCounter] = number_format(array_sum($sumOfAllSales), 0, '.', ',');
				$td[] = $sales->defaultCurrency.$totalSales_format[$totalSalesCounter];
				
				
				$totalSalesCounter = $totalSalesCounter + 1;
				
			}

			

			
			$this->profitlossdata['yearlyrevenuerows'] 	= $td;
			
			$monthrows = array("Direct Cost");
			
			$pldirectcosts = array();
			
			foreach($allSalesDetails as $expDetails)
			{
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					$arrayCostSummation[$i][$counter]  = ($expDetails['financial_status'][$i]['total_per_yr'] * $expDetails['cost']);
				}
				
				for($i = 0; $i < 12; $i++){
				
					$stri = str_pad($i+1,2,"0",STR_PAD_LEFT);
					$monthrows[$i+1] += ($expDetails["month_" . $stri] * $expDetails['cost']);
					$pldirectcosts[$i] += $expDetails["month_" . $stri] * $expDetails['cost']; 
				
				}
				
				
				
				$counter = $counter+1;
			}// end foreach

			$this->profitlossdata['monthlydirectcostrows'] 	= $monthrows;
			
			
			$td = array("Direct Cost");

			$totalCostCounter = 0;
			foreach($arrayCostSummation as $sumOfAllCost)
			{
				$totalDirectCost[$totalCostCounter] = (array_sum($sumOfAllCost));
				$totalDirectCost_format[$totalCostCounter] = number_format(array_sum($sumOfAllCost), 0, '.', ',');
				$td[] = $sales->defaultCurrency.$totalDirectCost_format[$totalCostCounter];

				$totalCostCounter = $totalCostCounter + 1;
			}

			$this->profitlossdata['yearlydirectcostrows'] 	= $td;
			
			
			

			$grossMargin = $this->salesdata['grossMarginRaw'];
			
			$td = array("Gross Margin");
			
			foreach($grossMargin as $key=>$value){
				$td[] = $sales->defaultCurrency.number_format($value, 0, '.', ',');                   
				
			}
			

			

			$this->writeBudgetExpensesRows($table, $this->expensesdata['employeeexpenses']);

			//$grossMargin = array_slice($this->salesdata['gross_margin'], 1);
			//transform values to float
			// foreach($grossMargin as $key=>$value){
			//    $grossvalue = str_replace($sales->defaultCurrency, "", $value);
			//    $grossvalue = str_replace(",", "", $grossvalue);
			//    $grossMargin[$key] = floatval($grossvalue);
			
			// }
			
			
			//var_dump($grossMargin);
			
			$allExpense = $this->expensesdata['allExpense'];

			
			include_once(LIBRARY_PATH . '/pdf_calc.php');
			
			
			//<!--------------------        INTEREST INCURRED SECTION       ------------------------->
			$totalCostCounter = 0;
			$td = array("Operating Income");
			

			//var_dump($grossMargin);

			foreach($arrayCostSummation as $sumOfAllCost)
			{

				//$grossvalue = str_replace($sales->defaultCurrency, "", $grossMargin[$totalCostCounter+1]);
				//$grossvalue = str_replace(",", "", $grossvalue);
				//$grossvalue = floatval($grossvalue);

				$operatingIncome[$totalCostCounter] = ( $grossMargin[$totalCostCounter] - $allExpense[$totalCostCounter]);

				if($operatingIncome[$totalCostCounter] < 0)
				{
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}

				$td[] = $open_bracket . $sales->defaultCurrency.number_format(($operatingIncome[$totalCostCounter] * $cancelNegative ), 0, '.', ',') . $closed_bracket;

				$totalCostCounter = $totalCostCounter + 1;
			}
			//echo "income<br>";
			//var_dump($operatingIncome);
			

			//<!--------------------------------------------------------------------------->

			$this->profitlossdata['yearlyoperatingincomerows'] = $td; 
			
			
			//<!--------------------        INTEREST INCURRED SECTION       ------------------------->
			if(isset($_interestIncured))
			{
				$_loanInvestment = new loansInvestments_lib();
				$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
				$sumOfEachLoan = "";

				$td = array("Interest Incurred");

				$_yrlyCalcInterest = array();
				$array_interestIncuredCounter = 0;
				$array_interestIncured = array();
				$currency = $sales->defaultCurrency;
				if(isset($_interestIncured))
				{
					$_yrlyCalcInterest =  $_interestIncured;
				} else
				{
					$_yrlyCalcInterest = 0;
				}

				// loop through this for number of years
				foreach($_yrlyCalcInterest as $yrInterestIncured)
				{
					//print_r($yrInterestIncured);

					$array_interestIncured[$array_interestIncuredCounter] = array_sum($yrInterestIncured);

					if($array_interestIncured[$array_interestIncuredCounter] < 0)
					{
						$open_bracket  = OPEN_BRACKET;
						$closed_bracket  = CLOSED_BRACKET;
						$cancelNegative = -1;
					}
					else
					{
						$open_bracket  = "";
						$closed_bracket  = "";
						$cancelNegative = 1;
					}

					$td[] = $open_bracket . $sales->defaultCurrency . number_format(($array_interestIncured[$array_interestIncuredCounter] * $cancelNegative), 0, '.', ',') . $closed_bracket;

					$array_interestIncuredCounter = $array_interestIncuredCounter + 1;
				}
				
				
				$this->profitlossdata['yearlyinterestincurredrows'] = $td;
				

				

			} //end isset interestincurred



			//add depreciation and amortization
			$lib = new expenditure_lib();
			$numbersyrOfFinancialForecast = $lib->numberOfFinancialYrForcasting;	
			$major_purchases_details = $lib->getAllMajorPurchaseDetails('', 'mp_date');
			$years = array();
			
			$p = .20;
			
			$monthly_detail_purchases 	= array();
			$monthly_depreciation		= array(); 
			
			$monthlypurchases			= array();		
			$yearlypurchases			= array();
			
			
			
			foreach ($major_purchases_details as $purchase) {
				
				list($pm, $py) = explode(' ', $purchase['mp_date']);
				if ( !isset($years[$py])) {
					$years[$py] = 0;
				}

				
				$years[$py] += $purchase['mp_price'];
					
				if (!isset($monthly_detail_purchases[$purchase['mp_date']])) {
						$monthly_detail_purchases[$purchase['mp_date']] = $purchase['mp_price'];
				} else {
						$monthly_detail_purchases[$purchase['mp_date']] += $purchase['mp_price'];
				}
				
				
				
				if ($purchase['mp_depreciate']) {
					
					
					
					if (!isset($monthly_depreciation[$purchase['mp_date']])) {
							$monthly_depreciation[$purchase['mp_date']] = $purchase['mp_price']*$p/12;
					} else {
							$monthly_depreciation[$purchase['mp_date']] += $purchase['mp_price']*$p/12;
					}
				}
					
				if (isset($monthlypurchases[$purchase['mp_name']])) {
					
					$mp = $monthlypurchases[$purchase['mp_name']];
					
					if ( isset($mp[$purchase['mp_date']]) ) {
						$mp[$purchase['mp_date']] += $purchase['mp_price'];
					} else {
						$mp[$purchase['mp_date']] = $purchase['mp_price'];
					}
					
					$monthlypurchases[$purchase['mp_name']] = $mp;
					
				} else {
					
					$monthlypurchases[$purchase['mp_name']] = array();					
					$monthlypurchases[$purchase['mp_name']][$purchase['mp_date']] = $purchase['mp_price'];
					
				}
				//init yearly
				if (isset($yearlypurchases[$purchase['mp_name']])) {
						
					$mp = $yearlypurchases[$purchase['mp_name']];
						
					if ( isset($mp[$py]) ) {
						$mp[$py] += $purchase['mp_price'];
					} else {
						$mp[$py] = $purchase['mp_price'];
					}
						
					$yearlypurchases[$purchase['mp_name']] = $mp;
						
				} else {
						
					$yearlypurchases[$purchase['mp_name']] = array();
					$yearlypurchases[$purchase['mp_name']][$py] = $purchase['mp_price'];
						
				}
				
				
								
			}
			
			//highlight_string(var_export($monthlypurchases,true));
			
			$major_purchase = array_values($years);

			
			$financialYearSF = $sales->startFinancialYear;
			//$financialYearSF = $financialYearSF + 1;

			$td = array('Depreciation and Amortization');

			
			
			//initmonthly depreciation
				
			$tmpvaluerows 	= array('Depreciation and Amortization');
			$tmpvalues 		= array();
			$tmpvalues1		= array();
			$monthnames 	= array("Jan", "Feb", "Mar", "Apr", "May", "Jun",
					"Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
				
				
			$financialYearSF = $sales->startFinancialYear;
				
			//adjust month start depending on the plan
				
			if(isset($_SESSION['bpFinancialStartDate'])) {
				$startMonth = split(" ", $_SESSION['bpFinancialStartDate']);
			}
				
			//echo "hello " . $startMonth[0];
				
			$index = array_search($startMonth[0], $monthnames);
				
			$monthnamesfixed = array();
				
			for($i = 0; $i < 12; $i++) {
			
				if($index>11) {
					$index = 0;				}
			
				$monthnamesfixed[] = $monthnames[$index];
				$index++;
			}
				
				
			foreach($monthlypurchases as $key=>$value) {
				$indexedpurchases[$key] = array();
				$indexedpurchasesrows[$key] = array($key);
								
				$indexedypurchases[$key] = array();								
			}
			
			
				
			foreach($monthnamesfixed as $mm){
			
				if (isset($monthly_depreciation[$mm. " " .$financialYearSF])) {
					$tmpvaluerows[] = 	$monthly_depreciation[$mm. " " .$financialYearSF];
					$tmpvalues[]	=	$monthly_depreciation[$mm. " " .$financialYearSF];
				} else {
					$tmpvaluerows[] = 0;
					$tmpvalues[]	= 0;
				}
			
				if (isset($monthly_detail_purchases[$mm. " " .$financialYearSF])) {
					$tmpvalues1[]	= 	$monthly_detail_purchases[$mm. " " .$financialYearSF];
				} else {
					$tmpvalues1[]	= 0;
				}
				
				foreach($monthlypurchases as $key=>$value) {
					if ( isset( $value[$mm. " " .$financialYearSF]  ) ){
						$indexedpurchases[$key][] 		= $value[$mm. " " .$financialYearSF];
						$indexedpurchasesrows[$key][] 	= $value[$mm. " " .$financialYearSF];
					} else {
						$indexedpurchases[$key][] 		= 0;
						$indexedpurchasesrows[$key][] 	= 0;
					}
					
					
				}				
			}
			
			//highlight_string(var_export($indexedpurchases, true));
			
			
			$this->profitlossdata['monthlydepreciationrows'] 	= $tmpvaluerows;
			$this->profitlossdata['monthlydepreciation'] 		= $tmpvalues;
			$this->profitlossdata['monthly_detail_purchases'] 	= $tmpvalues1;
			$this->profitlossdata['monthlypurchases'] 			= $indexedpurchases;
			
			
			//highlight_string(var_export($tmpvalues, true));
			
			$monthly_accudepreciation = array();
			
			for($i = 0; $i < 12; $i++) {
				
				if ($i>0) {				
					for($j = 0; $j <= $i; $j++) {
						$monthly_accudepreciation[$i] += $tmpvalues[$j];
					}			
				} else {
					$monthly_accudepreciation[$i] = $tmpvalues[$i];
				}	
			}
			
			$monthly_balaccudepreciation = array();
			
			for($i = 0; $i < 12; $i++) {
				if($i>0) {
					$monthly_balaccudepreciation[$i] = $monthly_balaccudepreciation[$i-1] - $monthly_accudepreciation[$i];
				} else {
					$monthly_balaccudepreciation[$i] = $monthly_accudepreciation[$i];
				}				
			}
			
			
			$this->profitlossdata['monthly_accudepreciation'] 		= $monthly_accudepreciation;
			$this->profitlossdata['monthly_balaccudepreciation'] 	= $monthly_balaccudepreciation;
			
			//highlight_string(var_export($monthly_accudepreciation, true));
			
			
			$index = 0;
			
			$year1depreciation = array_sum($monthly_accudepreciation);
			
			

			

			
			//initialise yearly major purchases
			foreach($indexedypurchases as $key=>$value) {
				
				$financialYearSF = $sales->startFinancialYear;
				
				$index = 0;
							
				
				foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
				{

					if ($index == 0 ) {
						
						$indexedypurchases[$key][0] = array_sum($indexedpurchases[$key]);
						
					} else {
					
						if (isset($yearlypurchases[$key][$financialYearSF])) {
							$indexedypurchases[$key][] = $yearlypurchases[$key][$financialYearSF];
						} else {
							$indexedypurchases[$key][] = 0;
						}						
					}						
				
					$financialYearSF++;
					$index++;
				}
								
			}
			
			
			$this->profitlossdata['yearlypurchases'] 			= $indexedypurchases;
			
			//highlight_string(var_export($indexedypurchases, true));
			
			
			$financialYearSF = $sales->startFinancialYear;
			
			$index = 0;
			
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				$tmpval = isset($years[$financialYearSF])?$years[$financialYearSF]:0;
				$major_purchase[$index] = $tmpval;
				$financialYearSF++;
				$index++;
			}
			
			$bookvalues = array();
				
			$bookvalues[0] = array_sum($monthly_detail_purchases) - $year1depreciation;
						
			$financialYearSF = $sales->startFinancialYear;
			
			$index = 0;
			
			$yearlydepreciation 	= array();
			$yearlydepreciation[0] 	= $year1depreciation; 
			
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				if ($index > 0 ) {
					$yearlydepreciation[$index] = ($bookvalues[$index-1] + $yearlydepreciation[$index-1]) * $p;
					$bookvalues[$index]			= $bookvalues[$index-1] + $major_purchase[$index] - $yearlydepreciation[$index-1];
				}
				
				$index++;
				
				
			}
			
			$limit = count($allSalesDetails[0]['financial_status']);
			
			for($i = 0; $i < $limit; $i++) {
				$data[] = $yearlydepreciation[$i];
				$td[] = $yearlydepreciation[$i];
			}
			
			
			
			/*
			foreach ($allSalesDetails[0]['financial_status'] as $eachFinStat)
			{
				$tmpval = isset($years[$financialYearSF])?$years[$financialYearSF]:0;
				$major_purchase[$index] = $tmpval;
				$data[$financialYearSF+1] = $tmpval * $p;
				$td[] = $sales->defaultCurrency . $data[$financialYearSF];
				$financialYearSF++;
			}
			*/
					
			$this->profitlossdata['yearlymajor_purchase'] = $major_purchase;

			$this->profitlossdata['yearlydepreciation'] = $data;
			$this->profitlossdata['yearlydepreciationrows'] = $td;


			$array_depreciation = $data;
			$td = $this->farraynumber($td);
			
			
			$tmpvalues = array();
			$tmpvalues1 = array();
			$tmpvalues2 = array();
			
			
			for($i=0; $i<12;$i++) {
				if ($i > 0) {
					$tmpvalues[$i] = $this->profitlossdata['monthly_detail_purchases'][$i] + $tmpvalues[$i-1];
					$tmpvalues1[$i] = $this->profitlossdata['monthlydepreciation'][$i] + $tmpvalues1[$i-1];
					
				} else {
					$tmpvalues[$i] = $this->profitlossdata['monthly_detail_purchases'][$i];
					$tmpvalues1[$i] = $this->profitlossdata['monthlydepreciation'][$i];
				}	

				$tmpvalues1[$i] = -1 * abs($tmpvalues1[$i]);
				
				$tmpvalues2[$i] = $tmpvalues[$i] + $tmpvalues1[$i];
				
			}
			
			$this->profitlossdata['monthly_acculongtermassets'] = $tmpvalues;
			//$this->profitlossdata['monthly_accudepreciation'] 	= $tmpvalues1;
			$this->profitlossdata['monthly_totallongassets'] 	= $tmpvalues2;
			
			//end depreciation and amortization


			//<!--------------------       INCOME TAXES    ------------------------------------->
			$_loanInvestment = new loansInvestments_lib();
			$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");
			//print_r($allloanInvestmentProjection);
			$sumOfEachLoan = "";

			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;

			$td = array("Income Taxes");
			$array_incomeTax = array();

			if(isset($array_eachYrEstimatedIncomeTax))
			{
				$array_eachYrEstimatedIncomeTax = $array_eachYrEstimatedIncomeTax;
			}

			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				//print_r($yrInterestIncured);
				if(count($array_eachYrEstimatedIncomeTax) > 0)
				{
					$array_incomeTax[$e_yr] = $array_eachYrEstimatedIncomeTax[$e_yr];
				}
				else
				{
					$array_incomeTax[$e_yr] = 0;
				}

				if($array_incomeTax[$e_yr] < 0)
				{
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}

				$td[] = $open_bracket . $sales->defaultCurrency . number_format(($array_incomeTax[$e_yr] * $cancelNegative), 0, '.', ',') . $closed_bracket;

			}

			
			
			$this->profitlossdata['yearlyincometaxrows'] = $td;
			

			//<!--------------------------------------------------------------------------->
			//<!--------------------	NET PROFIT	----------------------------------------->
			$sumOfEachLoan = "";					
			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;    
			$td = array("Net Profit");		
			$array_netProfit = array();
			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				if((count($operatingIncome) > 0) and (count($array_interestIncured) > 0) and (count($array_incomeTax) > 0))
				{
					/*---	Take off any available comas	---*/
					$operatingIncome[$e_yr] = 			str_replace(",", "", $operatingIncome[$e_yr]);
					$array_interestIncured[$e_yr] = 	str_replace(",", "", $array_interestIncured[$e_yr]);
					$array_incomeTax[$e_yr] = 			str_replace(",", "", $array_incomeTax[$e_yr]);
					
					$array_netProfit[$e_yr] = ($operatingIncome[$e_yr] - ($array_interestIncured[$e_yr] + $array_incomeTax[$e_yr]+
					$array_depreciation[$e_yr]));
				}
				else
				{
					$array_netProfit[$e_yr] = 0;
				}
				
				//$array_netProfit[$e_yr] = ($array_netProfit[$e_yr] * -1);
				
				if($array_netProfit[$e_yr] < 0)
				{
					
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}
				
				
				$td[] = $open_bracket . $currency. number_format(($array_netProfit[$e_yr] * $cancelNegative), 0, '.', ',') . $closed_bracket;
				$td_raw[] = $array_netProfit[$e_yr];
			} 

			
			
			$this->profitlossdata['yearlynetprofitrows'] = $td;
			$this->profitlossdata['yearlynetprofit'] = $td_raw;
			
			//<!--------------------------------------------------------------------------->
			
			
			
			
			
			
			//<!--------------------	NET PROFIT / SALES	--------------------------------->
			$sumOfEachLoan = "";			
			$numbersyrOfFinancialForecast = $_loanInvestment->numberOfFinancialYrForcasting;
			
			$array_netProfitSales = array();
			$array_revenue = array();
			$array_revenue = $totalSales_format;
			
			$td = array('Net Profit/Sales');
			
			// loop through this for number of years
			for($e_yr = 0; $e_yr < $numbersyrOfFinancialForecast; $e_yr++ )
			{
				if((count($array_netProfit) > 0) and (count($array_revenue) > 0))
				{
					/*---	Take off any available comas	---*/
					$array_revenue[$e_yr] = 			str_replace(",", "", $array_revenue[$e_yr]);
					$array_netProfit[$e_yr] = 	str_replace(",", "", $array_netProfit[$e_yr]);
					
					$array_netProfitSales[$e_yr] = (($array_netProfit[$e_yr] / $array_revenue[$e_yr]) * 100);
				}
				else
				{
					$array_netProfitSales[$e_yr] = 0;
				}
				if($array_netProfitSales[$e_yr] < 0)
				{
					
					$open_bracket  = OPEN_BRACKET;
					$closed_bracket  = CLOSED_BRACKET;
					$cancelNegative = -1;
				}
				else
				{
					$open_bracket  = "";
					$closed_bracket  = "";
					$cancelNegative = 1;
				}
				
				
				$td[] = $open_bracket . number_format(($array_netProfitSales[$e_yr] * $cancelNegative), 0, '.', ',') . '%' . $closed_bracket;
				
			}
			
			
			
			//keep net profit dales in salesdata
			$this->salesdata['netprofitsales'] = $array_netProfitSales;
			$this->profitlossdata['yearlynetprofitsalesrows'] = $td;


			

		}

	}

	protected function renderBalanceSheet($page_repo, $pdf, $menu)
	{
		
		
		$this->writeBalanceSheetTable($pdf);
		
		
	}

	public function writeBalanceSheetTable($pdf) { //new calculation
		
		include(LIBRARY_PATH . '/pdf_calc.php');		
		include(LIBRARY_PATH . '/pdf_balance_calc.php');
		

		
		$table = new HTMLTable();
		
		$years 	= $this->salesdata['years'];
	
		//data is calculated in expenses
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		$yearlyTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
	
		
		

				
		$td = array(); //build empty row
		
		foreach($years as $yr){
			$td[] = '';
		}
		
		
		
		//data of the follwing is calculate in pdf_calc_cash 
		$balTotalCurrentAssets 	= $this->profitlossdata['ns']['balTotalCurrentAssets'];
		$balLongTermsAssets 	= $this->profitlossdata['ns']['balLongTermsAssets'];
		$balAccuDepreciation 	= $this->profitlossdata['ns']['balAccuDepreciation'];
		$balTotalLongTermsAssets = $this->profitlossdata['ns']['balTotalLongTermsAssets'];
		$balTotalAssets 		= $this->profitlossdata['ns']['balTotalAssets'];
		
		
		
		$yrAccountPayable = $this->balancesheetdata['balaccpayable'];
		
		
		
		$balTotalCurrentLiability = $this->profitlossdata['ns']['balTotalCurrentLiability'];
		
		
		$balLongTermDebt 	= $this->profitlossdata['ns']['balLongTermDebt'];
		$balTotaLiabilities	= $this->profitlossdata['ns']['balTotaLiabilities'];
		
		
		
		$balEarnings 			= $this->profitlossdata['ns']['balEarnings'];
		$balRetainedEarnings 	= $this->profitlossdata['ns']['balRetainedEarnings'];
		$balTotalOwnerEquity	= $this->profitlossdata['ns']['balTotalOwnerEquity'];
		$balTotalLiabilitiesAndEquities = $this->profitlossdata['ns']['balTotalLiabilitiesAndEquities'];
		
		
		
		
		
	}
	
		//Appendix

	protected function renderAppendixSalesForecast($page_repo, $pdf, $menu)
	{
		
		//echo highlight_string(var_export($this->graphdatabank->sales, TRUE));
		

		//data is calculated in renderSalesForecast function call          
		
		
		$months = $this->salesdata['months'];   
		$year 	= $this->salesdata['years'];
		
		$products = $this->salesdata['products'];
		
		
		foreach($products as $product) {
			
			$merged = array_merge(array($product['name']),
			$this->farraynumber($product['monthlyUnitSales']));
			
			
		}
			
		
		$unit_sales 		= $this->salesdata['yrlyUnitSales'];
		$price_per_unit 	= $this->salesdata['yrlyUnitPrices'];
		$product_sales 		= $this->salesdata['yrlyProdSales'];
		$total_sales		= $this->salesdata['yrlyTotalSales'];
		$direct_cost_per_unit = $this->salesdata['yrlyUnitCost'];
		$direct_cost		= $this->salesdata['yrlyCosts'];
		$total_direct_cost 	= $this->salesdata['yrlyTotalCosts'];
		$gross_margin 		= $this->salesdata['yrlyGrossMargin'];
		$gross_margin_percentage = $this->salesdata['yrlyGMPercentage'];
		
				
		$years 	= $this->salesdata['years'];
		
		

	}	


	protected function renderCashFlowStatement($page_repo, $pdf, $menu)
	{
		
		$this->writeCashFlowStatementTable($pdf);
		
	}

	private function writeCashFlowStatementTable($pdf)
	{
		
		// calculate the net cash flow from operations
		$years = count($this->profitlossdata['yearlynetprofit']);
		for ($i = 0; $i < $years; $i++) {
			$net[$i] = $this->profitlossdata['yearlynetprofit'][$i]
				+ $this->profitlossdata['yearlydepreciation'][$i]
				+ $this->balancesheetdata['accountreceivable'][$i]
				+ $this->balancesheetdata['accountpayable'][$i];
		}
		$this->profitlossdata['netcashflow'] = $net;

		
	}

	public function number($number, $decimal = 0)
	{
		return number_format($number, $decimal, '.', ',');
	}

	protected function percentage($number, $decimal = 0)
	{
		return number_format($number, $decimal, '.', ',') . '%';
	}

	protected function currency($sales_forecast_lib, $number)
	{
		return $sales_forecast_lib->defaultCurrency . $this->number($number, 2);
	}
	
	public function farraynumber($tarray) {
		
		$currency = $this->salesdata['currency'];
		//$currency = html_entity_decode($currency);
		
		foreach($tarray as $key=>$value) {
			
			$value = str_replace(array($currency,','), '', $value);
			
			if (is_numeric($value)) {
				if ($value < 0) {
					$tarray[$key] = "({$currency}" . $this->number($value * -1, 0) . ')';
				}
				else {
					$tarray[$key] = $currency . $this->number($value, 0);
				}
			} else {
				$tarray[$key] = $value;
			}	
		}
		
		return $tarray;
		
	}
	
	protected function farraypercent($tarray) {
		
		foreach($tarray as $key=>$value) {
			$tarray[$key] = $this->percentage($value, 0);
		}
		
		return $tarray;
		
	}
	

	protected function renderImage($pdf, $label, $fname, $data = null, $type="single")
	{
		$pdf->setJPEGQuality(100);

		//echo '<br><br>'.$label.'<br><br>';
		//var_dump($data);

		if($type=="single") {
			$img = $this->grapher->genSngBar($data);
		} else if ($type=="accumulated") {
			$img = $this->grapher->genAccuBar($data);
		} else {
			$img = $this->grapher->genDblBar($data);
		}

		$filename = GRAPH_IMAGES_PATH . "PDF" . $fname;
		
		$filename = ($fname == null? null: $filename);
		
		ob_start();
		imagepng($img, $filename, 5);
		$imageData = ob_get_contents();
		ob_end_clean();

		$pdf->Bookmark($label, 2, 0, '', '', array(0,0,0));
		//$img =  '<img style="margin:auto; width: 600; " src="data:image/png;base64,'.base64_encode($imageData).'" />';
		$img =  '<img style="margin:auto; width: 600; " src="'. GRAPH_IMAGES_PATH . "PDF" . $fname .'" />';
		
		
		$h = '<div style="page-break-inside:avoid;"><p style="font-family: rock; font-size: 10;">'. $label . '</p>';
		$pdf->writeHTMLCell(0, 0, '', '', $h . '<div style="text-align:center; width: 100%;">' . $img . '</div></div>', 0, 1, 0, true, '', true);

	}
	
	protected function renderAppendixPersonnelPlan($page_repo, $pdf, $menu)
	{
		
		
		$employees 			= $this->employeedata['employees'];
		$monthlysalarytotal	= $this->employeedata['monthlysalarytotal'];
		
		
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
		
		
		
		$monthlysalarytotal =  $this->employeedata['monthlysalarytotal'];
		
		
		
		$years 	= $this->salesdata['years'];
		
		
		
		
	}
	
	protected function renderAppendixBudget($page_repo, $pdf, $menu)
	{
					

		
	}
	
	protected function initExpenses(&$allExpDetails, &$allEmpDetails, &$arraySummation, &$yearexpenses, &$expensesrows, $expenditure, $employee)
	{
		$counter = 0;
		$arraySummation = "";
		// Related Expenses calculation
		(int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];
		$personalRelatedExpenseInPercentage = ($personalRelatedExpenses / 100);
		$this->salesdata['personalRelatedExpenseInPercentage'] = $personalRelatedExpenseInPercentage;
		/*---------------------------------------------------------------------
			Employee Salary Calculation loop using the same counter and
		array summation for both allEmployee and all exenditure
		---------------------------------------------------------------------*/
		if($allEmpDetails)
		{
			foreach($allEmpDetails as $empDetails)
			{
				//$empDetails = number_format($empDetails);
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					$arraySummation[$i][$counter]  = ($empDetails['financial_status'][$i]['total_per_yr']);
				}
				$counter = $counter+1;
			}
			
			//<!-------------       SALARY  -------------->
			
			$salary = array();
			$empExpenses = array();
			
			foreach($arraySummation as $total)
			{
				$salary[] = $employee->defaultCurrency.number_format(array_sum($total), 0, '.', ',');
				$empExpenses[] = $employee->defaultCurrency.number_format($personalRelatedExpenseInPercentage * array_sum($total), 0, '.', ',');
			}
			
			
			
			$expensesrows['Salary'] = $salary;
			$expensesrows['Employee Related Expenses'] = $empExpenses;
			
			/*---------------------------------------------------------------------
				Calculate Employee Related Expenses
			---------------------------------------------------------------------*/
			
			foreach($allEmpDetails as $empDetails)
			{
				for($i=0; $i< count($empDetails['financial_status']); $i++)
				{
					$arraySummation[$i][$counter]  = ($personalRelatedExpenseInPercentage * $empDetails['financial_status'][$i]['total_per_yr']);
				}
				$counter = $counter+1;
			}
			
			
			
			//init monthly data
			$monthlytotalsalary 			= array();
			$monthlytotalrelatedexpenses 	= array();
			$monthlytotalexpenses 			= array();
			
			
			
			foreach($allEmpDetails as $empDetails)
			{
				for ($counter = 0; $counter < 12; $counter++)
				{
					$counterstr 						= str_pad($counter+1,2,"0",STR_PAD_LEFT);
					$monthlytotalsalary[$counter] 			+= $empDetails['month_' . $counterstr];
					$monthlytotalrelatedexpenses[$counter] 	+= ($personalRelatedExpenseInPercentage * $empDetails['month_' . $counterstr]);
					$monthlytotalexpenses[$counter] 	= $monthlytotalsalary[$counter] + $monthlytotalrelatedexpenses[$counter];
				}
				
				
				
			}
			
			
			
			//echo highlight_string(var_export($monthlyrelatedexpenses, TRUE));
			$this->expensesdata['monthlytotalsalary'] 			= $monthlytotalsalary;
			$this->expensesdata['monthlytotalrelatedexpenses'] 	= $monthlytotalrelatedexpenses;
			
			
			
			//init yearly data
			
			$yearlyTotalSalary 		= array();
			$yearlyTotalRSalary 	= array();
			$yearlyTotalExpenses 	= array();
			
			foreach($allEmpDetails as $empDetails)
			{
				$counter = 0;
				
				foreach($empDetails['financial_status'] as $yearexpense)
				{
					$yearlyTotalSalary[$counter] 	+= $yearexpense['total_per_yr'];					
					$counter++;					
				}
				
			}
			$counter = 0;
			
			foreach($yearlyTotalSalary as $yearsalaray)
			{
				
				
				$yearlyTotalRSalary[$counter] 	= $yearlyTotalSalary[$counter]*$personalRelatedExpenseInPercentage;
				$yearlyTotalExpenses[$counter] 	= $yearlyTotalSalary[$counter] + $yearlyTotalRSalary[$counter];
				
				$counter++;
			}
			
			
			//highlight_string(var_export($yearlyTotalSalary, true));
			//highlight_string(var_export($yearlyTotalRSalary, true));
			
			$this->expensesdata['yearlyTotalSalary'] 	= $yearlyTotalSalary;
			$this->expensesdata['yearlyTotalRSalary'] 	= $yearlyTotalRSalary;
			
			
			
			
			/*---------------------------------------------------
			Expenditure  Calculation loop
			/*-----------------------------------------------*/
			
			//highlight_string(var_export($allEmpDetails, true));
			
			$monthlyotherexpenses = array();
			$index = 0;
			foreach($allExpDetails as $expDetails)
			{
				
				$tmparray = array();
				$j = 0;
				foreach($expDetails['financial_status'] as $finDetails)
				{
					$tmparray[] = $expenditure->defaultCurrency.number_format($finDetails['total_per_yr'], 0, '.', ',');
					$yearlyTotalExpenses[$j] += $finDetails['total_per_yr'];
					$j++;
				}
				
				$expensesrows[$expDetails['expenditure_name']] = $tmparray;
				
				$monthlyotherexpenses[$expDetails['expenditure_name']] = array();
				
				for( $i=0; $i < 12; $i++ ) 
				{
					$key = str_pad($i+1, 2, '0', STR_PAD_LEFT);
					$monthlyotherexpenses[$expDetails['expenditure_name']][$i] = $expDetails['month_' . $key];
					$monthlytotalexpenses[$i] += $expDetails['month_' . $key];
					$monthlytotalexpenses[$i] += $personalRelatedExpenseInPercentage * $monthlyotherexpenses[$expDetails['expenditure_name']][$i];
				}  
				
				
				for($i=0; $i< count($expDetails['financial_status']); $i++)
				{
					$arraySummation[$i][$counter]  = $expDetails['financial_status'][$i]['total_per_yr'];
				}
				
				$counter = $counter+1;
				
				$index++;
				
			}
			
			//echo highlight_string(var_export($monthlytotalexpenses, TRUE));
			$this->expensesdata['yearlyTotalExpenses'] 		= $yearlyTotalExpenses;
			$this->expensesdata['yearlyexpenses'] 			= $expensesrows;
			$this->expensesdata['monthlyotherexpenses'] 	= $monthlyotherexpenses;
			$this->expensesdata['monthlytotalexpenses'] 	= $monthlytotalexpenses;
			
			
			
			//expenses by year
			
			$y = 0;
			$allExpense     = array();
			$tmparray       = array();
			
			foreach($arraySummation as $sumOfAllExpenses)
			{
				$allExpense[$y] = array_sum($sumOfAllExpenses);
				
				$yearexpenses[$allExpDetails[0]['financial_status'][$y]['financial_year']] = $allExpense[$y];
				$tmparray[] = $expenditure->defaultCurrency.number_format($allExpense[$y], 0, '.', ',');
				$y = $y+1;
			}
			
			$expensesrows["Total Operating Expenses"] = $tmparray;
			
			$this->expensesdata['allExpense'] = $allExpense;
			
			
			
		}
		
		
		
		
		
					
		
		
	}
	
	
	
	protected function renderAppendixLoansandInvestments($page_repo, $pdf, $menu)
	{
		


	
	}
	
	
	protected function renderAppendixProfitandLossStatement($page_repo, $pdf, $menu)
	{
		
	
		$monthrows 			= $this->profitlossdata['monthlyrevenuerows'];
		
	
		
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
	
		
	
		$monthrows = $this->profitlossdata['monthlydirectcostrows'];
		
		
		
		
		
		
		
		$monthrows = $this->salesdata['monthlyGrossMargin'];
		$monthrows = array_merge( array('Gross Margin') , $monthrows);
		
		
		
				
		$monthrows = $this->salesdata['monthlyGrossMPercentage'];
		$monthrows = array_merge( array('Gross Margin %') , $monthrows);
		
		
		
		//data is calculated in expenses
		$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];				
		
		
		$monthlyotherexpenses = $this->expensesdata['monthlyotherexpenses'];
		
		
		
		
		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		
		
		
		$monthrows = $this->salesdata['monthlyGrossMargin'];
		
		$tmprows = array();
		
		for($i = 0; $i < 12; $i++){		
			$tmprows[$i] = $monthrows[$i] -  $monthlytotalexpenses[$i];
		}
		
		
		$monthlyoperatingincome = $tmprows;
		
		
		
		$monthlyrows = $this->profitlossdata['monthlyinterestincurredrows'];
		$monthlyrows = array_merge(array('Interest Incurred'), $this->farraynumber($monthlyrows));
		
		
		
		
		//add depreciation		
		$monthlyvals = $this->profitlossdata['monthly_accudepreciation'];
		
		//$this->profitlossdata['monthly_accudepreciation']
		
		$monthlyrows = array_merge(array('Depreciation and Amortization'), $this->farraynumber($monthlyvals));
		
		
		//calculate monthtly incometax
		//loop through this for number of years
		
		$monthlygrossmargin 		= $this->salesdata['monthlyGrossMargin'];
		$montylyinterestincurred 	= $this->profitlossdata['monthlyinterestincurredrows'];
		$monthlyrows = array();
		$tmprows	 = array();
		
		$expenditure    = new expenditure_lib();
		$incomeTaxRate  =  $expenditure->incomeTaxRate;
		
		$tmptotalexpense = array();
		
		$depreciation = $this->profitlossdata['monthly_accudepreciation'];
		
		//echo "tax rate: " . $incomeTaxRate;
		
		for($i = 0; $i < 12; $i++)
		{
		$totalexpense 	= $monthlytotalexpenses[$i];
		$grossmargin 	= $monthlygrossmargin[$i];
		$interestincur	= $montylyinterestincurred[$i];
			
		$monthlyrows[$i] = ($grossmargin - $totalexpense - $interestincur);		//operating income * taxrate/100
		$tmprows[$i] = (($monthlyoperatingincome[$i] * $incomeTaxRate) / 100);
			
		
		if($monthlyrows[$i] < 0) {
		$monthlyrows[$i] = 0;
		}
		
		$tmptotalexpense[$i] = $interestincur + $tmprows[$i] + $depreciation[$i]; //income tax + interest incurred + depreciation
			
		}
		
		$monthlyincometaxes = $tmptotalexpense;
		
		$this->profitlossdata['monthlyincometax'] = $tmprows;
		
		
		$monthlyrows = array_merge(array('Income Taxes'), $this->farraynumber( $tmprows ));
		
		
				//calculate net profit
		
		
					
				$monthlydirectcost	= array_slice($this->profitlossdata['monthlydirectcostrows'], 1);
		
				$revenue = $this->profitlossdata['monthlyrevenuerows'];
		
				$tmprows	 = array();
				$monthlyrows = array();
		
		
				$pltotalexpense = array();
		
				for($i = 0; $i < 12; $i++)
				{
				$expense 			= $monthlydirectcost[$i] + $monthlytotalexpenses[$i] + $monthlyincometaxes[$i];
				$pltotalexpense[]	= $expense;
				$monthlyrows[$i] 	= $revenue[$i+1] - $expense;
				$tmprows[$i] 		= $revenue[$i+1] > 0 ? ($monthlyrows[$i] / $revenue[$i+1] * 100) : 0 ;
		}
		
		$this->profitlossdata['monthlynetprofit'] = $monthlyrows;
		//highlight_string(var_export($revenue,true));
		//highlight_string(var_export($monthlytotalexpenses,true));
		//highlight_string(var_export($monthlyincometaxes,true));
		
		$this->expensesdata['balYearlyTotalExpenses'] = $pltotalexpense;
		
		
		//highlight_string(var_export($this->expensesdata['balYearlyTotalExpenses'],true));
		
		//TOTAL EXPENSE
				
		
		
		$this->salesdata['netprofit'] = $monthlyrows;
		
		
		
		
		
		
		$years 	= $this->salesdata['years'];
	
		
		$tmprows = $this->profitlossdata['yearlyrevenuerows'];		
	
		
		
		$tmprows = $this->profitlossdata['yearlydirectcostrows'];
		
				
		
		$tmprows = $this->salesdata['grossMarginRaw'];
		$tmprows = array_merge( array('Gross Margin'), $tmprows); 
		
		
		
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		
		
		
		$yearlyOperatingTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
		
		
		$tmprows = $this->profitlossdata['yearlyoperatingincomerows'];
		
		
		$TotalExpenses = array();
		
		$this->profitlossdata['yearlyinterestincurredrows'][1] = $this->number(array_sum($montylyinterestincurred));
		
		for($i = 1; $i < 4; $i++ ) {
			//direct cost
			$dcost = str_replace(array($this->salesdata['currency'],','), '', $this->profitlossdata['yearlydirectcostrows'][$i]);
			$dcost = floatval($dcost);
			$iincur = str_replace(array($this->salesdata['currency'],',','(',')'), '', $this->profitlossdata['yearlyinterestincurredrows'][$i]);			
			$iincur = floatval($iincur);
			$dep	= $this->profitlossdata['yearlydepreciation'][$i-1];
			$itax 	= str_replace(array($this->salesdata['currency'],','), '', $this->profitlossdata['yearlyincometaxrows'][$i]);
			$itax	= floatval($itax);
			
			$totaloperatingexpense = $yearlyOperatingTotalExpenses[$i-1]; 
			
			$TotalExpenses[] = $dcost + $iincur + $dep + $itax + $totaloperatingexpense;
		}
		
		
		//echo highlight_string(var_export($TotalExpenses, TRUE));
		//echo highlight_string(var_export($this->profitlossdata['yearlyinterestincurredrows'], TRUE));
		//echo highlight_string(var_export($this->profitlossdata['yearlydirectcostrows'], TRUE));
		//echo highlight_string(var_export($this->profitlossdata['yearlydepreciation'], TRUE));		
		//echo highlight_string(var_export($this->profitlossdata['yearlyincometaxrows'], TRUE));
		
		$this->expensesdata['balYearlyTotalExpenses'] = $TotalExpenses;
		
		
		
		$tmprows = $this->profitlossdata['yearlyinterestincurredrows'];
		
		
		
		$tmpvals  = $this->profitlossdata['yearlydepreciation'];
		$tmprows = array_merge(array('Depreciation and Amortization'),$this->farraynumber($tmpvals));
		
		$tmprows = $this->profitlossdata['yearlyincometaxrows'];
				
		
		$tmprows = $this->profitlossdata['yearlynetprofitrows'];
		
		
		$tmprows = $this->profitlossdata['yearlynetprofitsalesrows'];
		
		
		
	
	}
	
	protected function renderAppendixBalanceSheet($page_repo, $pdf, $menu)
	{
	
			
		
		
	
		//data is calculated in expenses
		$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];
		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		
	
		
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
					
		
		
		$monthlycash 					= $this->loansdata['monthlycash'];
		$accountReceivable_allMonths 	= $this->loansdata['accountReceivable_allMonths'];
		
		
		$AccountReceivable = $this->expensesdata['TotalAccountsReceivable'];
		
		
		$totalcurrentassets_monthly = $this->loansdata['totalcurrentassets_monthly'];
				
		
		
		$monthly_acculongtermassets = $this->profitlossdata['monthly_acculongtermassets'];
		
				
		
		$monthly_accudepreciation = $this->profitlossdata['monthly_balaccudepreciation'];
		
		
		$monthly_totallongassets = $this->profitlossdata['monthly_totallongassets'];
		
		
		//calculate monthly total assets
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $totalcurrentassets_monthly[$i] + 	$monthly_totallongassets[$i];				
		}
		
		
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $this->loansdata['$monthlyreceive'][$i] - 	$this->loansdata['$monthlypayment'][$i];
		}
		
		
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			//$tmpvalues[$i] = $this->loansdata['MonthlyAccountsPayable'][$i] + 	$this->loansdata['$monthlypayment'][$i];
			$tmpvalues[$i] = $this->loansdata['MonthlyAccountsPayable'][$i];
		}
		
		
		
		$monthlyrows = $this->salesdata['netprofit'];

		
		
		
		
		
		$retainedEarning 	= array();
		$retainedEarning[0] = 0; 
		for ($i = 1; $i < 12; $i++ ) {
			$retainedEarning[$i] = $retainedEarning[$i-1] + $monthlyrows[$i-1];		
		}
		
		
		
		$totalequity = array();
		for ($i = 0; $i < 12; $i++ ) {
			$totalequity[$i] = $retainedEarning[$i] + $monthlyrows[$i];
		}
		
		
		$totalliabilityequity = array();
		
		for ($i = 0; $i < 12; $i++ ) {
			$totalliabilityequity[$i] = $totalequity[$i] + $tmpvalues[$i];
		}
		
		
		
		
	
	
		$years 	= $this->salesdata['years'];
	
		//data is calculated in expenses
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		$yearlyTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
	
		
		
		//data of the follwing is calculate in pdf_calc_cash 
		$balTotalCurrentAssets 	= $this->profitlossdata['ns']['balTotalCurrentAssets'];
		$balLongTermsAssets 	= $this->profitlossdata['ns']['balLongTermsAssets'];
		$balAccuDepreciation 	= $this->profitlossdata['ns']['balAccuDepreciation'];
		$balTotalLongTermsAssets = $this->profitlossdata['ns']['balTotalLongTermsAssets'];
		$balTotalAssets 		= $this->profitlossdata['ns']['balTotalAssets'];
		
		
		
		$yrAccountPayable = $this->balancesheetdata['balaccpayable'];
		
		
		$balTotalCurrentLiability = $this->profitlossdata['ns']['balTotalCurrentLiability'];
		
		
		$balLongTermDebt 	= $this->profitlossdata['ns']['balLongTermDebt'];
		$balTotaLiabilities	= $this->profitlossdata['ns']['balTotaLiabilities'];
		
		
		$balEarnings 			= $this->profitlossdata['ns']['balEarnings'];
		$balRetainedEarnings 	= $this->profitlossdata['ns']['balRetainedEarnings'];
		$balTotalOwnerEquity	= $this->profitlossdata['ns']['balTotalOwnerEquity'];
		$balTotalLiabilitiesAndEquities = $this->profitlossdata['ns']['balTotalLiabilitiesAndEquities'];
		
		
		
	}

	protected function renderAppendixCashFlowStatement($page_repo, $pdf, $menu)
	{
		
	}

	protected function writeAppendixCashFlowStatementTable($pdf)
	{
		

		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
	
		$total = count($this->profitlossdata['monthlynetprofit']);
		$operations = array();
		//var_dump($this->profitlossdata['monthlynetprofit']); die();
		for ($i = 0; $i < $total; $i++) {
			$operations[] = $this->profitlossdata['monthlynetprofit'][$i]
				+ $this->profitlossdata['monthlydepreciation'][$i]
				+ $this->loansdata['accountReceivable_allMonths'][$i];
		}
		$this->cashflowdata['operations'] = $operations;

		
		// cash at end of period
		// let us assume net cash flow from investing and financing 
		// todo
		$investing_and_finance = array();

		$net_change = array();
		$end_of_period = array();
		$beginning_of_period = array();
		for ($i = 0; $i < $total; $i++) {
			$net_change[$i] = $operations[$i] + $investing_and_finance[$i];

			if ($i == 0) {
				$beginning_of_period[$i] = 0;
			}
			else {
				$beginning_of_period[$i] = $end_of_period[$i - 1];
			}

			$end_of_period[$i] = $beginning_of_period[$i] + $net_change[$i];
		}
		
	}

	
}


