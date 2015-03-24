<?php

require_once(LIBRARY_PATH . '/pdf.php'); 
require_once(LIBRARY_PATH . '/jpgraph_lib.php');
require_once(LIBRARY_PATH . '/web_calc_full.php');

class BizPlannerProPDF extends PDFHandler {

	protected $grapher 		= null;
	protected $graphdatabank = null;
	protected $salesdata 	= array();
	protected $expensesdata = array();
	protected $employeedata = array();
	protected $loansdata	= array();
	protected $profitlossdata = array();
	protected $balancesheetdata = array();
	protected $cashflowdata = array();
	protected $oWebcalc		= null;
	
//$oWebcalc->build();
//$oWebcalc->displayData();
	
	
	public function __construct()
	{
		$title = '';
		$this->grapher = new GraphHandler();
		$this->graphdatabank = new GraphDataHandler();
		$this->profitlossdata['ns'] = array();
		$this->oWebcalc = new WebCalcFull();
		$this->oWebcalc->build();
		parent::__construct($title);
	}

	public function build()
	{
		$page_repo = new page_lib();
		$user = $page_repo->getCurrentUser();
		$user = $user[0];

		$this->buildCover($user);

		$top_menus = $page_repo->topMenus();

		$this->pdf->setImageScale(1.53);
		foreach ($top_menus as $top_menu) {

			$title 		= $top_menu['pagetitle'];
			$orientation 	= ($title!="Appendix"?"P":"L");

			$this->pdf->writeHeader($top_menu['pagetitle'], $orientation);

			$this->addSubMenu($page_repo, $this->pdf, $top_menu);
		}

		$this->buildTOC();
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
					$pdf->AddPage('L'); 
				}
				
				
			}
			$this->renderPageTitle($page_repo, $pdf, $menu);
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
		foreach ($page_repo->getSections($menu) as $section) {
			$pdf->writeH3($section['section_title']);
			$pdf->writeHTML(htmlspecialchars_decode($section['section_content']), true, false, false, false, 'L');
			$pdf->Ln(4);
		}
	}

	protected function renderPageTitle($page_repo, $pdf, $menu)
	{
		$pdf->writeSubHeader($menu['pagetitle']);
	}

	protected function renderPageContent($page_repo, $pdf, $menu)
	{
		if ($menu['page_content']) {
			$pdf->writeHTML(htmlspecialchars_decode($menu['page_content']), true, false, false, false, 'L');
			$pdf->Ln(4);
		}
	}

	// STRATEGY AND IMPLEMENTATION

	protected function renderMarketing($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Marketing');
		$this->renderDefault($page_repo, $pdf, $menu);
	}

	protected function renderSWOTAnalysis($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('SWOT Analysis');
		$this->renderDefault($page_repo, $pdf, $menu);
	}

	// FINANCIAL PLAN

	protected function renderSalesForecast($page_repo, $pdf, $menu)
	{
		$pdf->Ln(1);
		
		
		$sales_forecast_lib = new sales_forecast_lib();
		$sales = $sales_forecast_lib->getAllSales('', '', '');
		
		
		
		
		$thtml = new HTMLTable();
		
		$pdf->writeH3('Sales Forecast Table');
		$pdf->Ln(3);

		$fy_start = $sales_forecast_lib->startFinancialYear;
		$th = array('');
		foreach ($sales[0]['financial_status'] as $each_fin_stat) {
			$th[] = 'FY' . ++$fy_start;
		}
		$this->th = $th;
		$thtml->addTHRow($th);

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
		
		
		

		$thtml->add1ColRow('Unit Sales','bold', 'left', 'normal','4');
		foreach ($unit_sales as $row) {
			$thtml->addTDRow($row, null, true);
		}

		$thtml->add1ColRow('Price Per Unit','bold', 'left', 'normal','4');
		foreach ($price_per_unit as $row) {
			$thtml->addTDRow($row, null, true);
		}

		$thtml->add1ColRow('Sales','bold', 'left', 'normal','4');
		foreach ($product_sales as $row) {
			$thtml->addTDRow($row, null, true);
		}

		$thtml->addTDRow($total_sales, array('t'=>'total','s'=>'bold'));

		$thtml->add1ColRow('Direct Cost Per Unit','bold', 'left', 'normal','4');
		foreach ($direct_cost_per_unit as $row) {
			$thtml->addTDRow($row, null, true);
		}

		$thtml->add1ColRow('Direct Cost','bold', 'left', 'normal','4');
		foreach ($direct_cost as $row) {
			$thtml->addTDRow($row, null, true);
		}

		$thtml->addTDRow($total_direct_cost, array('t'=>'total','s'=>'bold'));

		//keep a reference to sales data to be used later on
		$this->salesdata['gross_margin'] = $gross_margin;
		$this->salesdata['gross_margin_percentage'] = $gross_margin_percentage;

		$this->writeGrossMarginRows($this->salesdata['gross_margin'], $this->salesdata['gross_margin_percentage'], $thtml);

		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');

		//echo highlight_string(var_export($thtml->getHTML(), TRUE));
		//echo '<br>';
		


		//keep data to graphdatabank
		$this->graphdatabank->salesobj  = $sales_forecast_lib;
		$this->graphdatabank->sales     = $sales;
		$this->graphdatabank->totalsales = $totalSale;
		$this->graphdatabank->grossmargin = $gross_margin_percentage;

		//var_dump($this->graphdatabank);

		//handle sales(revenue) by year
		$data = $this->graphdatabank->getSalesByYearGraphData();
		$data['title'] = '';
		$this->renderImage($pdf, 'Sales By Year', 'yearly_graph_sales.png', $data);

		//handle sales(revenue) by month
		$data = $this->graphdatabank->getSalesByMonthGraphData();
		$data['title'] = 'Months in Year 1';
		$this->renderImage($pdf, 'Sales By Month', 'monthly_graph_sales.png', $data);

		//handle gross by year
		$data = $this->graphdatabank->getSalesGrossByYearGraphData();
		$data['title'] = '';
		$this->renderImage($pdf, 'Gross Margin(%) By Year', 'yearly_graph_gross_margin.png', $data);

		//handle gross by month
		$data = $this->graphdatabank->getSalesGrossByMonthGraphData();
		$data['title'] = 'Months in Year 1';
		$this->renderImage($pdf, 'Gross Margin(%) By Month', 'monthly_graph_gross_margin.png', $data);

		$pdf->writeH3('Sales Forecast');
		$this->renderPageContent($page_repo, $pdf, $menu);


		

	}

	private function writeGrossMarginRows($gross_margin, $gross_margin_percentage, &$table, $long = null)
	{
		if ($long==null) {
			
			$table->addTDRow($gross_margin, null, true);
			$table->addTDRow($gross_margin_percentage, array('t'=>'total','s'=>'bold'));
		} else {
			$table->addLTDRow($gross_margin, null, true);
			$table->addLTDRow($gross_margin_percentage, array('t'=>'total','s'=>'bold'));
		}
		
	}

	protected function renderHumanResources($page_repo, $pdf, $menu)
	{
		/*
				$th = array('');
				foreach ($allEmpDetails[0]['financial_status'] as $eachFinStat) {
						$th[] = 'FY' . $eachFinStat['financial_plan'];
				}
				*/
		$pdf->writeH3('Personal Plan');
		$pdf->Ln(1.5);
		$this->writePPlanTable($pdf);
		
		$pdf->Ln(2);
		$pdf->writeH3('About the Human Resources');
		$this->renderPageContent($page_repo, $pdf, $menu);
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
		
		$table = new HTMLTable();
		$table->addTHRow($years);
		
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
			
			$table->addTDRow($td);
			
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
		
		$table->addTDRow($td, array('t'=>'total','s'=>'bold'));
		
		
		
		
		
		$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');
		
		
	}
	
	

	protected function renderBudget($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Budget Table');

		$expenditure    = new expenditure_lib();
		$employee       = new employee_lib();
		$allExpDetails  = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures
		$allEmpDetails  = $employee->getAllEmployeeDetails2("", "", ""); // All employees
		$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation
		$allPurDetails  = $expenditure->getAllMajorPurchaseDetails("", "", ""); // All Expenditures
		$arraySummation = array();
		$yearexpenses   = array();

		$empexpenses    = array();
		
		$this->initExpenses($allExpDetails, $allEmpDetails, $arraySummation, $yearexpenses, $empexpenses, $expenditure, $employee);
		$this->expensesdata = array_merge($this->expensesdata, $expenditure->calculateMajorPurchases());
		
		$this->expensesdata['employeeexpenses'] = $empexpenses;

		//keep data to graphdatabank
		$this->graphdatabank->employee                  = $employee;
		$this->graphdatabank->allEmpDetails             = $allEmpDetails;
		$this->graphdatabank->allExpDetails             = $allExpDetails;
		$this->graphdatabank->personalRelatedExpenses   = $personalRelatedExpenses;
		$this->graphdatabank->yearexpenses              = $yearexpenses;


		$pdf->Ln(1.5);
		
		$this->writeBudgetTable($pdf );
		

		//var_dump($arraySummation);

		// Related Expenses calculation
		(int)$personalRelatedExpenses = $_SESSION['bpRelatedExpensesInPercentage'];

		//get monthly expenses

		$data = $this->graphdatabank->getExpensesByMonthGraphData();
		$data['title'] = '';

		//show accumulated bar type of graph
		//use dummy data for direct cost
		//$data2y = array(1200, 5400, 6412, 2461, 4577, 6441, 6464, 946, 7451, 4678, 7466, 4785);
		//$data['data1y'] = $data['datay'];
		//$data['data2y'] = $data2y;

		//unset($data['datay']);
		
		
		

		//$this->renderImage($pdf, 'Expenses By Month', 'monthly_graph.png', $data, 'accumulated');
		$this->renderImage($pdf, 'Expenses By Month', 'monthly_graph.png', $data);

		//end render of graph for expenses by month

		//get yearly expenses data
		$data = $this->graphdatabank->getExpensesByYearGraphData();
		$data['title'] = '';

		$this->renderImage($pdf, 'Expenses By Year', 'yearly_graph.png', $data);

		$pdf->writeH3('About The Budget');
		$this->renderPageContent($page_repo, $pdf, $menu);

	}


	private function writeBudgetTable($pdf ) {
		
		$allExpDetails = $this->graphdatabank->allExpDetails;

		$years = array('');
		
		foreach ($allExpDetails[0]['financial_status'] as $eachFinStat)
		{

			$years[] = "FY" . $eachFinStat['financial_year'];

		}
		
		$table = new HTMLTable();
		$table->addTHRow($years);
		
		//$empexpenses needs be calculated before this function call in function initExpenses
		$empexpenses = $this->expensesdata['employeeexpenses'];
		$yearlymajorpurchases = $this->expensesdata['yearlymajorpurchases'];
		$yearlytotalmajorpurchases = $this->expensesdata['yearlytotalmajorpurchasesrows'];
		
		$this->writeBudgetExpensesRows($table, $empexpenses);
		$this->writeBudgetMajorPurchasesRows($table, $yearlymajorpurchases, $yearlytotalmajorpurchases);
		
		$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');
		
		
	}

	private function writeBudgetExpensesRows(&$table, $expenses){
		$table->add1ColRow('Operating Expenses','bold', 'left', 'normal','4');

		foreach($expenses as $key=>$value) {
			$tmparray = array_merge(array($key), $value);

			if ($key != "Total Operating Expenses") {
				$table->addTDRow($tmparray, null, true);
			} else {
				$table->addTDRow($tmparray, array('t'=>'total','s'=>'bold'));
			}

		}
	}

	private function writeBudgetMajorPurchasesRows(&$table, $yearlymajorpurchases, $yearlytotalmajorpurchases) {
		$table->add1ColRow('Major Purchases','bold', 'left', 'normal','4');

		foreach($yearlymajorpurchases as $data) {
			$table->addTDRow($data, null, true);
		}
		
		$table->addTDRow($yearlytotalmajorpurchases, array('t'=>'total','s'=>'bold'));
	}

	protected function renderCashFlowProjections($page_repo, $pdf, $menu)
	{
		$pdf->Ln(1);
		$pdf->writeH3('Cash Flow Projections');
		$pdf->Ln(1.5);
		//begin cashflow projection table
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			$cashFlow = new cashFlowProjection_lib();
			$getPayments = $cashFlow->Payments($businessPlanId);
			$this->writeCashFlowTable($getPayments, $pdf);

		}

		//end cashflow projection table

		$pdf->writeH3('About Cash Flow Projections');
		$this->renderPageContent($page_repo, $pdf, $menu);
	}

	private function writeCashFlowTable($payments, $pdf ) {
		$table = new HTMLTable();
		$table->addTHRow(array("",""));
		$table->add1ColRow('Cash Inflow','bold', 'left', 'normal','2');
		$table->addTDRow(array("% of Sales on Credit", $payments[0]['percentage_sale'] . "%"), null, true);
		$table->addTDRow(array("Avg Collection Period (Days)", $payments[0]['days_collect_payments']), null, true);
		$table->add1ColRow('Cash Outflow','bold', 'left', 'normal','2');
		$table->addTDRow(array("% Purchases on Credit", $payments[0]['percentage_purchase'] . "%"), null, true);
		$table->addTDRow(array("Avg Payment Delay (Days)", $payments[0]['days_make_payments']), null, true);

		$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');
	}

	protected function renderLoansandInvestments($page_repo, $pdf, $menu)
	{
		$pdf->Ln(1);
		$pdf->writeH3('Loans and Investments Table');
		$pdf->Ln(1.5);

		$_loanInvestment = new loansInvestments_lib();
		$allloanInvestmentProjection = $_loanInvestment->getAllCashProjections("", "", "");

		if($allloanInvestmentProjection)
		{
			$this->writeLoansandInvestmentsTable($_loanInvestment, $allloanInvestmentProjection, $pdf );
		}

		$this->renderDefaultSections($page_repo, $pdf, $menu);
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
		
		$table = new HTMLTable();

		$table->addTHRow($th);

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
			
			$table->addTDRow($td, array('t'=>'normal','s'=>'bold'));
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

		$table->addTDRow($td, array('t'=>'total','s'=>'bold'));

		
		$yearlydata['totalrows'] = $td;
		
		
		$this->loansdata['yearly'] 	= $yearlydata;
		$this->loansdata['monthly'] = $monthlydata;
		
		//echo highlight_string(var_export($yearlydata, TRUE));

		//echo highlight_string(var_export($monthlydata, TRUE));
		
		
		$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');
	}

	// FINANCIAL STATEMENT

	protected function renderProfitandLossStatement($page_repo, $pdf, $menu)
	{

		/* here we make use of the stored graph data */

		//$this->renderImage($pdf, 'Sales By Month', 'monthly_graph_sales.png');
		//$this->renderImage($pdf, 'Gross Margin(%) By Month', 'monthly_graph_gross_margin.png');
		//$this->renderImage($pdf, 'Expenses By Month', 'monthly_graph.png');

		$this->writePAndLTable($pdf);

		//handle sales(revenue) by month
		$data = $this->graphdatabank->getSalesByMonthGraphData();
		$data['title'] = 'Months in Year 1';
		$this->renderImage($pdf, 'Sales By Month', 'monthly_graph_sales.png', $data);

		//handle gross by year
		$data = $this->graphdatabank->getSalesGrossByMonthGraphData();
		$data['title'] = '';
		$this->renderImage($pdf, 'Gross Margin(%) By Month', 'monthly_graph_gross_margin.png', $data);

		//get monthly expenses
		$data = $this->graphdatabank->getExpensesByMonthGraphData();
		$data['title'] = '';

		//show accumulated bar type of graph
		//use dummy data for direct cost
		//$data2y = array(1200, 5400, 6412, 2461, 4577, 6441, 6464, 946, 7451, 4678, 7466, 4785);

		//$data['data1y'] = $data['datay'];
		//$data['data2y'] = $data2y;

		//unset($data['datay']);

		$this->renderImage($pdf, 'Expenses By Month', 'monthly_graph.png', $data);
		
		
		$years = $this->salesdata['years'];
		//netprofitsales is calculated in function writePAndLTable
		
		$counter = 0;
		$data['datax'] = array();
		$data['datay'] = array();
		foreach($years as $year)
		{		
			$data['datax'][] = $year;
			$data['datay'][] = $this->salesdata['netprofitsales'][$counter++];		
		}
		
		$this->renderImage($pdf, 'Net Profit (Or Loss) By Year', 'netprofitsales.png', $data);

		//end render of graph for expenses by month

		$pdf->writeH3('About Profit and Loss Statement');
		$this->renderPageContent($page_repo, $pdf, $menu);
	}

	private function writePAndLTable($pdf) {

		$table = new HTMLTable();

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

			$table->addTHRow($th);

			$td = array("Revenue");

			
			
			$totalSalesCounter = 0;
			foreach($arraySalesSummation as $sumOfAllSales)
			{
				$totalSales[$totalSalesCounter] = (array_sum($sumOfAllSales));
				$totalSales_format[$totalSalesCounter] = number_format(array_sum($sumOfAllSales), 0, '.', ',');
				$td[] = $sales->defaultCurrency.$totalSales_format[$totalSalesCounter];
				
				
				$totalSalesCounter = $totalSalesCounter + 1;
				
			}

			$table->addTDRow($td, array('t'=>'total','s'=>'bold'));

			
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
			
			
			$table->addTDRow($td, array('t'=>'total','s'=>'bold'));

			$grossMargin = $this->salesdata['grossMarginRaw'];
			
			$td = array("Gross Margin");
			
			foreach($grossMargin as $key=>$value){
				$td[] = $sales->defaultCurrency.number_format($value, 0, '.', ',');                   
				
			}
			

			$this->writeGrossMarginRows($td, $this->salesdata['gross_margin_percentage'], $table);

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
			$array_operating_income = array();
			

			//var_dump($grossMargin);

			foreach($arrayCostSummation as $sumOfAllCost)
			{

				//$grossvalue = str_replace($sales->defaultCurrency, "", $grossMargin[$totalCostCounter+1]);
				//$grossvalue = str_replace(",", "", $grossvalue);
				//$grossvalue = floatval($grossvalue);

				$operatingIncome[$totalCostCounter] = ( $grossMargin[$totalCostCounter] - $allExpense[$totalCostCounter]);

				$array_operating_income[] = $operatingIncome[$totalCostCounter];
				$td[] = global_lib::formatDisplayWithBrackets($operatingIncome[$totalCostCounter], $sales->defaultCurrency);
				
				$totalCostCounter = $totalCostCounter + 1;
			}
			//echo "income<br>";
			//var_dump($operatingIncome);
			$table->addTDRow($td, array('t'=>'total','s'=>'bold'));

			//<!--------------------------------------------------------------------------->

			$this->profitlossdata['yearlyoperatingincomerows'] = $td; 
			
			
			//<!--------------------        INTEREST INCURRED SECTION       ------------------------->
			if(isset($_interestIncured))
			{
				$td = array("Interest Incurred");
				
				foreach ($yearly_interest_incurred as $yii_value) {
					$td[] =  global_lib::formatDisplayWithBrackets($yii_value, $sales->defaultCurrency);
				}
				
				$this->profitlossdata['yearlyinterestincurredrows'] = $td;
				$this->profitlossdata['yearlyinterestincurred'] = $yearly_interest_incurred;

				$table->addTDRow($td);

			} //end isset interestincurred

			$monthly_accudepreciation = array();
			$monthly_balaccudepreciation = array();
			$monthlydepreciationrows  = array('Depreciation and Amortization');
			$monthlydepreciation      = array();
			$monthly_detail_purchases = array();
			$total_depreciation1 = 0;
			
			for($i = 0; $i < 12; $i++) {
				for($j = 0; $j <= $i; $j++) {
					$monthly_accudepreciation[$i] += $this->expensesdata['monthlytotaldepreciation'][$j];
				}
				
				$val = $this->expensesdata['monthlytotaldepreciation'][$i];
				$monthlydepreciationrows[$i + 1] = $val;
				$monthlydepreciation[$i] = $val;
				$monthly_detail_purchases = $this->expensesdata['monthlytotalmajorpurchases'][$i];
				$total_depreciation1 += $monthly_accudepreciation[$i];
				
				if($i>0) {
					$monthly_balaccudepreciation[$i] = $monthly_balaccudepreciation[$i-1] - $monthly_accudepreciation[$i];
				} else {
					$monthly_balaccudepreciation[$i] = $monthly_accudepreciation[$i];
				}				
			}
			
			$this->profitlossdata['monthly_accudepreciation'] 		= $this->expensesdata['monthly_accudepreciation'];
			$this->profitlossdata['monthly_balaccudepreciation'] 	= $this->expensesdata['monthly_balaccudepreciation'];
			$this->profitlossdata['monthlydepreciationrows']    	= $this->expensesdata['monthlydepreciationrows'];
			$this->profitlossdata['monthlydepreciation'] 		    = $this->expensesdata['monthlytotaldepreciation'];
			$this->profitlossdata['monthly_detail_purchases']     	= $this->expensesdata['$monthlytotalmajorpurchases'];
			$this->profitlossdata['monthlypurchases'] 			    = $this->expensesdata['monthlymajorpurchasesindexed'];
			$this->profitlossdata['yearlydepreciation']             = $this->expensesdata['yearlydepreciation'];
			$this->profitlossdata['yearlydepreciationrows']         = $this->expensesdata['yearlydepreciationrows'];

			$array_depreciation = $this->profitlossdata['yearlydepreciation'];
			$table->addTDRow($this->profitlossdata['yearlydepreciationrows']);

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
			
			$expenditure    = new expenditure_lib();
			
			//global_lib::log($this->expensesdata['yearlyTotalExpenses']);
			// $total_sales(revenue), $totalDirectCost, $array_operating_income, $yearly_interest_incurred, $array_depreciation
			$incomeTaxRate  =  $expenditure->incomeTaxRate;
			$array_incomeTax = array();

			if(isset($array_eachYrEstimatedIncomeTax))
			{
				$array_eachYrEstimatedIncomeTax = $array_eachYrEstimatedIncomeTax;
			}
			
			$yearly_income_tax        =     array();
			$yearly_income_tax_row    = array('Income Tax');
			$yearly_total_expenses    =     array();
			$yearly_total_expenses_row = array('Total Expenses');
			$yearly_net_profit        =     array();
			$yearly_net_profit_row = array('Net Profit');	
			$yearly_profit_percent     =     array();
			$yearly_profit_percent_row = array('Net Profit/Sales');

			// loop through this for number of years
			for($i = 0; $i < $numbersyrOfFinancialForecast; $i++ )
			{
				$pre_tax_profit = $array_operating_income[$i] - $yearly_interest_incurred[$i] - $array_depreciation[$i];
				$income_tax = $pre_tax_profit * ($incomeTaxRate / 100);
				$total_expense = $totalDirectCost[$i] + $this->expensesdata['yearlyTotalExpenses'][$i] + $yearly_interest_incurred[$i] + $array_depreciation[$i] + $income_tax;
				$net_profit = $totalSales[$i] - $total_expense;
				// TODO: Need to ask the real formula.  This is conflict with monthly
				//$net_profit_percent = round(($net_profit / $this->expensesdata['yearlyTotalExpenses'][$i]) * 100);
				$net_profit_percent = round(($net_profit / $totalSales[$i]) * 100);
				
				$yearly_income_tax[]     = $income_tax;
				$yearly_income_tax_row[] = global_lib::formatDisplayWithBrackets($income_tax, $sales->defaultCurrency);
				
				$yearly_total_expenses[]  = $total_expense;
				$yearly_total_expenses_row[]   = global_lib::formatDisplayWithBrackets($total_expense, $sales->defaultCurrency);
			
				$yearly_net_profit[]  = $net_profit;
				$yearly_net_profit_row[]   = global_lib::formatDisplayWithBrackets($net_profit, $sales->defaultCurrency);
				
				$yearly_profit_percent[]  = $net_profit_percent;
				$yearly_profit_percent_row[]   = global_lib::formatDisplayWithBrackets($net_profit_percent, "","%");

			}

			$table->addTDRow($yearly_income_tax_row);
			$this->profitlossdata['yearlyincometaxrows'] = $yearly_income_tax_row;
			$this->profitlossdata['yearlyincometax'] = $yearly_income_tax;
			
			$table->addTDRow($yearly_total_expenses_row);
			$this->profitlossdata['yearlytotalexpensesrows'] = $yearly_total_expenses_row;
			$this->profitlossdata['yearlytotalexpenses'] = $yearly_total_expenses;
			
			$table->addTDRow($yearly_net_profit_row);
			$this->profitlossdata['yearlynetprofitrows'] = $yearly_net_profit_row;
			$this->profitlossdata['yearlynetprofit'] = $yearly_net_profit;
			
			$table->addTDRow($yearly_profit_percent_row, array('t'=>'total','s'=>'bold'));	
			//keep net profit dales in salesdata
			$this->salesdata['netprofitsales'] = $yearly_profit_percent;
			$this->profitlossdata['yearlynetprofitsalesrows'] = $yearly_profit_percent_row;

			$pdf->Ln(1.5);
			$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');

		}

	}
	
	protected function renderBalanceSheet($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Balance Sheet');
		$pdf->Ln(1.5);
		
		$this->writeBalanceSheetTable($pdf);
		
		$pdf->writeH3('About Balance Sheet');
		$this->renderPageContent($page_repo, $pdf, $menu);
	}

	public function writeBalanceSheetTable($pdf) { //new calculation
		
		
		$table = new HTMLTable();
		
		$years 	= $this->salesdata['years'];
		
	
		$pdf->Ln(2);
	
		$thtml = new HTMLTable();
	
		$thtml->addTHRow(array_merge(array("As of Period's End"),$years));	
		
		$tmpdata = $this->oWebcalc->yearlycash;		
		$thtml->addTDRow(array_merge(array('Cash'), $this->farraynumber($tmpdata)));
		
		$tmpdata = $this->oWebcalc->yearlyaccountsreceivable;	
		$thtml->addTDRow(array_merge(array('Accounts Receivable'), $this->farraynumber($tmpdata)));
		
				
		$td = array(); //build empty row
		
		foreach($years as $yr){
			$td[] = '';
		}
		
		$thtml->addTDRow($td);
				
		$tmpdata = $this->oWebcalc->yearlytotalcurrentassets;
		$thtml->addTDRow(array_merge(array('Total Current Assets'), $this->farraynumber($tmpdata)), array('t'=>'normal','s'=>'bold'));

		
		$tmpdata = $this->oWebcalc->yearlylongtermassets;
		$thtml->addTDRow(array_merge(array('Long-Term Assets'), $this->farraynumber($tmpdata)));

		$tmpdata = $this->oWebcalc->yearlyaccudepreciation;
		$thtml->addTDRow(array_merge(array('Accumulated Depreciation'), $this->farraynumber($tmpdata)));
		
		$tmpdata = $this->oWebcalc->yearlytotallongtermassets;
		$thtml->addTDRow(array_merge(array('Total Long-term Assets'), $this->farraynumber($tmpdata)), array('t'=>'total','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlytotalassets;
		$thtml->addTDRow(array_merge(array('Total Assets'), $this->farraynumber($tmpdata)), array('t'=>'normal','s'=>'bold'));

		
		$yrAccountPayable = $this->balancesheetdata['balaccpayable'];
		
		$thtml->addTDRow($td); //empty row
		
		$tmpdata = $this->oWebcalc->yearlyaccountspayable;
		$thtml->addTDRow(array_merge(array('Accounts Payable '), $this->farraynumber($tmpdata)));
		
		$tmpdata = $this->oWebcalc->yearlytotalcurrentliabilities;
		$thtml->addTDRow(array_merge(array('Total Current Liabilities'), $this->farraynumber($tmpdata)), array('t'=>'total','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlylongtermdebt;
		$thtml->addTDRow(array_merge(array('Long Term Debt'), $this->farraynumber($tmpdata)), array('t'=>'normal','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlytotalliabilities;
		$thtml->addTDRow(array_merge(array('Total Liabilities'), $this->farraynumber($tmpdata)), array('t'=>'total','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlyretainedearnings;
		$thtml->addTDRow(array_merge(array('Retained Earnings'), $this->farraynumber($tmpdata)), array('t'=>'normal','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlyearnings;
		$thtml->addTDRow(array_merge(array('Earnings'), $this->farraynumber($tmpdata)), array('t'=>'normal','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlytotalownerequity;
		$thtml->addTDRow(array_merge(array('Total Owner Equity'), $this->farraynumber($tmpdata)), array('t'=>'total','s'=>'bold'));
		
		$tmpdata = $this->oWebcalc->yearlytotalliabilityandEquity;
		$thtml->addTDRow(array_merge(array('Total Liabilities & Equity'), $this->farraynumber($tmpdata)), array('t'=>'total','s'=>'bold'));
		
		
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
		
		
		
	}
	
	
	public function writeBalanceSheetTableOLDWithoutWebcalcfull($pdf) { //new calculation
	
		include(LIBRARY_PATH . '/pdf_calc.php');
		include(LIBRARY_PATH . '/pdf_balance_calc.php');
	
	
	
		$table = new HTMLTable();
	
		$years 	= $this->salesdata['years'];
	
		//data is calculated in expenses
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		$yearlyTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
	
		$pdf->Ln(2);
	
		$thtml = new HTMLTable();
	
		$thtml->addTHRow(array_merge(array("As of Period's End"),$years));
	
	
	
		$thtml->addTDRow(array_merge(array('Cash'), $this->farraynumber($this->balancesheetdata['balcash'])));
	
		$thtml->addTDRow(array_merge(array('Accounts Receivable'), $this->farraynumber($this->balancesheetdata['balaccreceivable'])));
	
	
	
		$td = array(); //build empty row
	
		foreach($years as $yr){
			$td[] = '';
		}
	
		$thtml->addTDRow($td);
	
		//data of the follwing is calculate in pdf_calc_cash
		$balTotalCurrentAssets 	= $this->profitlossdata['ns']['balTotalCurrentAssets'];
		$balLongTermsAssets 	= $this->profitlossdata['ns']['balLongTermsAssets'];
		$balAccuDepreciation 	= $this->profitlossdata['ns']['balAccuDepreciation'];
		$balTotalLongTermsAssets = $this->profitlossdata['ns']['balTotalLongTermsAssets'];
		$balTotalAssets 		= $this->profitlossdata['ns']['balTotalAssets'];
	
		$thtml->addTDRow(array_merge(array('Total Current Assets'), $this->farraynumber($balTotalCurrentAssets)), array('t'=>'normal','s'=>'bold'));
	
	
	
		$thtml->addTDRow(array_merge(array('Long-Term Assets'), $this->farraynumber($balLongTermsAssets)));
	
	
		$thtml->addTDRow(array_merge(array('Accumulated Depreciation'), $this->farraynumber($balAccuDepreciation)));
		$thtml->addTDRow(array_merge(array('Total Long-term Assets'), $this->farraynumber($balTotalLongTermsAssets)), array('t'=>'total','s'=>'bold'));
	
	
	
		$thtml->addTDRow(array_merge(array('Total Assets'), $this->farraynumber($balTotalAssets)), array('t'=>'normal','s'=>'bold'));
	
	
		$yrAccountPayable = $this->balancesheetdata['balaccpayable'];
	
		$thtml->addTDRow($td); //empty row
		$thtml->addTDRow(array_merge(array('Accounts Payable '), $this->farraynumber($yrAccountPayable)));
	
	
		$balTotalCurrentLiability = $this->profitlossdata['ns']['balTotalCurrentLiability'];
	
		$thtml->addTDRow(array_merge(array('Total Current Liabilities'), $this->farraynumber($balTotalCurrentLiability)), array('t'=>'total','s'=>'bold'));
	
	
		$balLongTermDebt 	= $this->profitlossdata['ns']['balLongTermDebt'];
		$balTotaLiabilities	= $this->profitlossdata['ns']['balTotaLiabilities'];
	
		$thtml->addTDRow(array_merge(array('Long Term Debt'), $this->farraynumber($balLongTermDebt)), array('t'=>'normal','s'=>'bold'));
	
		$thtml->addTDRow(array_merge(array('Total Liabilities'), $this->farraynumber($balTotaLiabilities)), array('t'=>'total','s'=>'bold'));
	
	
		$balEarnings 			= $this->profitlossdata['ns']['balEarnings'];
		$balRetainedEarnings 	= $this->profitlossdata['ns']['balRetainedEarnings'];
		$balTotalOwnerEquity	= $this->profitlossdata['ns']['balTotalOwnerEquity'];
		$balTotalLiabilitiesAndEquities = $this->profitlossdata['ns']['balTotalLiabilitiesAndEquities'];
	
	
		$thtml->addTDRow(array_merge(array('Retained Earnings'), $this->farraynumber($balRetainedEarnings)), array('t'=>'normal','s'=>'bold'));
		$thtml->addTDRow(array_merge(array('Earnings'), $this->farraynumber($balEarnings)), array('t'=>'normal','s'=>'bold'));
		$thtml->addTDRow(array_merge(array('Total Owner Equity'), $this->farraynumber($balTotalOwnerEquity)), array('t'=>'total','s'=>'bold'));
		$thtml->addTDRow(array_merge(array('Total Liabilities & Equity'), $this->farraynumber($balTotalLiabilitiesAndEquities)), array('t'=>'total','s'=>'bold'));
	
	
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	
	
	
	
	}
	
	
	public function writeBalanceSheetTable1($pdf) {
	
		include(LIBRARY_PATH . '/pdf_calc.php');
		include(LIBRARY_PATH . '/pdf_balance_calc.php');
	
		//echo highlight_string(var_export($cashProjectionDetails, TRUE));
	
		$table = new HTMLTable();
	
		$table->addTHRow($years);
	
		//yearly cash calculation
		$cash[1] = $this->loansdata['monthlycash'][11];
		$accountreceivable[1] = $accountReceivable_allMonths[11];
	
		$table->addTDRow($this->farraynumber($cash));
	
		$this->balancesheetdata['cash'] = $cash;
	
	
	
		$table->addTDRow($accountreceivable);
		$this->balancesheetdata['accountreceivablerows'] = $accountreceivable;
		$this->balancesheetdata['accountreceivable'] = $accountreceivable_raw;
	
		$table->addTDRow($currentassets, array('t'=>'normal','s'=>'bold'));
		$this->balancesheetdata['currentassets'] = $currentassets;
	
		$td = array(); //build empty row
	
		foreach($years as $yr){
			$td[] = '';
		}
	
		$table->addTDRow($td);
	
		$table->addTDRow($longtermassets);
		//$longtermassets = array("Long Term Assets");
		//note: add empty row before
		//note no data to pick up for long term assets
		$this->balancesheetdata['longtermassets'] = $longtermassets;
	
	
		$table->addTDRow($depreciations);
		//$depreciations = array("Accumulated Depreciation");
		//note: value in website is hardcoded to zero
		$this->balancesheetdata['depreciations'] = $depreciations;
	
		$table->addTDRow($totallongtermassets, array('t'=>'normal','s'=>'bold'));
		//$totallongtermassets = array("Total Long-Term Assets");
		//note: harcoded in website
		$this->balancesheetdata['totallongtermassets'] = $totallongtermassets;
	
		$table->addTDRow($td); //empty row
		$table->addTDRow($totalassets, array('t'=>'total','s'=>'bold'));
		//$totalassets = array("Total Assets");
		//note: add empty line before
		$this->balancesheetdata['totalassets'] = $totalassets;
	
	
		$table->addTDRow($td); //empty row
		$table->addTDRow($accountpayable);
		//$accountpayable = array("Account Payable");
		//note: add empty line before
		$this->balancesheetdata['accountpayablerows'] = $accountpayable;
		$this->balancesheetdata['accountpayable'] = $accountpayable_raw;
	
		//$table->addTDRow($salestaxespayable);
		// $salestaxespayable = array("Sales Taxes Payable");
		// note: hard coded to zero in website
	
	
		//$table->addTDRow($shorttermdebt);
		//$shorttermdebt = array("Short-Term Debt");
		//note: hard coded to zero in website
	
		$table->addTDRow($totalcurrentliability, array('t'=>'normal','s'=>'bold'));
		//$totalcurrentliability = array("Total Current Liabilities");
		//note: hard coded to zero in website
		$this->balancesheetdata['totalcurrentliability'] = $totalcurrentliability;
	
	
		$table->addTDRow($td); //empty row
		$table->addTDRow($longtermdebt);
		//$longtermdebt = array("Long-Term Debt");
		//note add empty line before
		$this->balancesheetdata['longtermdebt'] = $longtermdebt;
	
	
		$table->addTDRow($totalliability, array('t'=>'normal','s'=>'bold'));
		//$totalliability = array("Total Liabilities");
		//note: add empty row before
		//hardcoded to zero in web
		$this->balancesheetdata['$totalliability'] = $totalliability;
	
		$pdf->writeHTML($table->getHTML(), true, false, false, false, 'L');
	
	
	
	
	}
	

	//Appendix

	protected function renderAppendixSalesForecast($page_repo, $pdf, $menu)
	{
		$pdf->Ln(1);

		//echo highlight_string(var_export($this->graphdatabank->sales, TRUE));
		

		//data is calculated in renderSalesForecast function call          
		$thtml = new HTMLTable();
		$pdf->writeH3('Sales Forecast Table (With Monthly Detail)');
		$pdf->Ln(3);
		
		$months = $this->salesdata['months'];   
		$year 	= $this->salesdata['years'];
		$thtml->addLTHRow(array_merge(array($year[0]), str_replace("20","'", $months)));
		
		$products = $this->salesdata['products'];
		
		$thtml->add1LColRow("Unit Sales", 'bold', 'left', 'normal', 13);
		
		foreach($products as $product) {
			
			$merged = array_merge(array($product['name']),
			$product['monthlyUnitSales']);
			
			$thtml->addLTDRow($merged, null, true);
			
		}
		

		$thtml->add1LColRow("Price Per Unit", 'bold', 'left', 'normal', 13);
		
		foreach($products as $product) {
			
			$thtml->addLTDRow(array_merge(array($product['name']),$this->farraynumber($product['monthlyPricePerUnit'])), null, true);
			
		}
		
		$thtml->add1LColRow("Sales", 'bold', 'left', 'normal', 13);
		
		foreach($products as $product) {
			
			$thtml->addLTDRow(array_merge(array($product['name']),$this->farraynumber($product['monthlyProductSales'])), null, true);
			
		}
		
		
		
		$thtml->addLTDRow(array_merge(array('Total Sales'),$this->farraynumber($this->salesdata['monthlyTotalSales'])),
		array('s'=>'bold','t'=>'total'));			
		
		
		$thtml->add1LColRow("Direct Cost Per Unit", 'bold', 'left', 'normal', 13);
		
		foreach($products as $product) {
			
			$thtml->addLTDRow(array_merge(array($product['name']),$this->farraynumber($product['monthlyDirectCostPerUnit'])), null, true);
			
		}
		
		$thtml->add1LColRow("Direct Cost", 'bold', 'left', 'normal', 13);
		
		foreach($products as $product) {
			
			$thtml->addLTDRow(array_merge(array($product['name']),$this->farraynumber($product['monthlyDirectCost'])), null, true);
			
		}
		
		$thtml->addLTDRow(array_merge(array('Total Direct Cost'),$this->farraynumber($this->salesdata['monthlyTotalDirectCost'])),
		array('s'=>'bold','t'=>'total'));			
		
		$thtml->addLTDRow(array_merge(array('Gross Margin'),$this->farraynumber($this->salesdata['monthlyGrossMargin'])), null, true);

		
		$thtml->addLTDRow(array_merge(array('Gross Margin %'),$this->farraypercent($this->salesdata['monthlyGrossMPercentage'])),
		array('s'=>'bold','t'=>'total'));
		
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
		
		
		$unit_sales 		= $this->salesdata['yrlyUnitSales'];
		$price_per_unit 	= $this->salesdata['yrlyUnitPrices'];
		$product_sales 		= $this->salesdata['yrlyProdSales'];
		$total_sales		= $this->salesdata['yrlyTotalSales'];
		$direct_cost_per_unit = $this->salesdata['yrlyUnitCost'];
		$direct_cost		= $this->salesdata['yrlyCosts'];
		$total_direct_cost 	= $this->salesdata['yrlyTotalCosts'];
		$gross_margin 		= $this->salesdata['yrlyGrossMargin'];
		$gross_margin_percentage = $this->salesdata['yrlyGMPercentage'];
		
		
		$pdf->AddPage("L");
		
		$years 	= $this->salesdata['years'];
		
		$thtml = new HTMLTable();
		
		$thtml->addLTHRow(array_merge(array(''),$years));
		
		$thtml->add1LColRow('Unit Sales','bold', 'left', 'normal','4');
		
		foreach ($unit_sales as $row) {
			$thtml->addLTDRow($row, null, true);
		}

		$thtml->add1LColRow('Price Per Unit','bold', 'left', 'normal','4');
		foreach ($price_per_unit as $row) {
			$thtml->addLTDRow($this->farraynumber($row), null, true);
		}

		$thtml->add1LColRow('Sales','bold', 'left', 'normal','4');
		foreach ($product_sales as $row) {
			$thtml->addLTDRow($this->farraynumber($row), null, true);
		}

		$thtml->addLTDRow($this->farraynumber($total_sales), array('t'=>'total','s'=>'bold'));

		$thtml->add1LColRow('Direct Cost Per Unit','bold', 'left', 'normal','4');
		foreach ($direct_cost_per_unit as $row) {
			$thtml->addLTDRow($this->farraynumber($row), null, true);
		}

		$thtml->add1LColRow('Direct Cost','bold', 'left', 'normal','4');
		foreach ($direct_cost as $row) {
			$thtml->addLTDRow($this->farraynumber($row), null, true);
		}
		
		$thtml->addLTDRow($this->farraynumber($total_direct_cost), array('t'=>'total','s'=>'bold'));
		
		$this->writeGrossMarginRows($this->farraynumber($gross_margin), $this->farraynumber($gross_margin_percentage), $thtml, true);
		
		
		//echo highlight_string(var_export($thtml->getHTML(), TRUE));
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');

	}	


	protected function renderCashFlowStatement($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('About Cash Flow Statement');
		$this->writeCashFlowStatementTable($pdf);
		$this->renderPageContent($page_repo, $pdf, $menu);
	}

	private function writeCashFlowStatementTable($pdf)
	{
		$thtml = new HTMLTable();

		// FY2012 header
		$thtml->addTHRow($this->th);


		// calculate the net cash flow from operations
		$years = count($this->profitlossdata['yearlynetprofit']);
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
				$change_receivable[$i] = 0 - $this->balancesheetdata['balaccreceivable'][$i];
				$change_payable[$i] = 0 + $this->balancesheetdata['balaccpayable'][$i];
			}
			else {
				$change_receivable[$i] = $this->balancesheetdata['balaccreceivable'][$i - 1] - $this->balancesheetdata['balaccreceivable'][$i];
				$change_payable[$i] = $this->balancesheetdata['balaccpayable'][$i] - $this->balancesheetdata['balaccpayable'][$i - 1];
			}

			$operations[$i] = $this->profitlossdata['yearlynetprofit'][$i]
				+ $this->profitlossdata['yearlydepreciation'][$i]
				+ $change_receivable[$i]
				+ $change_payable[$i];


			$assets_purchased[$i] = 0 - $this->expensesdata['yearlytotalmajorpurchases'][$i];
			if ($i == 0) {
				$change_longterm[$i] = $this->profitlossdata['ns']['balLongTermDebt'][$i];
			}
			else {
				$change_longterm[$i] = $this->profitlossdata['ns']['balLongTermDebt'][$i] - $this->profitlossdata['ns']['balLongTermDebt'][$i - 1];
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

		$thtml->add1LColRow("Operations", 'bold', 'left', 'normal', 13);
		$thtml->addTDRow($this->profitlossdata['yearlynetprofitrows']);
		$thtml->addTDRow(array_merge(array('Depreciation'),$this->farraynumber($this->profitlossdata['yearlydepreciation'])));
		$thtml->addTDRow(array_merge(array('Change in Accounts Receivable'), $this->farraynumber($change_receivable)));
		$thtml->addTDRow(array_merge(array('Change in Accounts Payable '), $this->farraynumber($change_payable)));
		$thtml->addTDRow(array_merge(array('Net Cash Flow From Operations'), $this->farraynumber($operations)), array('s'=>'bold','t'=>'total'));


		$thtml->add1LColRow("Investing and Finance", 'bold', 'left', 'normal', 13);
		$thtml->addTDRow(array_merge(array('Assets Purchased or Sold'), $this->farraynumber($assets_purchased)));
		$thtml->addTDRow(array_merge(array('Change in Long Term Debt'), $this->farraynumber($change_longterm)));
		$thtml->addTDRow(array_merge(array('Net Cash Flow From Investing and Finance'), $this->farraynumber($investing_and_finance)), array('s'=>'bold','t'=>'total'));

		$thtml->add1LColRow("", 'bold', 'left', 'normal', 13);
		$thtml->addTDRow(array_merge(array('Cash at Beginning of Period'), $this->farraynumber($beg_of_period)));
		$thtml->addTDRow(array_merge(array('Net Change in Cash'), $this->farraynumber($net)));
		$thtml->addTDRow(array_merge(array('Cash at End of Period'), $this->farraynumber($end_of_period)), array('s'=>'bold','t'=>'total'));

		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	}

	protected function number($number, $decimal = 0)
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
	
	protected function farraynumber($tarray) {
		
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
		$pdf->writeH3('Personnel Table (With Monthly Detail)');
		$pdf->Ln(2);
		
		$employees 			= $this->employeedata['employees'];
		$monthlysalarytotal	= $this->employeedata['monthlysalarytotal'];
		
		$thtml = new HTMLTable();
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
		
		$thtml->addLTHRow(array_merge(array($years[0]), str_replace("20","'", $months)));
		
		foreach($employees as $employee)
		{
			$thtml->addLTDRow(array_merge(array($employee['name']),$this->farraynumber($employee['monthlysalary'])), null);
			
		}
		
		$monthlysalarytotal =  $this->employeedata['monthlysalarytotal'];
		
		$thtml->addLTDRow(array_merge(array('Total'),$this->farraynumber($monthlysalarytotal)),
		array('s'=>'bold','t'=>'total'));
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
		$years 	= $this->salesdata['years'];
		
		$thtml = new HTMLTable();
		
		$thtml->addLTHRow(array_merge(array(''),$years));
		
		foreach($employees as $employee)
		{
			$thtml->addLTDRow($this->farraynumber($employee['yrlysalary']), null);
			
		}
		
		$yrlyTotal = $this->employeedata['yrlyTotal'];
		
		
		$thtml->addLTDRow($yrlyTotal,
		array('s'=>'bold','t'=>'total'));
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
	}
	
	protected function renderAppendixBudget($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Budget Table (With Monthly Detail)');
		$pdf->Ln(2);
		
		//data is calculated in expenses
		$monthlytotalsalary			 = $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses = $this->expensesdata['monthlytotalrelatedexpenses'];
		$monthlyotherexpenses        = $this->expensesdata['monthlyotherexpenses'];
		$monthlytotalexpenses		 = $this->expensesdata['monthlytotalexpenses'];
		$monthlymajorpurchases       = $this->expensesdata['monthlymajorpurchasesrows'];
		$monthlytotalmajorpurchases  = $this->expensesdata['monthlytotalmajorpurchasesrows'];

		$this->expensesdata['yearlyotherexpenses'] 		= $expensesrows;
	
		$thtml = new HTMLTable();
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
		
		$thtml->addLTHRow(array_merge(array($years[0]), str_replace("20","'", $months)));
		
		$thtml->add1LColRow("Expenses", 'bold', 'left', 'normal', 13);
		
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($monthlytotalsalary)), null);

		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($monthlytotalrelatedexpenses)), null);
		
		foreach ($monthlyotherexpenses as $row) {
			$thtml->addLTDRow(array_merge(array($row['name']), $this->farraynumber($row['monthly_expenses'])), null);
		}
			
		$thtml->addLTDRow(array_merge(array('Total Expenses'),$this->farraynumber($monthlytotalexpenses)),
				array('s'=>'bold','t'=>'total'));

		$thtml->add1LColRow('Major Purchases','bold', 'left', 'normal','4');
		
		foreach($monthlymajorpurchases as $data) {
			$thtml->addLTDRow($data, null);
		}
				
		$thtml->addLTDRow($monthlytotalmajorpurchases, array('t'=>'total','s'=>'bold'));
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
			
		$years 	= $this->salesdata['years'];
		
		//data is calculated in expenses
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		$yearlyOtherExpenses	= $this->expensesdata['yearlyOtherExpenses'];
		$yearlyTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
		$yearlyMajorPurchases	= $this->expensesdata['yearlymajorpurchases'];
		$yearlyTotalPurchases	= $this->expensesdata['yearlytotalmajorpurchasesrows'];
		
		$pdf->Ln(2);
	
		$thtml = new HTMLTable();
	
		$thtml->addLTHRow(array_merge(array(''),$years));
	
		
		$thtml->add1LColRow("Expenses", 'bold', 'left', 'normal', 4);
		
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($yearlyTotalSalary)), null);
		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($yearlyTotalRSalary)), null);
		
		foreach ($yearlyOtherExpenses as $row) {
			$thtml->addLTDRow(array_merge(array($row['name']), $this->farraynumber($row['yearly_expenses'])), null);
		}
		
		$thtml->addLTDRow(array_merge(array('Total Expenses'),$this->farraynumber($yearlyTotalExpenses)),
				array('s'=>'bold','t'=>'total'));
	
		$thtml->add1LColRow("Major Purchases", 'bold', 'left', 'normal', 4);
		
		foreach ($yearlyMajorPurchases as $row) {
			$thtml->addLTDRow($row, null);
		}
		
		$thtml->addLTDRow($yearlyTotalPurchases, array('s'=>'bold','t'=>'total'));
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
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
		//init monthly data
		$monthlytotalsalary 			= array();
		$monthlytotalrelatedexpenses 	= array();
		$monthlyotherexpenses           = array();
		$monthlytotalexpenses 			= array();
		
		$yearlyTotalSalary           = array();
		$yearlyTotalRSalary          = array();
		$yearlyTotalSalary_Display   = array();
		$yearlyTotalRSalary_Display  = array();
		$yearlyOtherExpenses         = array();
		$yearlyTotalExpenses         = array();
		$yearlyTotalExpenses_display = array();
		
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
					$counterstr 						     = str_pad($counter+1,2,"0",STR_PAD_LEFT);
					$monthlytotalsalary[$counter] 			+= $empDetails['month_' . $counterstr];
					$monthlytotalrelatedexpenses[$counter] 	+= ($personalRelatedExpenseInPercentage * $empDetails['month_' . $counterstr]);
					$monthlytotalexpenses[$counter] 	     = $monthlytotalsalary[$counter] + $monthlytotalrelatedexpenses[$counter];
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
				$i = 0;
				foreach ($empDetails['financial_status'] as $yearly_status) {
					$yearlyTotalSalary[$i]   += $yearly_status['total_per_yr'];
					$yearlyTotalSalary_Display[$i] = $expenditure->defaultCurrency.number_format($yearlyTotalSalary[$i], 0, '.', ',');
					$yearlyTotalRSalary[$i]  += $yearly_status['total_per_yr'] * $personalRelatedExpenseInPercentage;
					$yearlyTotalRSalary_Display[$i] = $expenditure->defaultCurrency.number_format($yearlyTotalRSalary[$i], 0, '.', ',');
					$yearlyTotalExpenses[$i] += $yearlyTotalSalary[$i] + $yearlyTotalRSalary[$i];
					$yearlyTotalExpenses_display[$i] = $expenditure->defaultCurrency.number_format($yearlyTotalExpenses[$i], 0, '.', ',');

					$i++;
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
			
			$expensesrows['Salary'] = $yearlyTotalSalary_Display;
			$expensesrows['Employee Related Expenses'] = $yearlyTotalRSalary_Display;
		}
		
		if ($allExpDetails) {
			//global_lib::log($allExpDetails);
			foreach ($allExpDetails as $expDetails) {
				$otherexpenses = array();
				$otherexpenses['name'] = $expDetails['expenditure_name'];
				$otherexpenses['monthly_expenses'] = array();
				
				$otheryearlyexpenses = array();
				$otheryearlyexpenses['name'] = $expDetails['expenditure_name'];
				$otheryearlyexpenses['yearly_expenses'] = array();
				$otheryearlyexpenses_display = array();
				
				for ($counter = 0; $counter < 12; $counter++)
				{
					$counterstr 						         = str_pad($counter+1,2,"0",STR_PAD_LEFT);
					$value                                       = $expDetails['month_' . $counterstr];
					$otherexpenses['monthly_expenses'][$counter] = $value;
					$monthlytotalexpenses[$counter] 	         += $value;
				}
				
				$i = 0;
				foreach ($expDetails['financial_status'] as $yearly_status) {
					$otheryearlyexpenses['yearly_expenses'][$i] = $yearly_status['total_per_yr'];
					$otheryearlyexpenses_display[$i]            = $expenditure->defaultCurrency.number_format($yearly_status['total_per_yr'], 0, '.', ',');
					$yearlyTotalExpenses[$i]                   += $yearly_status['total_per_yr'];
					$yearlyTotalExpenses_display[$i]            = $expenditure->defaultCurrency.number_format($yearlyTotalExpenses[$i], 0, '.', ',');
					$i++;
				}
				
				$monthlyotherexpenses[] = $otherexpenses;
				$yearlyOtherExpenses[]  = $otheryearlyexpenses;
				
				$expensesrows[$expDetails['expenditure_name']] = $otheryearlyexpenses_display;
			}
		}
		
		$expensesrows["Total Operating Expenses"] = $yearlyTotalExpenses_display;
		
		//echo highlight_string(var_export($monthlysalary, TRUE));
		//echo highlight_string(var_export($monthlyrelatedexpenses, TRUE));
		$this->expensesdata['monthlytotalsalary'] 			= $monthlytotalsalary;
		$this->expensesdata['monthlytotalrelatedexpenses'] 	= $monthlytotalrelatedexpenses;
		$this->expensesdata['monthlyotherexpenses'] 		= $monthlyotherexpenses;
		$this->expensesdata['monthlytotalexpenses'] 		= $monthlytotalexpenses;
		
		$this->expensesdata['yearlyTotalSalary'] 	= $yearlyTotalSalary;
		$this->expensesdata['yearlyTotalRSalary'] 	= $yearlyTotalRSalary;
		$this->expensesdata['yearlyOtherExpenses'] 	= $yearlyOtherExpenses;
		$this->expensesdata['yearlyTotalExpenses'] 	= $yearlyTotalExpenses;
		$this->expensesdata['allExpense']           = $yearlyTotalExpenses;
		
		//global_lib::log($this->expensesdata);
	}
	
	protected function renderAppendixLoansandInvestments($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Loans and Investments Table (With Monthly Detail)');
		$pdf->Ln(2);
	
		$employees 			= $this->employeedata['employees'];
		$monthlysalarytotal	= $this->employeedata['monthlysalarytotal'];
	
		$thtml = new HTMLTable();
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
	
		$thtml->addLTHRow(array_merge(array($years[0]), str_replace("20","'", $months)));
	
				
		$tmploansrows 		= $this->loansdata['monthly']['loansrows'];
		$tmploansdetailrows	= $this->loansdata['monthly']['loansdetailrows'];
		//$tmploansdetailrows	= $this->loansdata['monthly'] 	= array();
		
		
		foreach($tmploansrows as $key => $value)
		{
			$value[0] = $value[0] . '<br><span style="font-family: arialmt">' .  $tmploansdetailrows[$key][0] . '</span>';
			
			$thtml->addLTDRow($this->farraynumber($value), 
					array('s'=>'bold', 't'=>'normal'));
			//$thtml->addLTDRow($this->farraynumber($tmploansdetailrows[$key]));
				
		}
	
		$tmptotal =  $this->loansdata['monthly']['totalrows'];
	
		$thtml->addLTDRow($this->farraynumber($tmptotal),
				array('s'=>'bold','t'=>'total'));
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	
		$years 	= $this->salesdata['years'];
	
		$pdf->AddPage('L');
		
		
		$thtml = new HTMLTable();
	
		$thtml->addLTHRow(array_merge(array(''),$years));
	
		$tmploansrows 		= $this->loansdata['yearly']['loansrows'];
		$tmploansdetailrows	= $this->loansdata['yearly']['loansdetailrows'];
		
		foreach($tmploansrows as $key => $value)
		{
			//$value[0] = $value[0] . '<br><span style="font-family: arialmt">' .  $tmploansdetailrows[$key][0] . '</span>';
			$thtml->addLTDRow($this->farraynumber($value), 
					array('s'=>'bold', 't'=>'normal'));
			//$thtml->addLTDRow($this->farraynumber($tmploansdetailrows[$key]));
				
		}
	
		$tmptotal =  $this->loansdata['yearly']['totalrows'];
	
	
		$thtml->addLTDRow($tmptotal,
				array('s'=>'bold','t'=>'total'));
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	
	}
	
	
	protected function renderAppendixProfitandLossStatement($page_repo, $pdf, $menu)
	{
		$pdf->writeH3('Profit and Loss Statement (With Monthly Detail)');
		$pdf->Ln(2);
	
		$monthrows 			= $this->profitlossdata['monthlyrevenuerows'];
		
	
		$thtml = new HTMLTable();
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
	
		$thtml->addLTHRow(array_merge(array($years[0]), str_replace("20","'", $months)));
	
	
		//$tmploansrows 		= $this->loansdata['monthly']['loansrows'];
		//$tmploansdetailrows	= $this->loansdata['monthly']['loansdetailrows'];
		//$tmploansdetailrows	= $this->loansdata['monthly'] 	= array();
	
		$thtml->addLTDRow($this->farraynumber($monthrows),
				array('s'=>'bold', 't'=>'total'));
		
	
		$monthrows = $this->profitlossdata['monthlydirectcostrows'];
		
		
		$thtml->addLTDRow($this->farraynumber($monthrows),
				array('s'=>'bold', 't'=>'total'));
		
		
		
		
		$monthrows = $this->salesdata['monthlyGrossMargin'];
		$monthrows = array_merge( array('Gross Margin') , $monthrows);
		
		$thtml->addLTDRow($this->farraynumber($monthrows),
				array('s'=>'normal', 't'=>'normal'));
		
				
		$monthrows = $this->salesdata['monthlyGrossMPercentage'];
		$monthrows = array_merge( array('Gross Margin %') , $monthrows);
		
		$thtml->addLTDRow($this->farraypercent($monthrows),
				array('s'=>'bold', 't'=>'total'));
	
		$thtml->add1LColRow("Operating Expenses", 'bold', 'left', 'normal', 13);
		
		//data is calculated in expenses
		$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];				
		$monthlyotherexpenses        = $this->expensesdata['monthlyotherexpenses'];
		
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($monthlytotalsalary)), null);
								
		
		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($monthlytotalrelatedexpenses)), null);
		
		foreach ($monthlyotherexpenses as $row) {
			$thtml->addLTDRow(array_merge(array($row['name']), $this->farraynumber($row['monthly_expenses'])), null);
		}

		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		
		$thtml->addLTDRow(array_merge(array('Total Expenses'),$this->farraynumber($monthlytotalexpenses)),
				array('s'=>'bold','t'=>'total'));
		
		$monthrows = $this->salesdata['monthlyGrossMargin'];
		
		$tmprows = array();
		
		for($i = 0; $i < 12; $i++){		
			$tmprows[$i] = $monthrows[$i] -  $monthlytotalexpenses[$i];
		}
		
		
		$monthlyoperatingincome = $tmprows;
		
		//global_lib::log($tmprows);
		
		$thtml->addLTDRow(array_merge(array('Operating Income'),$this->farraynumber($tmprows)),
				array('s'=>'bold','t'=>'total'));
		
		$monthlyrows = $this->profitlossdata['monthlyinterestincurred'];
		$monthlyrows = array_merge(array('Interest Incurred'), $this->farraynumber($monthlyrows));
		
		$thtml->addLTDRow($monthlyrows,
				array('s'=>'normal','t'=>'normal'));
		
		
		//add depreciation		
		$monthlyvals = $this->profitlossdata['monthly_accudepreciation'];
		
		//$this->profitlossdata['monthly_accudepreciation']
		
		$monthlyrows = array_merge(array('Depreciation and Amortization'), $this->farraynumber($monthlyvals));
		$thtml->addLTDRow($monthlyrows,
				array('s'=>'normal','t'=>'normal'));
					
		
		//calculate monthtly incometax
		//loop through this for number of years
		
		$expenditure    = new expenditure_lib();
		
		// TODO: Need to confirm this default to 20% if percent is zero
		//$incomeTaxRate  =  $expenditure->incomeTaxRate > 0 ? $expenditure->incomeTaxRate : 20;
		$incomeTaxRate  =  $expenditure->incomeTaxRate;
		
		$revenue = array_slice($this->profitlossdata['monthlyrevenuerows'], 1);
		$monthlydirectcost	= array_slice($this->profitlossdata['monthlydirectcostrows'], 1);
		// $monthlytotalexpenses
		// $monthlyoperatingincome
		$montylyinterestincurred 	= $this->profitlossdata['monthlyinterestincurred'];
		$depreciation = $this->profitlossdata['monthly_accudepreciation'];
		$monthlyincometax = array();
		$monthlynetprofit = array();
		$monthlynetprofitpercent = array();
		
		//global_lib::log($revenue);
		
		//echo "tax rate: " . $incomeTaxRate;
		
		for($i = 0; $i < 12; $i++)
		{
			$monthlyincometax[$i] = ($monthlyoperatingincome[$i] - $montylyinterestincurred[$i] - $depreciation[$i]) * ($incomeTaxRate / 100);
			$monthlynetprofit[$i] = $revenue[$i] - ($monthlydirectcost[$i] + $monthlytotalexpenses[$i] + $montylyinterestincurred[$i] + $depreciation[$i] + $monthlyincometax[$i]);
			$monthlynetprofitpercent[$i] = round(($monthlynetprofit[$i] / $revenue[$i]) * 100);
		}
		
		$this->profitlossdata['monthlyincometax'] = $monthlyincometax;
				
		$thtml->addLTDRow(array_merge(array('Income Taxes'), $this->farraynumber( $monthlyincometax )),
				array('s'=>'normal','t'=>'normal'));
		
		$this->profitlossdata['monthlynetprofit'] = $monthlynetprofit;
		$this->salesdata['netprofit'] = $monthlynetprofit;
				
		$thtml->addLTDRow(array_merge(array('Net Profit'),$this->farraynumber($monthlynetprofit)),
				array('s'=>'bold','t'=>'total'));
		
		$thtml->addLTDRow(array_merge(array('Net Profit/Sales'),$this->farraypercent($monthlynetprofitpercent)),
				array('s'=>'bold','t'=>'total'));
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
			
		
		$years 	= $this->salesdata['years'];
	
		$pdf->AddPage('L');
	
	
		$thtml = new HTMLTable();
	
		$thtml->addLTHRow(array_merge(array(''),$years));
	
		$tmprows = $this->profitlossdata['yearlyrevenuerows'];		
	
		$thtml->addLTDRow($tmprows,
				array('s'=>'bold','t'=>'total'));
		
		$tmprows = $this->profitlossdata['yearlydirectcostrows'];
		
		$thtml->addLTDRow($tmprows,
				array('s'=>'bold','t'=>'total'));
		
		
		$tmprows = $this->salesdata['grossMarginRaw'];
		$tmprows = array_merge( array('Gross Margin'),  $this->farraynumber( $tmprows)); 
		
		$thtml->addLTDRow($tmprows,
				array('s'=>'normal','t'=>'normal'));
		
		$thtml->add1LColRow("Operating Expenses", 'bold', 'left', 'normal', 4);
		
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($yearlyTotalSalary)), null);
		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($yearlyTotalRSalary)), null);
		
		$yearlyOtherExpenses	= $this->expensesdata['yearlyOtherExpenses'];
		
		foreach ($yearlyOtherExpenses as $row) {
			$thtml->addLTDRow(array_merge(array($row['name']), $this->farraynumber($row['yearly_expenses'])), null);
		}
		
		$yearlyOperatingTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
		$thtml->addLTDRow(array_merge(array('Total Operating Expenses'),$this->farraynumber($yearlyOperatingTotalExpenses)),
				array('s'=>'bold','t'=>'total'));
		
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
		
		$thtml->addLTDRow($tmprows,
				array('s'=>'bold','t'=>'total'));
		
		$tmprows = $this->profitlossdata['yearlyinterestincurredrows'];
		$thtml->addLTDRow($tmprows,
				array('s'=>'normal','t'=>'normal'), true);
	
		
		
		
		$tmpvals  = $this->profitlossdata['yearlydepreciation'];
		$tmprows = array_merge(array('Depreciation and Amortization'),$this->farraynumber($tmpvals));
		
		$thtml->addLTDRow($tmprows,
				array('s'=>'normal','t'=>'normal'), true);
	
		$tmprows = $this->profitlossdata['yearlyincometaxrows'];
		$thtml->addLTDRow($tmprows,
				array('s'=>'normal','t'=>'normal'), true);

		
		$thtml->addLTDRow($this->profitlossdata['yearlytotalexpensesrows'],
				array('s'=>'normal','t'=>'normal'));
		
		$tmprows = $this->profitlossdata['yearlynetprofitrows'];
		$thtml->addLTDRow($tmprows,
				array('s'=>'bold','t'=>'normal'));
		
		
		$tmprows = $this->profitlossdata['yearlynetprofitsalesrows'];
		$thtml->addLTDRow($tmprows,
				array('s'=>'bold','t'=>'total'));
		
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		
		
	
	}
	
	protected function renderAppendixBalanceSheet($page_repo, $pdf, $menu)
	{
	
			
		
		$pdf->writeH3('Balance Sheet (With Monthly Detail)');
		$pdf->Ln(2);
			
	
		//data is calculated in expenses
		$monthlytotalsalary			= $this->expensesdata['monthlytotalsalary'];
		$monthlytotalrelatedexpenses= $this->expensesdata['monthlytotalrelatedexpenses'];
		$monthlytotalexpenses		= $this->expensesdata['monthlytotalexpenses'];
		
	
		$thtml = new HTMLTable();
		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
					
		
		$thtml->addLTHRow(array_merge(array("As of Period's End " . $years[0]), str_replace("20","'", $months)));
	
		$monthlycash 					= $this->loansdata['monthlycash'];
		$accountReceivable_allMonths 	= $this->loansdata['accountReceivable_allMonths'];
		
		$thtml->addLTDRow(array_merge( array('Cash') , $this->farraynumber($monthlycash) ), null);
		
		$AccountReceivable = $this->expensesdata['TotalAccountsReceivable'];
		
		$thtml->addLTDRow(array_merge( array('Accounts Receivable') , $this->farraynumber($AccountReceivable) ), null);
		
		$totalcurrentassets_monthly = $this->loansdata['totalcurrentassets_monthly'];
				
		$thtml->addLTDRow(array_merge( array('Total Current Assets') , $this->farraynumber($totalcurrentassets_monthly) ), array('t'=>'normal','s'=>'bold'));
		
		
		$monthly_acculongtermassets = $this->profitlossdata['monthly_acculongtermassets'];
		
				
		$thtml->addLTDRow(array_merge( array('Long Term Assets') , $this->farraynumber($monthly_acculongtermassets) ), null);
		
		$monthly_accudepreciation = $this->profitlossdata['monthly_balaccudepreciation'];
		
		
		$thtml->addLTDRow(array_merge( array('Accumulated Depreciation') , $this->farraynumber($monthly_accudepreciation) ), null);
		
		$monthly_totallongassets = $this->profitlossdata['monthly_totallongassets'];
		
		$thtml->addLTDRow(array_merge( array('Total Long-Term Assets') , $this->farraynumber($monthly_totallongassets) ), array('t'=>'normal','s'=>'bold'));
		
		//calculate monthly total assets
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $totalcurrentassets_monthly[$i] + 	$monthly_totallongassets[$i];				
		}
		
		$thtml->addLTDRow(array_merge( array('Total Assets') , $this->farraynumber($tmpvalues) ), array('t'=>'total','s'=>'bold'));
				
		$thtml->addLTDRow(array_merge( array('Accounts Payable') , $this->farraynumber($this->loansdata['MonthlyAccountsPayable']) ), null);
		
		$thtml->addLTDRow(array_merge( array('Total Current Liability') , $this->farraynumber($this->loansdata['MonthlyAccountsPayable']) ), array('t'=>'total','s'=>'bold'));
		
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			$tmpvalues[$i] = $this->loansdata['$monthlyreceive'][$i] - 	$this->loansdata['$monthlypayment'][$i];
		}
		
		$this->balancesheetdata['monthlylongtermdebt'] = $tmpvalues;
		
		$thtml->addLTDRow(array_merge( array('Long Term Debt') , $this->farraynumber($tmpvalues) ), null);
		
		$tmpvalues = array();
		for ($i = 0; $i < 12; $i++ ) {
			//$tmpvalues[$i] = $this->loansdata['MonthlyAccountsPayable'][$i] + 	$this->loansdata['$monthlypayment'][$i];
			$tmpvalues[$i] = $this->loansdata['MonthlyAccountsPayable'][$i];
		}
		
		$thtml->addLTDRow(array_merge( array('Total Liabilities') , $this->farraynumber($tmpvalues) ), array('t'=>'total','s'=>'bold'));
		
		
		$monthlyrows = $this->salesdata['netprofit'];

		
		
		
		
		
		$retainedEarning 	= array();
		$retainedEarning[0] = 0; 
		for ($i = 1; $i < 12; $i++ ) {
			$retainedEarning[$i] = $retainedEarning[$i-1] + $monthlyrows[$i-1];		
		}
		
		$thtml->addLTDRow(array_merge(array('Retained Earnings'),$this->farraynumber($retainedEarning)), null);
		
		$thtml->addLTDRow(array_merge(array('Earnings'),$this->farraynumber($monthlyrows)), null);
		
		
		$totalequity = array();
		for ($i = 0; $i < 12; $i++ ) {
			$totalequity[$i] = $retainedEarning[$i] + $monthlyrows[$i];
		}
		
		$thtml->addLTDRow(array_merge( array('Total Owner Equity') , $this->farraynumber($totalequity) ), array('t'=>'total','s'=>'bold'));
		
		$totalliabilityequity = array();
		
		for ($i = 0; $i < 12; $i++ ) {
			$totalliabilityequity[$i] = $totalequity[$i] + $tmpvalues[$i];
		}
		
		$thtml->addLTDRow(array_merge( array('Total Liability and Equity') , $this->farraynumber($totalliabilityequity) ), array('t'=>'total','s'=>'bold'));
		
		/*
		$thtml->add1LColRow("Expenses", 'bold', 'left', 'normal', 13);
	
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($monthlytotalsalary)), null);
	
		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($monthlytotalrelatedexpenses)), null);
			
		$thtml->addLTDRow(array_merge(array('Total Expenses'),$this->farraynumber($monthlytotalexpenses)),
				array('s'=>'bold','t'=>'total'));
		*/
	
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	
		
		
	
	
		$years 	= $this->salesdata['years'];
	
		//data is calculated in expenses
		$yearlyTotalSalary		= $this->expensesdata['yearlyTotalSalary'];
		$yearlyTotalRSalary		= $this->expensesdata['yearlyTotalRSalary'];
		$yearlyTotalExpenses	= $this->expensesdata['yearlyTotalExpenses'];
	
		$pdf->Ln(2);
	
		$thtml = new HTMLTable();
	
		$thtml->addLTHRow(array_merge(array("As of Period's End"),$years));
	
		/*
		$thtml->add1LColRow("Expenses", 'bold', 'left', 'normal', 4);
	
		$thtml->addLTDRow(array_merge(array('Salary'),$this->farraynumber($yearlyTotalSalary)), null);
		$thtml->addLTDRow(array_merge(array('Employee Related Expenses'),$this->farraynumber($yearlyTotalRSalary)), null);
	
	
		$thtml->addLTDRow(array_merge(array('Total Expenses'),$this->farraynumber($yearlyTotalExpenses)),
				array('s'=>'bold','t'=>'total'));
	
		*/
		
		//include(LIBRARY_PATH . '/pdf_calc_extended.php');
		
		
		//$thtml->addLTDRow($this->farraynumber($this->balancesheetdata['cash']));
				
		//$thtml->addLTDRow($this->balancesheetdata['accountreceivablerows']);

		$thtml->addLTDRow(array_merge(array('Cash'), $this->farraynumber($this->balancesheetdata['balcash'])));
		
		$thtml->addLTDRow(array_merge(array('Accounts Receivable'), $this->farraynumber($this->balancesheetdata['balaccreceivable'])));
		
		
		//$thtml->addLTDRow($this->balancesheetdata['currentassets'], array('t'=>'normal','s'=>'bold'));
				
		$td = array(); //build empty row
		
		foreach($years as $yr){
			$td[] = '';
		}
		
		$thtml->addLTDRow($td);
		
		//data of the follwing is calculate in pdf_calc_cash 
		$balTotalCurrentAssets 	= $this->profitlossdata['ns']['balTotalCurrentAssets'];
		$balLongTermsAssets 	= $this->profitlossdata['ns']['balLongTermsAssets'];
		$balAccuDepreciation 	= $this->profitlossdata['ns']['balAccuDepreciation'];
		$balTotalLongTermsAssets = $this->profitlossdata['ns']['balTotalLongTermsAssets'];
		$balTotalAssets 		= $this->profitlossdata['ns']['balTotalAssets'];
		
		$thtml->addLTDRow(array_merge(array('Total Current Assets'), $this->farraynumber($balTotalCurrentAssets)), array('t'=>'normal','s'=>'bold'));
		//$thtml->addLTDRow($this->balancesheetdata['longtermassets']);
		//$longtermassets = array("Long Term Assets");
		//note: add empty row before
		//note no data to pick up for long term assets
		
		
		$thtml->addLTDRow(array_merge(array('Long-Term Assets'), $this->farraynumber($balLongTermsAssets)));
		//$thtml->addLTDRow($this->balancesheetdata['depreciations']);
		//$depreciations = array("Accumulated Depreciation");
		//note: value in website is hardcoded to zero
		
		$thtml->addLTDRow(array_merge(array('Accumulated Depreciation'), $this->farraynumber($balAccuDepreciation)));
		$thtml->addLTDRow(array_merge(array('Total Long-term Assets'), $this->farraynumber($balTotalLongTermsAssets)), array('t'=>'total','s'=>'bold'));
		
		//$thtml->addLTDRow($this->balancesheetdata['totallongtermassets'], array('t'=>'normal','s'=>'bold'));
		//$totallongtermassets = array("Total Long-Term Assets");
		//note: harcoded in website
		
		$thtml->addLTDRow(array_merge(array('Total Assets'), $this->farraynumber($balTotalAssets)), array('t'=>'normal','s'=>'bold'));
		//$thtml->addLTDRow($td); //empty row		
		//$thtml->addLTDRow($this->balancesheetdata['totalassets'], array('t'=>'total','s'=>'bold'));
		//$totalassets = array("Total Assets");
		//note: add empty line before
		
		$yrAccountPayable = $this->balancesheetdata['balaccpayable'];
		
		$thtml->addLTDRow($td); //empty row
		$thtml->addLTDRow(array_merge(array('Accounts Payable '), $this->farraynumber($yrAccountPayable)));
		//$thtml->addLTDRow($this->balancesheetdata['accountpayablerows']);
		//$accountpayable = array("Account Payable");
		//note: add empty line before
		
		$balTotalCurrentLiability = $this->profitlossdata['ns']['balTotalCurrentLiability'];
		
		$thtml->addLTDRow(array_merge(array('Total Current Liabilities'), $this->farraynumber($balTotalCurrentLiability)), array('t'=>'total','s'=>'bold'));
		
		//$table->addTDRow($salestaxespayable);
		// $salestaxespayable = array("Sales Taxes Payable");
		// note: hard coded to zero in website
		
		
		//$table->addTDRow($shorttermdebt);
		//$shorttermdebt = array("Short-Term Debt");
		//note: hard coded to zero in website
		
		//$thtml->addLTDRow($this->balancesheetdata['totalcurrentliability'], array('t'=>'normal','s'=>'bold'));
		//$totalcurrentliability = array("Total Current Liabilities");
		//note: hard coded to zero in website
		
		$balLongTermDebt 	= $this->profitlossdata['ns']['balLongTermDebt'];
		$balTotaLiabilities	= $this->profitlossdata['ns']['balTotaLiabilities'];
		
		$thtml->addLTDRow(array_merge(array('Long Term Debt'), $this->farraynumber($balLongTermDebt)), array('t'=>'normal','s'=>'bold'));
		
		$thtml->addLTDRow(array_merge(array('Total Liabilities'), $this->farraynumber($balTotaLiabilities)), array('t'=>'total','s'=>'bold'));
		
		
		$balEarnings 			= $this->profitlossdata['ns']['balEarnings'];
		$balRetainedEarnings 	= $this->profitlossdata['ns']['balRetainedEarnings'];
		$balTotalOwnerEquity	= $this->profitlossdata['ns']['balTotalOwnerEquity'];
		$balTotalLiabilitiesAndEquities = $this->profitlossdata['ns']['balTotalLiabilitiesAndEquities'];
		
		
		$thtml->addLTDRow(array_merge(array('Retained Earnings'), $this->farraynumber($balRetainedEarnings)), array('t'=>'normal','s'=>'bold'));
		$thtml->addLTDRow(array_merge(array('Earnings'), $this->farraynumber($balEarnings)), array('t'=>'normal','s'=>'bold'));
		$thtml->addLTDRow(array_merge(array('Total Owner Equity'), $this->farraynumber($balTotalOwnerEquity)), array('t'=>'total','s'=>'bold'));
		$thtml->addLTDRow(array_merge(array('Total Liabilities & Equity'), $this->farraynumber($balTotalLiabilitiesAndEquities)), array('t'=>'total','s'=>'bold'));
		
		//$thtml->addLTDRow($td); //empty row
		//$thtml->addLTDRow($this->balancesheetdata['longtermdebt']);
		//$longtermdebt = array("Long-Term Debt");
		//note add empty line before
		
		
		
		
		//$thtml->addLTDRow($this->balancesheetdata['$totalliability'], array('t'=>'normal','s'=>'bold'));
		//$totalliability = array("Total Liabilities");
		//note: add empty row before
		//hardcoded to zero in web
		
		
		
		
		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
	
	}

	protected function renderAppendixCashFlowStatement($page_repo, $pdf, $menu)
	{
		$this->writeAppendixCashFlowStatementTable($pdf);
	}

	protected function writeAppendixCashFlowStatementTable($pdf)
	{
		$pdf->writeH3('Cash Flow Statement (With Monthly Detail)');
		$pdf->Ln(2);
		$thtml = new HTMLTable();

		$months = $this->salesdata['months'];
		$years 	= $this->salesdata['years'];
	
		$total = count($this->profitlossdata['monthlynetprofit']);

		$operations = array();
		$change_receivable = array();
		$change_payable = array();

		$investing_and_finance = array();
		$change_longterm = array();

		$net_change = array();
		$end_of_period = array();
		$beginning_of_period = array();
		for ($i = 0; $i < $total; $i++) {
			if ($i == 0) {
				$change_receivable[$i] = 0 - $this->loansdata['accountReceivable_allMonths'][$i];
				$change_payable[$i] = $this->loansdata['MonthlyAccountsPayable'][$i];
			}
			else {
				$change_receivable[$i] = $this->loansdata['accountReceivable_allMonths'][$i - 1] - $this->loansdata['accountReceivable_allMonths'][$i];
				$change_payable[$i] = $this->loansdata['MonthlyAccountsPayable'][$i] - $this->loansdata['MonthlyAccountsPayable'][$i - 1];
			}
			$operations[] = $this->profitlossdata['monthlynetprofit'][$i]
				+ $this->profitlossdata['monthlydepreciation'][$i]
				+ $change_receivable[$i]
				+ $change_payable[$i];

			// --
			if ($i == 0) {
				$change_longterm[$i] = $this->balancesheetdata['monthlylongtermdebt'][$i];
			}
			else {
				$change_longterm[$i] = $this->balancesheetdata['monthlylongtermdebt'][$i] - $this->balancesheetdata['monthlylongtermdebt'][$i - 1];
			}
			$investing_and_finance[$i] = $this->expensesdata['monthlytotalmajorpurchases'][$i]
				+ $change_longterm[$i];


			// --
			$net_change[$i] = $operations[$i] + $investing_and_finance[$i];

			if ($i == 0) {
				$beginning_of_period[$i] = 0;
			}
			else {
				$beginning_of_period[$i] = $end_of_period[$i - 1];
			}

			$end_of_period[$i] = $beginning_of_period[$i] + $net_change[$i];

		}
		$thtml->addLTHRow(array_merge(array($years[0]), str_replace("20","'", $months)));
		$thtml->add1LColRow("Operations", 'bold', 'left', 'normal', 13);

		$thtml->addLTDRow(array_merge(array('Net Profit'), $this->farraynumber($this->profitlossdata['monthlynetprofit'])));
		$thtml->addLTDRow(array_merge(array('Depreciation and Amortization'), $this->farraynumber($this->profitlossdata['monthlydepreciation'])));
		$thtml->addLTDRow(array_merge(array('Change in Accounts Receivable'), $this->farraynumber($this->loansdata['accountReceivable_allMonths'])));
		$thtml->addLTDRow(array_merge(array('Change in Accounts Payable') , $this->farraynumber($this->loansdata['MonthlyAccountsPayable']) ), null);
		$thtml->addLTDRow(array_merge(array('Net Cash Flow from Operaitions'), $this->farraynumber($operations)), array('t'=>'total','s'=>'bold'));



		//$investments_received = $this->profitlossdata['monthlyinvestmentsreceived'];


		$thtml->add1LColRow("Investing and Financing", 'bold', 'left', 'normal', 13);
		$thtml->addLTDRow(array_merge(array('Assets Purchases or Sold'), $this->farraynumber($this->expensesdata['monthlytotalmajorpurchases'])));
		//$thtml->addLTDRow(array_merge(array('Investments Received'), $this->farraynumber($investments_received)));
		$thtml->addLTDRow(array_merge(array('Change in Long Term Debt'), $this->farraynumber($this->balancesheetdata['monthlylongtermdebt'])));
		$thtml->addLTDRow(array_merge(array('Net Cash Flow from Investing and Finance'), $this->farraynumber($investing_and_finance)), array('t'=>'total','s'=>'bold'));




		// cash at end of period

		$thtml->add1LColRow("", 'bold', 'left', 'normal', 13);
		$thtml->addLTDRow(array_merge(array('Cash at Beginning of Period'), $this->farraynumber($beginning_of_period)));
		$thtml->addLTDRow(array_merge(array('Net Change in Cash'), $this->farraynumber($net_change)));
		$thtml->addLTDRow(array_merge(array('Cash at End of Period'), $this->farraynumber($end_of_period)), array('t'=>'total','s'=>'bold'));

		$pdf->writeHTML($thtml->getHTML(), true, false, false, false, 'L');
		// render monthly data
		$pdf->AddPage('L');
		$this->writeCashFlowStatementTable($pdf);
	}

	
}

