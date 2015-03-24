<?php
/* UNCOMMENT TO USE AS STAND ALONE
 * 
error_reporting(E_ALL);
$display_errors = isset($_GET['de']) ? 1 : 0;
ini_set('display_errors', $display_errors);

if(!defined("MAIN_FOLDER")) {
	//CHange this to match the folder in whcih you place this in
	define ("MAIN_FOLDER", "public/bizplannerpro");
}

$slash = (substr($_SERVER['DOCUMENT_ROOT'], -1)=='/'? '' : '/');

if(!defined("BASE_PATH")) {
	//define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/".MAIN_FOLDER);
	define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . $slash . MAIN_FOLDER);
}


//if(!defined("BASE_URL")) { //test one constant in Definitions
	require_once(BASE_PATH."/Definitions.php");
//}


// library files
include_once(LIBRARY_PATH."/global_lib.php");
include_once(LIBRARY_PATH."/page_lib.php");
include_once(LIBRARY_PATH."/register_lib.php");
include_once(LIBRARY_PATH."/activate_lib.php");
include_once(LIBRARY_PATH."/login_lib.php");
include_once(LIBRARY_PATH."/update_lib.php");

include_once(LIBRARY_PATH."/archive_lib.php");
include_once(LIBRARY_PATH."/employee_lib.php");
include_once(LIBRARY_PATH."/expenditure_lib.php");
include_once(LIBRARY_PATH."/sales_forecast_lib.php");
include_once(LIBRARY_PATH."/cashFlowProjection_lib.php");
include_once(LIBRARY_PATH."/LoansInvestments_lib.php");
include_once(LIBRARY_PATH."/jpgraph_lib.php");
include_once(LIBRARY_PATH."/BusinessPlan_lib.php");
include_once(LIBRARY_PATH."/writeToFile.php");
include_once(LIBRARY_PATH."/bizplannerpro_pdf.php");



//include_once(LIBRARY_PATH."/FormData.php");

if(!class_exists('format_FrontEndFormat'))
{
	require_once(CLASS_PATH. "/format/frontendformat.php");
}


require(CLASS_PATH.'/Settings/Settings.php');




require_once(ERROR_CLASS_PATH.'/CustomException.php');
require_once(CREDENTIALS_PATH."/Credentials.php");
require_once(DB_CLASS_PATH.'/Database.php');


//connect to the database
try{
	//$db = new Database();
	//$db->connect();

}
catch(CustomException $e)
{
	$e->logError("file");

}



$global_func = new global_lib();
$outputMsg = array();
$color = array();
$msgs = '';

ob_start();

if(!isset($_SESSION)) {
	session_start();
}


*/


class WebCalcFull {

	public $currency	= "";
	public $months		= array();
	public $years		= array();
	public $fyyears		= array();
	
	// Sales & Direct Costs	
	public $monthlyunitsales	= array();
	public $monthlypriceperunit	= array();
	public $monthlysales		= array();
	public $monthlytotalsales	= array();
	
	public $monthlydirectunitcosts	= array();
	public $monthlydirectcosts		= array();
	public $monthlytotaldirectcosts = array();
	
	public $monthlygrossmargin		= array();
	public $monthlygrossmarginpercent	= array();
	
	public $yearlyunitsales		= array();
	public $yearlypriceperunit	= array();
	public $yearlysales			= array();
	public $yearlytotalsales	= array();
	
	public $yearlydirectunitcosts	= array();
	public $yearlydirectcosts		= array();
	public $yearlytotaldirectcosts	= array();
	
	public $yearlygrossmargin		= array();
	public $yearlygrossmarginpercent= array();
	
	//End Sales & Direct Costs
	
	//Personnel Plan
	public $monthlysalary		= array();
	public $monthlytotalsalary 	= array();
	
	public $yearlysalary		= array();
	public $yearlytotalsalary	= array();
	//End Personnel Plan
	
	//Budget
	//Salary is in $monthlysalary
	public $monthlyemployeeexpenses		= array();	
	public $monthlyexpenses				= array();
	public $monthlytotaloperatingexpenses = array();
	
	public $monthlymajorpurchases		= array();	
	public $monthlytotalmajorpurchases	= array();
	
	public $yearlyemployeeexpenses		= array();
	public $yearlyexpenses				= array();
	public $yearlytotaloperatingexpenses = array();
	
	public $yearlymajorpurchases		= array();	
	public $yearlytotalmajorpurchases	= array();
	
	public $monthlydepreciation			= array();
	public $monthlyaccudepreciation	= array();
	
	//End Budget
	
	//Loans and Investments
	public $monthlyloans				= array();
	public $monthlytotalamountreceive	= array();
	
	public $monthlyrepayments			= array(); //estimated repayment	
	public $monthlyestimatedbalance		= array();
	public $monthlyestimatedinterest	= array();
	
	public $monthlytotalamountrepaid	= array(); //estimated repayment
	public $monthlytotalbalance			= array(); //estimated repayment
	public $monthlytotalinterest		= array();
	
	public $yearlyloans					= array();
	public $yearlytotalloans			= array();
	public $yearlyamountreceive			= array();
	public $yearlyamountrepaid			= array();
	public $yearlyinterest				= array();
	public $yearlyrepayments			= array();

	
	//Investments
	public $monthlyinvestments						= array();
	public $monthlyinvestmentsrepaid				= array();
	public $monthlytotalinvestmentsamountreceive	= array();
	public $monthlytotalinvestmentsamountrepaid		= array();
		
	public $yearlyinvestment						= array();
	public $yearlytotalinvestment					= array();
	public $yearlynetinvestment						= array();
	
	
	
	
	//End Loans and Investments

	
	//Profit and Loss
	public $monthlyoperatingincome 		= array();
	public $monthlypretaxprofit			= array();
	public $monthlyincometax			= array();
	public $monthlytotalexpenses		= array();
	public $monthlynetprofit			= array();
	
	public $yearlyrevenue				= array();
	public $yearlydirectcost			= array();
	public $yearlypfgrossmargin			= array();
	public $yearlypfgrossmarginpercent	= array();
	
	public $yearlyoperatingincome		= array();
	public $yearlydepreciation			= array();
	public $yearlypretaxprofit			= array();
	public $yearlyincometax				= array();
	public $yearlytotalexpenses			= array();
	public $yearlynetprofit				= array();
	public $yearlynetprofitpercent		= array();
	//End of Profit and Loss
	

	//Accounts Receivable
	public $monthlyaccountsreceivable	= array();	
	public $monthlyreceivabletotalcashcollected	= array();
	public $truemonthlyaccountsreceivable = array();
	//End Accounts Receivable
	
	//Accounts Payable
	public $monthlyaccountspayable		= array();
	public $monthlypayabletotalcashcolleted	= array();
	//End Accounts Payable
	
	//Balance Sheet
	public $monthlycash					= array();
	public $monthlytotalcurrentassets	= array();
	public $monthlylongtermassets	= array();
	public $monthlytotallongtermassets	= array();
	public $monthlytotalassets			= array();
	
	public $monthlytotalliability		= array();
	public $monthlypaidincapital		= array();
	public $monthlyretainedearnings		= array();
	public $monthlyearnings				= array();
	public $monthlyownerequity			= array();
	public $monthlyliabilityandequity	= array();
	
	public $yearlycash					= array();
	public $yearlyaccountsreceivable	= array();
	public $yearlytotalcurrentassets	= array();
	public $yearlylongtermassets		= array();
	public $yearlyaccudepreciation		= array();
	public $yearlytotallongtermassets	= array();
	public $yearlytotalassets			= array();
	
	public $yearlyaccountspayable		= array();
	public $yearlytotalcurrentliabilities	= array();
	public $yearlylongtermdebt			= array();
	public $yearlytotalliabilities		= array();
	public $yearlyretainedearnings		= array();
	public $yearlyearnings				= array();
	public $yearlytotalownerequity		= array();
	public $yearlytotalliabilityandEquity	= array();
	
	
	//End Balance Sheet
	
	//Cash Flow
	public $changeinaccountsreceivable		= array();
	public $changeinaccountspayable			= array();
	
	public $assetspurchasedorsold			= array();
	public $changeinlongtermdebt			= array();
	
	public $netcashflowfromoperations		= array(); 
	public $netcashflowfrominvesting		= array();
	public $cashatbeginningofperiod			= array();
	public $netchangeincash					= array();
	public $cashatendofperiod				= array();
	
	
	public $yearlychangeinaccountsreceivable	= array();
	public $yearlychangeinaccountspayable		= array();
	
	public $yearlyassetspurchasedorsold			= array();
	public $yearlychangeinlongtermdebt			= array();
	
	public $yearlynetcashflowfromoperations		= array();
	public $yearlynetcashflowfrominvesting		= array();
	public $yearlycashatbeginningofperiod		= array();
	public $yearlynetchangeincash				= array();
	public $yearlycashatendofperiod				= array();
	
	
	//End Cash Flow
	
	
	public function build()
	{
		
		$this->renderSalesForecast();
		$this->renderPersonnelPlan();
		$this->renderBudget();
		$this->renderLoans();
		$this->renderInvestments();
		$this->renderProfitAndLoss();
		$this->renderAccountsReceivable();
		$this->renderBalanceSheet();
		$this->renderCashFlow();
		
		
		
	}

	
	public function displayData()
	{
		
		
		$str = <<<EOD
<style>
body
{
	line-height: 1.6em;
}
		
.hor-minimalist-a
{
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	background: #fff;
	/*margin: 45px;*/
	width: 1140;
	border-collapse: collapse;
	text-align: left;
}
.hor-minimalist-a th
{
	font-size: 14px;
	font-weight: normal;
	color: #039;
	padding: 10px 8px;
	border-bottom: 2px solid #6678b1;
}
.hor-minimalist-a td
{
	color: #669;
	padding: 9px 8px 0px 8px;
	border-bottom: 1px solid #ccc;
	text-align: right;
}
</style>
EOD;

		
	echo $str;	

		echo "<b>Sales Forecast</b><br>";
		$this->displaySalesForecast();
		
		
		echo "<br><b>Personnel Plan</b><br>";
		$this->displayPersonnelPlan();
		
		
		echo "<br><b>Budget</b><br>";
		$this->displayBudget();
		
		
		echo "<br><b>Loans</b><br>"  ;
		$this->displayLoans();
		
		
		echo "<br><b>Profit and Loss</b><br>" ;
		$this->displayProfitAndLoss();
		
		echo "<br><b>Accounts Receivable and Payable</b><br>"  ;
		
		echo "<br><b>Balance Sheet</b><br>"  ;
		
		$this->displayBalanceSheet();
		
		echo "<br><b>Cash Flow</b><br>"  ;
		
		$this->displayCashFlow();
		
		
	}

	// FINANCIAL PLAN

	protected function renderSalesForecast()
	{
		
		try{
		
			$sales_forecast_lib = new sales_forecast_lib();
			$sales 				= $sales_forecast_lib->getAllSales('', '', '');
			$this->currency		= $sales_forecast_lib->defaultCurrency;
			//highlight_string(var_export($sales, true));
			
			if(!$sales) { return;  }
			
			$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
			$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
			$list12Months = $sales_forecast_lib->twelveMonths($start_years, $start_month);
						
			foreach($list12Months as $monthList)
			{
				$this->months[]  = date("M", strtotime($monthList));
			}
			
			
			$productcounter = 1;
										
			foreach($sales as $product)
			{
				$unitsales = array();
				$unitsales['name'] 	= $product['sales_forecast_name'];
				
				$unitprice			= array();
				$unitprice['name']	= $unitsales['name'];
				
				$msales				= array();
				$msales['name']		= $unitsales['name'];
				
				$directunitcosts		= array();
				$directunitcosts['name']= $unitsales['name'];
				
				$directcosts			= array();
				$directcosts['name']	= $unitsales['name'];
										
				
				for($i=1;$i<13;$i++) {
					
					$index 			= str_pad($i, 2, "0", STR_PAD_LEFT);	
					$unitsales[$i]	= floatval($product['month_' . $index]);
					$unitprice[$i]	= floatval($product['price']);
					$msales[$i]		= floatval($unitsales[$i])*floatval($unitprice[$i]);
					
					if(isset($this->monthlytotalsales[$i])) {
						$this->monthlytotalsales[$i]	+= $msales[$i];
					} else {
						$this->monthlytotalsales[$i]	= $msales[$i];
					}		
					
					
					$directunitcosts[$i] 	= floatval($product['cost']);
					$directcosts[$i]		= floatval($unitsales[$i])*floatval($product['cost']);
					
					
					if(isset($this->monthlytotaldirectcosts[$i])) {
						$this->monthlytotaldirectcosts[$i] += $directcosts[$i];
					} else {
						$this->monthlytotaldirectcosts[$i] = $directcosts[$i];
					}	
														
				}
	
				$this->monthlyunitsales[$productcounter] 		= $unitsales;
				
				$this->monthlypriceperunit[$productcounter]		= $unitprice;
				
				$this->monthlysales[$productcounter]			= $msales;
				
				$this->monthlydirectunitcosts[$productcounter] 	= $directunitcosts;
				
				$this->monthlydirectcosts[$productcounter]		= $directcosts;
										
				
				$productcounter++;
				
			}
			
			
			for($i=1;$i<13;$i++) {
			
				$this->monthlygrossmargin[$i] = $this->monthlytotalsales[$i] - $this->monthlytotaldirectcosts[$i];				
			
				$this->monthlygrossmarginpercent[$i] = $this->monthlygrossmargin[$i] / $this->monthlytotalsales[$i];
			
			}
			
			
			
			//yearly sales
			$productcounter = 1;
			
			foreach($sales as $product)
			{
				$unitsales = array();
				$unitsales['name'] 	= $product['sales_forecast_name'];
				
				$unitprice			= array();
				$unitprice['name']	= $unitsales['name'];
				
				$msales				= array();
				$msales['name']		= $unitsales['name'];
				
				$directunitcosts		= array();
				$directunitcosts['name']= $unitsales['name'];
				
				$directcosts			= array();
				$directcosts['name']	= $unitsales['name'];
				
								
				for($i=1;$i<4;$i++) {
					
					$unitsales[$i]	= floatval($product['financial_status'][$i-1]['total_per_yr']);
					$unitprice[$i]	= floatval($product['price']);
					$msales[$i]		= floatval($unitsales[$i])*floatval($unitprice[$i]);
						
					
					if(isset($this->yearlytotalsales[$i])) {
						$this->yearlytotalsales[$i]	+= $msales[$i];
					} else {
						$this->yearlytotalsales[$i]	= $msales[$i];
					}
					
					
						
					$directunitcosts[$i] 	= floatval($product['cost']);
					$directcosts[$i]		= floatval($unitsales[$i])*floatval($product['cost']);

					
					if(isset($this->yearlytotaldirectcosts[$i])) {
						$this->yearlytotaldirectcosts[$i] += $directcosts[$i];
					} else {
						$this->yearlytotaldirectcosts[$i] = $directcosts[$i];
					}
					
					
					
						
				}
				
				$this->yearlyunitsales[$productcounter] 	= $unitsales;
				
				$this->yearlypriceperunit[$productcounter]	= $unitprice;
				
				$this->yearlysales[$productcounter]			= $msales;
				
				$this->yearlydirectunitcosts[$productcounter] =	$directunitcosts;
				
				$this->yearlydirectcosts[$productcounter]	= $directcosts;
				
				
				$productcounter++;
			}
			
			for($i=1;$i<4;$i++) {
					
				$this->yearlygrossmargin[$i] = $this->yearlytotalsales[$i] - $this->yearlytotaldirectcosts[$i];
					
				$this->yearlygrossmarginpercent[$i] = $this->yearlygrossmargin[$i] / $this->yearlytotalsales[$i];
					
			}
			
			
		} catch(Exception $e)
		{
			echo $e->getMessage();
		}

	}
	
	
	protected function renderPersonnelPlan()
	{
		$employee       	= new employee_lib();		
		$allEmpDetails  	= $employee->getAllEmployeeDetails2("", "", ""); // All employees
		$allRelatedExpenses = $allEmpDetails; // All employees for related expenses calculation
		
		//highlight_string(var_export($allEmpDetails,true));
		
		
		$productcounter = 1;
		
		if(!$allEmpDetails) {
			return;
		}
		
		
		foreach($allEmpDetails as $item)
		{
			$monthlyitem = array();
			$monthlyitem['name'] 	= $item['emplye_name'];
					
			for($i=1;$i<13;$i++) {
					
				$index 				= str_pad($i, 2, "0", STR_PAD_LEFT);
				$monthlyitem[$i]	= floatval($item['month_' . $index]);
				
					
				if(isset($this->monthlytotalsalary[$i])) {
					$this->monthlytotalsalary[$i]	+= $monthlyitem[$i];
				} else {
					$this->monthlytotalsalary[$i]	= $monthlyitem[$i];
				}
				
		
			}
		
			$this->monthlysalary[$productcounter] 		= $monthlyitem;
				
			$productcounter++;
		
		}
			
			
		//yearly personnel plan
		$productcounter = 1;
			
		foreach($allEmpDetails as $item)
		{
			$yearlyitem = array();
			$yearlyitem['name'] 	= $item['emplye_name'];
		
		
			for($i=1;$i<4;$i++) {
					
				$yearlyitem[$i]	= floatval($item['financial_status'][$i-1]['total_per_yr']);
						
					
				if(isset($this->yearlytotalsalary[$i])) {
					$this->yearlytotalsalary[$i]	+= $yearlyitem[$i];
				} else {
					$this->yearlytotalsalary[$i]	= $yearlyitem[$i];
				}	
		
			}
		
			$this->yearlysalary[$productcounter] 	= $yearlyitem;
		
		
			$productcounter++;
		}
		
		
		
	}
	
	protected function renderBudget(){
		//mothly operating expenses
		//Salary is in $this->monthlytotalsalary
		$i = 1;
		
		$salaryexpensepercentage = 0.2; 
		
		foreach($this->monthlytotalsalary as $totalsalary) {
			$this->monthlyemployeeexpenses[$i] = $totalsalary * $salaryexpensepercentage;
			$i++;
		}

		$expenditure    = new expenditure_lib();		
		$allExpDetails  = $expenditure->getAllExpenditureDetails("", "", ""); // All Expenditures

		if (!$allExpDetails) {
			return;
		}
		
		foreach ($allExpDetails[0]['financial_status'] as $eachFinStat)
		{
			$this->years[] = $eachFinStat['financial_year'];
			$this->fyyears[] = "FY" . $eachFinStat['financial_year'];
		}
		
		
		$productcounter = 1;
		
		foreach($allExpDetails as $item)
		{
			$monthlyitem = array();
			$monthlyitem['name'] 	= $item['expenditure_name'];
					
			for($i=1;$i<13;$i++) {
					
				$index 				= str_pad($i, 2, "0", STR_PAD_LEFT);
				$monthlyitem[$i]	= floatval($item['month_' . $index]);
				
					
				if(isset($this->monthlytotaloperatingexpenses[$i])) {
					$this->monthlytotaloperatingexpenses[$i]	+= $monthlyitem[$i];
				} else {					
					$this->monthlytotaloperatingexpenses[$i]	= $monthlyitem[$i];
				}
					
			}
		
			$this->monthlyexpenses[$productcounter] 		= $monthlyitem;
				
			$productcounter++;
		
		}
		
		for($i=1;$i<13;$i++) {				
			$this->monthlytotaloperatingexpenses[$i] += $this->monthlytotalsalary[$i] + $this->monthlyemployeeexpenses[$i];				
		}
		
		$major_purchases = $expenditure->calculateMajorPurchases();
				
		$counter = 1;
		
		foreach($major_purchases['monthlymajorpurchasesindexed'] as $key=>$purchase ) {
			
			$this->monthlymajorpurchases[$counter] = array($key);
			
			
			
			for($i=1;$i<13;$i++) {
				$this->monthlymajorpurchases[$counter][$i] = floatval($purchase[$i-1]);
				
				if(isset($this->monthlytotalmajorpurchases[$i])){
					$this->monthlytotalmajorpurchases[$i] += floatval($purchase[$i-1]);
				} else {
					$this->monthlytotalmajorpurchases[$i] = floatval($purchase[$i-1]);
				}				
			}
			
			
			
			$counter++;
			
		}
		
		
		//yearly budget
		//$yearlytotalsalary is in Personnel Plan
		$this->yearlyemployeeexpenses[1] = array_sum($this->monthlyemployeeexpenses);
		$this->yearlyemployeeexpenses[2] = $this->yearlytotalsalary[2] * 0.20;
		$this->yearlyemployeeexpenses[3] = $this->yearlytotalsalary[3] * 0.20;
		
		
		
		$counter = 1;
		
		
		
		
		foreach($allExpDetails as $item)
		{
			$this->yearlyexpenses[$counter]	= array($item['expenditure_name']);

			for($i = 1; $i < 4; $i++) {
				
				if($i>1) {
					$otmp = $item['financial_status'];
					$this->yearlyexpenses[$counter][$i] = $otmp[$i-1]['total_per_yr'];
				} else {
					$tmp = $this->monthlyexpenses[$counter];
					array_shift($tmp);
					$this->yearlyexpenses[$counter][$i] = array_sum($tmp);
				}			
				
				if(isset($this->yearlytotaloperatingexpenses[$i])) {
					$this->yearlytotaloperatingexpenses[$i] += $this->yearlyexpenses[$counter][$i];
				} else {
					$this->yearlytotaloperatingexpenses[$i] = $this->yearlyexpenses[$counter][$i];
				}					
				
			}
			
			$counter++;
		}
		
		for($i = 1; $i < 4; $i++) {
			$this->yearlytotaloperatingexpenses[$i] += $this->yearlyemployeeexpenses[$i] + $this->yearlytotalsalary[$i]; 
		}
		
		
		$counter = 1;
		
		foreach($major_purchases['yearlymajorpurchasesraw'] as $item) {
				
			$this->yearlymajorpurchases[$counter] = $item;
		
			for($i = 1; $i < 4; $i++) {
				if(isset($this->yearlytotalmajorpurchases[$i])){
					$this->yearlytotalmajorpurchases[$i] += $item[$i];
				} else {
					$this->yearlytotalmajorpurchases[$i] = $item[$i];
				}
				
			}	
			
			$counter++;
		}
		
		
		for($i = 1; $i < 13; $i++) {
			$this->monthlydepreciation[$i]		= $major_purchases['monthly_accudepreciation'][$i-1];
			$this->monthlyaccudepreciation[$i]	= $major_purchases['monthly_balaccudepreciation'][$i-1];
		}
		
		//highlight_string(var_export($major_purchases,true));
		
	}
	
	
protected function renderLoans()
	{

		$_loanInvestment 				= new loansInvestments_lib();
		$allloanInvestmentProjection 	= $_loanInvestment->getAllCashProjections("NOT (loan_investment.type_of_funding = 'Investment') ", "", "");
		
		$loans		= $allloanInvestmentProjection;		
		$counter 	= 1;
		
		
		
		foreach($loans as $loan){
			$this->monthlyloans[$counter]			= array();
			$this->monthlyloans[$counter]['name'] 	= $loan['loan_invest_name'];
			
			for($i=1;$i<13;$i++) {
				$index 				= str_pad($i, 2, "0", STR_PAD_LEFT);
				$this->monthlyloans[$counter][$i]	= floatval($loan['limr_month_' . $index]);	

				if(isset($this->monthlytotalamountreceive[$i])) {
					$this->monthlytotalamountreceive[$i] += floatval($loan['limr_month_' . $index]);
				} else {
					$this->monthlytotalamountreceive[$i] = floatval($loan['limr_month_' . $index]);
				}
								
				
			}
			
			$this->monthlyloans[$counter]['interestrate'] = floatval($loan['loan_invest_interest_rate']);
			$this->monthlyloans[$counter]['period'] = floatval($loan['loan_invest_pays_per_years']);
			$this->monthlyloans[$counter]['terms'] = floatval($loan['loan_invest_years_to_pay']);
			
			$counter++;
		}
		
		//highlight_string(var_export($allloanInvestmentProjection,true));
		
		
		
		$counter = 1;
		
		foreach($this->monthlyloans as $loan) {
			
			$interestrate = $loan['interestrate']/100;

			//$period	= 12;
			//$terms	= 3; //3 years
			//$pmtperiod = $period*$terms;
			
			$period	= $loan['period'];
			$terms	= $loan['terms'];
			$pmtperiod = $period*$terms;
			
			
			//echo "interest rate: " . $interestrate;
			
			//$this->monthlyrepayments[0]			= 0;
			//$this->monthlytotalamountrepaid[0]	= 0; //estimated repayment
			//$this->monthlyestimatedbalance[0]	= 0;
			
			//monthly repayment
			$this->monthlyestimatedbalance[$counter] 	= array($loan['name']);
			$this->monthlyestimatedinterest[$counter] 	= array($loan['name']);
			$this->monthlyrepayments[$counter]	= array($loan['name']);
			
						
			for($i=1;$i<13;$i++) {
				
				$tmpsum = 0;

				if($i!=1) {
					for($j = 1; $j <= $i; $j++){
						$tmpsum  += $loan[$j];
					}	
				} else {
					$tmpsum = $loan[$i];
				}
				
			
				if($i==1){
					$this->monthlyrepayments[$counter][$i] = 0;
				} elseif($this->monthlyestimatedbalance[$counter][$i-1] == 0) {
					$this->monthlyrepayments[$counter][$i] = 0;
				} elseif($tmpsum == 0) {
					$this->monthlyrepayments[$counter][$i] = 0;									
				} else {
					$this->monthlyrepayments[$counter][$i] = -self::PMT($interestrate/$period, $pmtperiod, $tmpsum);
				}
				
				//monthly estimated interest
				if($i==1){
					$this->monthlyestimatedinterest[$counter][$i] = 0;
				} elseif($this->monthlyestimatedbalance[$counter][$i-1] == 0) {
					$this->monthlyestimatedinterest[$counter][$i] = 0;				
				} else {
					$this->monthlyestimatedinterest[$counter][$i] = -self::IPMT($interestrate/$period, 1, $pmtperiod, $tmpsum);
				}
								
				
				$this->monthlyestimatedbalance[$counter][$i] = $this->monthlyestimatedbalance[$counter][$i-1] + $loan[$i] 
				- $this->monthlyrepayments[$counter][$i] 
				+ $this->monthlyestimatedinterest[$counter][$i];
				
				if(isset($this->monthlytotalamountrepaid[$i])) {
					$this->monthlytotalamountrepaid[$i]	+= $this->monthlyrepayments[$counter][$i];
					$this->monthlytotalbalance[$i]		+= $this->monthlyestimatedbalance[$counter][$i];
					$this->monthlytotalinterest[$i]		+= $this->monthlyestimatedinterest[$counter][$i];
				} else {
					$this->monthlytotalamountrepaid[$i]	= $this->monthlyrepayments[$counter][$i];
					$this->monthlytotalbalance[$i]		= $this->monthlyestimatedbalance[$counter][$i];
					$this->monthlytotalinterest[$i]		= $this->monthlyestimatedinterest[$counter][$i];
				}
				
			}
			
			$counter++;
		}
			
		//yearly
		
		
		$counter = 1;
		
		foreach($loans as $loan) {
			
			$this->yearlyloans[$counter] = array($loan['loan_invest_name']);
						
			for($i=1;$i<4;$i++) {
				
				$tmp = $loan['financial_receive'][$i-1];
				$this->yearlyloans[$counter][$i] = floatval($tmp['lir_total_per_yr']);
				
				$tmp = $loan['financial_payment'][$i-1];
				$this->yearlyamountrepaid[$i] 	= floatval($tmp['lip_total_per_yr']);
				
				
				if(isset($this->yearlytotalloans[$i]))
				{
					$this->yearlytotalloans[$i] += $this->yearlyloans[$counter][$i];
				}else {
					$this->yearlytotalloans[$i] = $this->yearlyloans[$counter][$i];
				}
			}						
			
			$counter++;
		}
		
		//hardcoded yearlyamountreceive pjj1 there is no advance loan yet as of 10may2014
		
		
		$this->yearlyamountreceive		= array(1=>0,2=>0,3=>0);//$this->yearlytotalloans;   	
		$this->yearlyinterest[1]		= array_sum($this->monthlytotalinterest);
		$this->yearlyrepayments[1]		= array_sum($this->monthlytotalamountrepaid);
		
		$tmpsum = $this->yearlyamountreceive[1] - $this->yearlyrepayments[1] + $this->yearlyinterest[1] + $this->yearlyamountreceive[2]; 
		
		if($tmpsum < 0 ) {
			$this->yearlyrepayments[2]		= 0;			
		} else {
			$this->yearlyrepayments[2]		= $this->yearlyrepayments[1];
		}
		
		$this->yearlyinterest[2]			= $this->yearlyinterest[1];
		$this->yearlyinterest[3]			= $this->yearlyinterest[2];
		
		$tmpsum = $tmpsum-$this->yearlyrepayments[2] + $this->yearlyinterest[2];
		
		if($tmpsum < 0 ) {
			$this->yearlyrepayments[3]		= 0;
		} else {
			$this->yearlyrepayments[3]		= $this->yearlyrepayments[2];
		}
		
		//highlight_string(var_export($loans,true));
		
		
		
	}
	
	
	protected function renderInvestments()
	{
	
		$_loanInvestment 				= new loansInvestments_lib();
		$allloanInvestmentProjection 	= $_loanInvestment->getAllCashProjections("(loan_investment.type_of_funding = 'Investment') ", "", "");
	
		$loans		= $allloanInvestmentProjection;
		$counter 	= 1;
	
	
	
		foreach($loans as $loan){
			$this->monthlyinvestments[$counter]			= array();
			$this->monthlyinvestments[$counter]['name'] 	= $loan['loan_invest_name'];
			$this->monthlyinvestmentsrepaid[$counter]['name'] = $loan['loan_invest_name'];
				
			for($i=1;$i<13;$i++) {
				$index 				= str_pad($i, 2, "0", STR_PAD_LEFT);
				$this->monthlyinvestments[$counter][$i]	= floatval($loan['limr_month_' . $index]);
				$this->monthlyinvestmentsrepaid[$counter][$i]	= floatval($loan['limp_month_' . $index]);
				
				if(isset($this->monthlytotalinvestmentsamountreceive[$i])) {
					$this->monthlytotalinvestmentsamountreceive[$i] += floatval($loan['limr_month_' . $index]);
				} else {
					$this->monthlytotalinvestmentsamountreceive[$i] = floatval($loan['limr_month_' . $index]);
				}
				
				if(isset($this->monthlytotalinvestmentsamountrepaid[$i])) {
					$this->monthlytotalinvestmentsamountrepaid[$i] += floatval($loan['limp_month_' . $index]);
				} else {
					$this->monthlytotalinvestmentsamountrepaid[$i] = floatval($loan['limp_month_' . $index]);
				}	
	
			}
				
			//$this->monthlyinvestments[$counter]['interestrate'] = floatval($loan['loan_invest_interest_rate']);
			//$this->monthlyinvestments[$counter]['period'] = floatval($loan['loan_invest_pays_per_years']);
			//$this->monthlyinvestments[$counter]['terms'] = floatval($loan['loan_invest_years_to_pay']);
				
			$counter++;
		}
	
		//highlight_string(var_export($this->monthlyinvestmentsrepaid,true));
	
	
		
			
		//yearly
	
	
		$counter = 1;
	
		foreach($loans as $loan) {
				
			$this->yearlyinvestment[$counter] = array($loan['loan_invest_name']);
	
			for($i=1;$i<4;$i++) {
	
				if($i>1) {
					$tmp = $loan['financial_receive'][$i-1];
					//NOTE: there is something wrong with the spreadsheet, it wont balance if the value is picked up from lir_total_per_yr
					$this->yearlyinvestment[$counter][$i] = 0; //floatval($tmp['lir_total_per_yr']);
										
				} else {
					
					$tmpr = $this->monthlyinvestments[$counter];
					unset($tmpr['name']);								
					
					$tmpp = $this->monthlyinvestmentsrepaid[$counter];
					unset($tmpp['name']);										
					
					$this->yearlyinvestment[$counter][$i] = floatval(array_sum($tmpr) - array_sum($tmpp));
				}
				
	
	
				if(isset($this->yearlytotalinvestment[$i]))
				{
					$this->yearlytotalinvestment[$i] += $this->yearlyinvestment[$counter][$i];
				}else {
					$this->yearlytotalinvestment[$i] = $this->yearlyinvestment[$counter][$i];
				}
			}
				
			$counter++;
		}
	
		for($i=1;$i<4;$i++) {
			
			if ($i > 1) {
				$this->yearlynetinvestment[$i] = $this->yearlynetinvestment[$i-1] + $this->yearlytotalinvestment[$i];
			} else {
				$this->yearlynetinvestment[$i] = $this->yearlytotalinvestment[$i];
			}
			
		}
	
		//highlight_string(var_export($loans,true));
	
	
	
	}
	
	
	
	protected function renderProfitAndLoss()
	{
		//Revenue 		: $this->monthlytotalsales;
		//DirectCost 	: $this->monthlytotaldirectcosts
		//Gross Margin 	: $this->monthlygrossmargin
		//Gross Margin Percentage	: $this->monthlygrossmarginpercent
		//Salary		: $this->monthlytotalsalary
		//Employee Expenses	: $this->monthlyemployeeexpenses
		//Other Expenses	: $this->monthlyexpenses
		//Total Operating Expenses : $this->monthlytotaloperatingexpenses
		//Operating Income 	: $this->monthlyoperatingIncome
		//Interest Incured 	: $this->monthlytotalinterest
		//Depreciation		: $this->monthlydepreciation
		
		//highlight_string(var_export($this->monthlyoperatingincome,true));
		
		$expenditure    = new expenditure_lib();
		// TODO: Need to confirm this default to 20% if percent is zero
		//$incomeTaxRate  =  $expenditure->incomeTaxRate > 0 ? $expenditure->incomeTaxRate : 20;
		$incomeTaxRate  =  $expenditure->incomeTaxRate;
		
		
		
		for($i=1; $i < 13; $i++) {
			$this->monthlyoperatingincome[$i] = $this->monthlygrossmargin[$i] - $this->monthlytotaloperatingexpenses[$i] ;
			
			$this->monthlypretaxprofit[$i] = $this->monthlyoperatingincome[$i] 
			- $this->monthlytotalinterest[$i] - $this->monthlydepreciation[$i];
			
			$this->monthlyincometax[$i] =  $this->monthlypretaxprofit[$i] * $incomeTaxRate/100;
			
			$this->monthlytotalexpenses[$i] = $this->monthlytotaldirectcosts[$i]
			+ $this->monthlytotaloperatingexpenses[$i]
			+ $this->monthlytotalinterest[$i]
			+ $this->monthlydepreciation[$i]
			+ $this->monthlyincometax[$i];	
			
			$this->monthlynetprofit[$i] = $this->monthlytotalsales[$i] - $this->monthlytotalexpenses[$i];
			
		}
			
		//YEARLY
		//Revenue
		//Salary			: $this->yearlytotalsalary
		//Employee Expenses	: $this->yearlyemployeeexpenses
		//Other Expenses	: $this->yearlyexpenses
		//Total Operating Expenses	: $this->yearlytotaloperatingexpenses
		//Interest Incurred	:	$this->yearlyinterest
		
		$this->yearlyrevenue[1]			= array_sum($this->monthlytotalsales);
		$this->yearlyrevenue[2]			= $this->yearlytotalsales[2];
		$this->yearlyrevenue[3]			= $this->yearlytotalsales[3];
		
		//Direct Costs		
		$this->yearlydirectcost[1]			= array_sum($this->monthlytotaldirectcosts);
		$this->yearlydirectcost[2]			= $this->yearlytotaldirectcosts[2];
		$this->yearlydirectcost[3]			= $this->yearlytotaldirectcosts[3];
		
		for($i = 1; $i < 4; $i++)
		{
			$this->yearlypfgrossmargin[$i]		= $this->yearlyrevenue[$i] - $this->yearlydirectcost[$i];
			$this->yearlypfgrossmarginpercent[$i]	= $this->yearlygrossmargin[$i]/$this->yearlyrevenue[$i];
			
			$this->yearlyoperatingincome[$i]	= $this->yearlypfgrossmargin[$i] - $this->yearlytotaloperatingexpenses[$i];
		}
		
		$this->yearlydepreciation[1] = array_sum($this->monthlydepreciation);
		
		$depreciationrate 	= 0.2;
		$taxrate 			= 0.2;
		
		for($i=2; $i < 4; $i++) {
			$sum = 0;			
			for($j=1;$j<=$i; $j++){
				$sum+= $this->yearlytotalmajorpurchases[$j];
			}
			
			$diff = 0;
			for($j=1;$j<$i; $j++){
				$diff+= $this->yearlydepreciation[$j];
			}
			
			$this->yearlydepreciation[$i] = ($sum - $diff) * $depreciationrate;
			
		}
				
		for($i = 1; $i < 4; $i++)
		{
			if ($i > 1) {
				$this->yearlypretaxprofit[$i] = $this->yearlyoperatingincome[$i]
				+ $this->yearlyinterest[$i] + $this->yearlydepreciation[$i];
			} else {
				$this->yearlypretaxprofit[$i] = $this->yearlyoperatingincome[$i]
				- $this->yearlyinterest[$i] - $this->yearlydepreciation[$i];
			}
			
			
			$this->yearlyincometax[$i] 		= $this->yearlypretaxprofit[$i] * $taxrate;
			
			$this->yearlytotalexpenses[$i]	= $this->yearlydirectcost[$i]
			+ $this->yearlytotaloperatingexpenses[$i] + $this->yearlyinterest[$i]
			+ $this->yearlydepreciation[$i] + $this->yearlyincometax[$i];
			
			$this->yearlynetprofit[$i]	= $this->yearlyrevenue[$i] - $this->yearlytotalexpenses[$i];
			$this->yearlynetprofitpercent[$i]	= $this->yearlynetprofit[$i]/$this->yearlyrevenue[$i]*100;
		}
		
		
		
	}
	
	protected function renderAccountsReceivable(){
		//Total Sales	: $this->monthlytotalsales
		$cashsettinglib = new cashFlowProjection_lib();
		
		if(isset($_SESSION['bpId']))
		{
			$businessPlanId = $_SESSION['bpId'];
			$cashsetting 	= $cashsettinglib->Payments($businessPlanId);
			if (count($cashsetting)) {
				$cashsetting = $cashsetting[0];
			}
		}
		
		
		
		//echo highlight_string(var_export($cashsetting, TRUE));
		
		
		$percentoncredit 	= $cashsetting['percentage_sale']/100;
		$daystocollect		= $cashsetting['days_collect_payments'];
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		$collectpercent = array();
		$collectdays	= array();
		
		for($i = 0; $i < 20; $i++) {
			$collectpercent[] 	= ($i * 5)/100;
			$collectdays[]		= ($i * 30);
		}
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsReceivable 		= array();
		$MonthlyAccountsReceivable[-1] 	= 0;
		$TotalAccountsReceivable		= array();
		$TotalAccountsReceivable[-1]	= 0;
		
		//echo highlight_string(var_export($monthlytotalsales, TRUE));
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $this->monthlytotalsales[$j+1];
			} else {
				$tmprow[$j] = $this->monthlytotalsales[$j+1]*(1-$percentoncredit);
			}
		
		}
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
		
		
		
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				if(isset($totalcashcollected[$i-1])){
					$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
				} else {
					$totalcashcollected[$i-1] = $cashcollected[$i-1][$j];
				}
		
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			//$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
		
			$MonthlyAccountsReceivable[$i-1] 	= $this->monthlytotalsales[$i] - $cashcollectedcurrent[$i-1];
			$TotalAccountsReceivable[$i-1]		= $TotalAccountsReceivable[$i-2] + $this->monthlytotalsales[$i] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsReceivable[$i-1] : 0 );
				}
			}
		
				
				
		}
		
		$cashcollectedreceivable = array();
		
		for($i = 0; $i < 13; $i++) {
			for($j = 0; $j < 12; $j++) {
				if(isset($cashcollectedreceivable[$j+1])) {
					$cashcollectedreceivable[$j+1] += $cashcollected[$i][$j];
				} else {
					$cashcollectedreceivable[$j+1] = $cashcollected[$i][$j];
				}
					
			}
		}
		
			
		
		$TotalAccountsReceivable = array_slice($TotalAccountsReceivable,1);
		
		//$cashcollectedreceivable 		= $cashcollected;
		$totalcashcollectedreceivable 	= $cashcollectedreceivable;
		
		
		$this->monthlyreceivabletotalcashcollected = $totalcashcollectedreceivable;
		
		$tmp = array();
		for($i = 1; $i < 13; $i++) {
			$tmp[$i] = $TotalAccountsReceivable[$i-1];
		}
		
		for($i = 1; $i < 13; $i++) {
			if($i > 1) {
				$tmp[$i] = $this->monthlytotalsales[$i] + $tmp[$i-1] - $totalcashcollectedreceivable[$i];
			} else {
				$tmp[$i] = $this->monthlytotalsales[$i] - $totalcashcollectedreceivable[$i];
			}
		}
		
		
		$TotalAccountsReceivable = $tmp;
		
		$MonthlyAccountsReceivable = array_slice($MonthlyAccountsReceivable,1);
		
		$this->truemonthlyaccountsreceivable = $MonthlyAccountsReceivable;
		
		
		$this->monthlyaccountsreceivable = $tmp;
		
				
		
		/*
		 echo highlight_string(var_export($this->monthlytotalsales, TRUE));
		echo highlight_string(var_export($TotalAccountsReceivable, TRUE));
		echo "totalcashcollected receivable:<br>";
		echo highlight_string(var_export($totalcashcollectedreceivable, TRUE));
		echo highlight_string(var_export($tmp1, TRUE));
		*/
		//Calculate monthly payable
		$percentoncredit 	= $cashsetting['percentage_purchase']/100;
		$daystocollect		= $cashsetting['days_make_payments'];
		
		$tmptotalDirectCost	= $this->monthlytotaldirectcosts;
		
		$monthlytotalsalary			= $this->monthlytotalsalary;
		$monthlytotalrelatedexpenses= $this->monthlyemployeeexpenses;
		$monthlytotalexpenses		= $this->monthlytotaloperatingexpenses;
		
		$totalExpenses = array();
		
		for($j = 1; $j < 13; $j++ ){
			$totalExpenses[] = $tmptotalDirectCost[$j] + $monthlytotalexpenses[$j] - $monthlytotalsalary[$j] - $monthlytotalrelatedexpenses[$j];
		}
		
		
		
		
		
		$cashcollected 		= array();
		$cashcollected[0] 	= array();
		
		$tmprow 						= $cashcollected[0];
		$MonthlyAccountsPayable 		= array();
		$MonthlyAccountsPayable[-1] 	= 0;
		$TotalAccountsPayable			= array();
		$TotalAccountsPayable[-1]		= 0;
		
		
		
		for($j = 0; $j<12 ; $j++) {
			if ($percentoncredit < 0 || $percentoncredit == 0 ) {
				$tmprow[$j] = $totalExpenses[$j];
			} else {
				$tmprow[$j] = $totalExpenses[$j] * (1-$percentoncredit);
			}
		
		}
		
		
		
		
		
		$cashcollected[0]		= $tmprow;
		$cashcollectedcurrent 	= $tmprow;
		
				
		$totalcashcollected = array();
		
		for($i = 1; $i < 13; $i++) {
		
			$cashcollected[$i] 	= array();
		
			for ($j = 0; $j < $i; $j++ ) {
				$cashcollected[$i][$j] = 0;
				if(isset($totalcashcollected[$i-1])){
					$totalcashcollected[$i-1] += $cashcollected[$i-1][$j];
				} else {
					$totalcashcollected[$i-1] = $cashcollected[$i-1][$j];
				}
		
			}
		
			if ($i > 1 ) {
				$totalcashcollected[$i-1] += $cashcollected[0][$i-1];
			}
		
			$MonthlyAccountsPayable[$i-1] 	= $totalExpenses[$i-1] - $cashcollectedcurrent[$i-1];
			$TotalAccountsPayable[$i-1]		= $TotalAccountsPayable[$i-2] + $totalExpenses[$i-1] - $totalcashcollected[$i-1];
		
		
			for($j = 0; $j < 12; $j++ ){
				if($j<$i) {
					$cashcollected[$i][$j] = 0;
				} else {
					$cashcollected[$i][$j] = ( $daystocollect == $collectdays[$j-$i+1] ? $MonthlyAccountsPayable[$i-1] : 0 );
				}
			}
		
			
				
		}
		
		
		$cashcollectedpayable = array();
		
		for($i = 0; $i < 13; $i++) {
			for($j = 0; $j < 12; $j++) {
				if(isset($cashcollectedpayable[$j+1])) {
					$cashcollectedpayable[$j+1] += $cashcollected[$i][$j];
				} else {
					$cashcollectedpayable[$j+1] = $cashcollected[$i][$j];
				}
					
			}
		}
		
		
		$totalcashcollectedpayable = $cashcollectedpayable;
		
		
		$MonthlyAccountsPayable = array_slice($MonthlyAccountsPayable,1);
		$TotalAccountsPayable = array_slice($TotalAccountsPayable,1);
		
		
		$tmp = array();
		for($i = 1; $i < 13; $i++) {
			$tmp[$i] = $MonthlyAccountsPayable[$i-1];
		}
		
		$MonthlyAccountsPayable = $tmp;
		
		
		//$this->monthlyaccountspayable = $totalcashcollectedpayable;
		
		$tmp = array();
		for($i = 1; $i < 13; $i++) {
			$tmp[$i] = $TotalAccountsPayable[$i-1];
		}
		
		for($i = 1; $i < 13; $i++) {
			if($i > 1) {
				$tmp[$i] = $totalExpenses[$i-1] + $tmp[$i-1] - $totalcashcollectedpayable[$i];
			} else {
				$tmp[$i] = $totalExpenses[$i-1] - $totalcashcollectedpayable[$i];
			}
		}
		
		
		
		
		
		$this->monthlypayabletotalcashcollected = $totalcashcollectedpayable;
		
		$this->monthlyaccountspayable = $tmp;
		
		//highlight_string(var_export($this->monthlyaccountspayable, TRUE));
		
		
		
		//calculate monthly cash
		$monthlycash = array();
		
		$incometax = $this->monthlyincometax;
		$monthlypurchase = $this->monthlytotalmajorpurchases;
		$monthlyreceive = $this->monthlytotalamountreceive;
		$monthlypayment	= $this->monthlytotalamountrepaid;
		$monthylyinterestincurred = $this->monthlytotalinterest;
		
		$monthlytotalinterest	= $this->monthlytotalinterest;
		$monthlydepreciation	= $this->monthlydepreciation;
		
		$monthlyiamountreceive = $this->monthlytotalinvestmentsamountreceive;
		$monthlyiamountrepaid  =  $this->monthlytotalinvestmentsamountrepaid;
		
		$monthlycash[0] = 0;
		
		for($i = 0; $i < 12; $i++) {
		
			if ($i>0) {
				$monthlycash[$i] = $monthlycash[$i-1];
			}
			/*
			 $monthlycash[$i] = $monthlycash[$i] + $monthlyreceive[$i+1] - $monthlypayment[$i+1] + $totalcashcollectedreceivable[$i];
			- $totalcashcollectedpayable[$i] - $monthlytotalsalary[$i+1] - $monthlytotalrelatedexpenses[$i+1];
			- $monthylyinterestincurred[$i+1] - $monthlypurchase[$i+1] - $incometax[$i+1];
			*/
		
			$monthlycash[$i] = $monthlycash[$i] + $monthlyreceive[$i+1] - $monthlypayment[$i+1]
			+ $monthlytotalinterest[$i+1] + $totalcashcollectedreceivable[$i+1]
			- $totalcashcollectedpayable[$i+1] - $monthlytotalsalary[$i+1] - $monthlytotalrelatedexpenses[$i+1]
			- $monthylyinterestincurred[$i+1] - $monthlydepreciation[$i+1] - $incometax[$i+1]
			- $monthlypurchase[$i+1] + $monthlydepreciation[$i+1]
			+ $monthlyiamountreceive[$i+1] - $monthlyiamountrepaid[$i+1];
		
		}
		
		$tmp = array();
		for($i = 1; $i < 13; $i++) {
			$tmp[$i] = $monthlycash[$i-1];
		}
		
		$this->monthlycash = $tmp;
		
		//highlight_string(var_export($monthlycash, TRUE));
		
		/*
		 echo '<br>monthlyreceive: ';
		echo highlight_string(var_export($monthlyreceive, TRUE));
		echo '<br>$monthlypayment: ';
		echo highlight_string(var_export($monthlypayment, TRUE));
		echo '<br>$totalcashcollectedreceivable: ';
		echo highlight_string(var_export($totalcashcollectedreceivable, TRUE));
		echo '<br>$totalcashcollectedpayable: ';
		echo highlight_string(var_export($totalcashcollectedpayable, TRUE));
		echo '<br>$monthlytotalsalary: ';
		echo highlight_string(var_export($monthlytotalsalary, TRUE));
		echo '<br>$monthlytotalrelatedexpenses: ';
		echo highlight_string(var_export($monthlytotalrelatedexpenses, TRUE));
		echo '<br>$monthylyinterestincurred: ';
		echo highlight_string(var_export($monthylyinterestincurred, TRUE));
		echo '<br>$monthlypurchase: ';
		echo highlight_string(var_export($monthlypurchase, TRUE));
		echo '<br>$incometax: ';
		echo highlight_string(var_export($incometax, TRUE));
		
		*/
		
		
		
		
	}
	
	
	protected function renderBalanceSheet() {
		//Cash 					: $this->monthlycash
		//Accounts Receivable	: $this->monthlyaccountsreceivable
		
		//Accumulated Depreciation 	: $this->monthlyaccudepreciation
		//Monthly Accounts payable	: $this->monthlyaccountspayable	
		//Long-Term Debt			: $this->monthlytotalbalance
		//Earnings				: $this->monthlynetprofit
		
		$totalcurrentassets 	= array();
		$totalcurrentliability	= array();
		
		
		$balMonthlyRetainedEarnings 	= array();
		
		for ($i = 1; $i < 13; $i++ ) {
			
						
			if(isset($this->monthlynetprofit[$i-1])) {
				$balMonthlyRetainedEarnings[$i] = $balMonthlyRetainedEarnings[$i-1] + $this->monthlynetprofit[$i-1];
			} else {
				$balMonthlyRetainedEarnings[$i] = 0;
			}

			if ($i > 1) {
				$this->monthlypaidincapital[$i] = $this->monthlypaidincapital[$i-1] 
				+ $this->monthlytotalinvestmentsamountreceive[$i] - $this->monthlytotalinvestmentsamountrepaid[$i];
			} else {
				$this->monthlypaidincapital[$i] = $this->monthlytotalinvestmentsamountreceive[$i] - $this->monthlytotalinvestmentsamountrepaid[$i];
			}
			
		}
		
		$this->monthlyretainedearnings = $balMonthlyRetainedEarnings;
		
		$acculongtermsassets = array();
		
		for($i = 1; $i < 13; $i++) {
			
			if($i!=1) {
				$acculongtermsassets[$i] = $acculongtermsassets[$i-1] +  $this->monthlytotalmajorpurchases[$i];
			} else {
				$acculongtermsassets[$i] = $this->monthlytotalmajorpurchases[$i];
			}
				
			
			$totalcurrentassets[$i] = $this->monthlycash[$i]+$this->monthlyaccountsreceivable[$i];
			
			$this->monthlytotallongtermassets[$i] = $acculongtermsassets[$i] + $this->monthlyaccudepreciation[$i];
			
			$this->monthlytotalassets[$i] = $totalcurrentassets[$i] + $this->monthlytotallongtermassets[$i];
			
			$this->monthlytotalliability[$i] = $this->monthlyaccountspayable[$i] + $this->monthlytotalbalance[$i];
			
			$this->monthlyownerequity[$i] = $this->monthlypaidincapital[$i] + $balMonthlyRetainedEarnings[$i] + $this->monthlynetprofit[$i];
			
			$this->monthlyliabilityandequity[$i] = $this->monthlytotalliability[$i] + $this->monthlyownerequity[$i];

			
			
		}
		
		$this->monthlylongtermassets	=  $acculongtermsassets;
		$this->monthlytotalcurrentassets = $totalcurrentassets;
		

		//YEARLY
		
		$this->yearlycash[1]				= $this->monthlycash[12];
		$this->yearlyaccountsreceivable[1] 	= $this->monthlyaccountsreceivable[12];
		$this->yearlyaccountsreceivable[2]	= $this->yearlyaccountsreceivable[1]/$this->yearlytotalsales[1]*$this->yearlytotalsales[2];
		$this->yearlyaccountsreceivable[3]	= $this->yearlyaccountsreceivable[1]/$this->yearlytotalsales[1]*$this->yearlytotalsales[3];
		
		$this->yearlyaccountspayable[1] 	= $this->monthlyaccountspayable[12];
		$this->yearlyaccountspayable[2]		= $this->yearlyaccountspayable[1]/$this->yearlytotaldirectcosts[1]*$this->yearlytotaldirectcosts[2];
		$this->yearlyaccountspayable[3]		= $this->yearlyaccountspayable[1]/$this->yearlytotaldirectcosts[1]*$this->yearlytotaldirectcosts[3];
		
		
		$this->yearlycash[2]				= $this->yearlycash[1] + $this->yearlyaccountsreceivable[1] + $this->yearlyrevenue[2]
		- $this->yearlyaccountsreceivable[2] + $this->yearlyamountreceive[2] - $this->yearlyrepayments[2]
		- $this->yearlytotalmajorpurchases[2] - $this->yearlyaccountspayable[1] - $this->yearlytotalexpenses[2] 
		+ $this->yearlyaccountspayable[2] + $this->yearlydepreciation[2];
		
		$this->yearlycash[3]				= $this->yearlycash[2] + $this->yearlyaccountsreceivable[2] + $this->yearlyrevenue[3]
		- $this->yearlyaccountsreceivable[3] + $this->yearlyamountreceive[3] - $this->yearlyrepayments[3]
		- $this->yearlytotalmajorpurchases[3] - $this->yearlyaccountspayable[2] - $this->yearlytotalexpenses[3]
		+ $this->yearlyaccountspayable[3] + $this->yearlydepreciation[3];
		
		for($i=1; $i < 4; $i ++) {
			$this->yearlytotalcurrentassets[$i] = $this->yearlycash[$i] + $this->yearlyaccountsreceivable[$i];
		}
		
		$this->yearlylongtermassets[1] = $this->monthlylongtermassets[12];
		$this->yearlylongtermassets[2] = $this->yearlylongtermassets[1] + $this->yearlytotalmajorpurchases[2];
		$this->yearlylongtermassets[3] = $this->yearlylongtermassets[2] + $this->yearlytotalmajorpurchases[3];
		
		$this->yearlyaccudepreciation[1]		= $this->monthlyaccudepreciation[12];
		$this->yearlyaccudepreciation[2]		= $this->yearlyaccudepreciation[1] -  $this->yearlydepreciation[2];
		$this->yearlyaccudepreciation[3]		= -$this->yearlydepreciation[1] - $this->yearlydepreciation[2] - $this->yearlydepreciation[3];
		
		for($i=1; $i < 4; $i++) {
			$this->yearlytotallongtermassets[$i] 	= $this->yearlylongtermassets[$i] + $this->yearlyaccudepreciation[$i];			
			$this->yearlytotalassets[$i]			= $this->yearlytotalcurrentassets[$i] + $this->yearlytotallongtermassets[$i];
		}
		
		
		/*
		public $yearlycash					= array();
		public $yearlyaccountsreceivable	= array();
		public $yearlytotalcurrentassets	= array();
		public $yearlylongtermassets		= array();
		public $yearlyaccudepreciation		= array()
		public $yearlytotallongtermassets	= array();
		public $yearlytotalassets			= array();
		*/
		
		$this->yearlytotalcurrentliabilities	= $this->yearlyaccountspayable; //sales tax payable is zero, short term debt is zero 
		
		//NOTE: monthlylongterdebt is $this->monthlytotalbalance
		
		$this->yearlylongtermdebt[1]			= $this->monthlytotalbalance[12];
		$this->yearlylongtermdebt[2]			= $this->yearlylongtermdebt[1] + $this->yearlytotalloans[2] - $this->yearlyrepayments[2];
		$this->yearlylongtermdebt[3]			= $this->yearlylongtermdebt[2] + $this->yearlytotalloans[3] - $this->yearlyrepayments[3];
		
		for($i=1; $i < 4; $i++) {
			$this->yearlytotalliabilities[$i]	= $this->yearlytotalcurrentliabilities[$i] + $this->yearlylongtermdebt[$i];			
		}
		
		//monthlyearnings				: $this->monthlynetprofit
		
		$this->yearlyearnings[1]			= $this->monthlynetprofit[12] + $this->monthlyretainedearnings[12];
		$this->yearlyearnings[2]			= $this->yearlynetprofit[2];
		$this->yearlyearnings[3]			= $this->yearlynetprofit[3];
		
		$this->yearlyretainedearnings[1]	= 0;
		$this->yearlyretainedearnings[2]	= $this->yearlyretainedearnings[1] + $this->yearlyearnings[1];
		$this->yearlyretainedearnings[3]	= $this->yearlyretainedearnings[2] + $this->yearlyearnings[2];
		
		for($i=1; $i < 4; $i++) {
			$this->yearlytotalownerequity[$i]	= $this->yearlynetinvestment[$i] + $this->yearlyretainedearnings[$i] + $this->yearlyearnings[$i];
			$this->yearlytotalliabilityandEquity[$i] = $this->yearlytotalliabilities[$i] + $this->yearlytotalownerequity[$i];
		}
		 
		
		/*
		public $yearlyaccountspayable		= array();
		public $yearlytotalcurrentliabilities	= array();
		public $yearlylongtermdebt			= array();
		public $yearlytotalliabilities		= array();
		public $yearlyretainedearnings		= array();
		public $yearlyearnings				= array();
		public $yearlytotalownerequity		= array();
		public $yearlytotalliabilityandEquity	= array();
		*/
		
	}
	
	
	public function displayBalanceSheet()
	{
		$this->displayTotal($this->monthlycash, "Cash");
		$this->displayTotal($this->monthlyaccountsreceivable, "Accounts Receivable");
		$this->displayTotal($this->monthlytotalcurrentassets, "Total Current Assets");
		$this->displayTotal($this->monthlylongtermassets, "Long-Term Assets");
		$this->displayTotal($this->monthlyaccudepreciation, "Accumulated Depreciation");
		$this->displayTotal($this->monthlytotalmajorpurchases, "Long-term Assets");
		$this->displayTotal($this->monthlytotalassets, "Total Assets");
		$this->displayTotal($this->monthlyaccountspayable, "Account Payable");
		$this->displayTotal($this->monthlyaccountspayable, "Total Current Liabilities");
		$this->displayTotal($this->monthlytotalbalance, "Total Long Term Debt");
		$this->displayTotal($this->monthlytotalliability, "Total Liabilities");
		$this->displayTotal($this->monthlyretainedearnings, "Retained Earnings");
		$this->displayTotal($this->monthlynetprofit, "Earnings");
		
		$this->displayTotal($this->monthlyownerequity, "Total Owner Equity");
		$this->displayTotal($this->monthlyliabilityandequity, "Total Liabilities & Equity");
		
		echo "<br>Yearly Balance Sheet<br>";
		$this->displayTotal($this->yearlycash, "Cash");
		$this->displayTotal($this->yearlyaccountsreceivable, "Accounts Receivable");		
		$this->displayTotal($this->yearlyaccountspayable, "Accounts Payable");
		$this->displayTotal($this->yearlytotalcurrentassets, "Total Current Assets");		
		$this->displayTotal($this->yearlylongtermassets, "Long Term Assets");
		$this->displayTotal($this->yearlyaccudepreciation, "Accumulated Depreciation");
		$this->displayTotal($this->yearlytotallongtermassets, "Total Long-term Assets");
		$this->displayTotal($this->yearlytotalassets, "Total Assets");
		$this->displayTotal($this->yearlyaccountspayable, "Accounts Payable");
		$this->displayTotal($this->yearlytotalcurrentliabilities, "Total Current Liabilities");
		$this->displayTotal($this->yearlylongtermdebt, "Long-Term Debt");
		$this->displayTotal($this->yearlytotalliabilities, "Total Liabilities");
		$this->displayTotal($this->yearlyretainedearnings, "Retained earnings");
		$this->displayTotal($this->yearlyearnings, "Earnings");
		$this->displayTotal($this->yearlytotalownerequity, "Total Owner Equity");
		$this->displayTotal($this->yearlytotalliabilityandEquity, "Total Liabilities & Equity");
				
		
	}
	
	
	protected function renderCashFlow() {
		//NET PROFIT			: $this->monthlynetprofit
		//Depreciation			: $this->monthlydepreciation
		
		
		
		$this->cashatbeginningofperiod[1] = 0;
		
		for($i=1; $i < 13; $i++) {
			
			if($i>1) {
				$this->changeinaccountsreceivable[$i]	= $this->monthlyaccountsreceivable[$i-1] - $this->monthlyaccountsreceivable[$i];
				$this->changeinaccountspayable[$i]		= $this->monthlyaccountspayable[$i]- $this->monthlyaccountspayable[$i-1];
				$this->changeinlongtermdebt[$i]			= $this->monthlytotalbalance[$i]-$this->monthlytotalbalance[$i-1];
				
			} else {
				$this->changeinaccountsreceivable[$i] 	= -$this->monthlyaccountsreceivable[$i];
				$this->changeinaccountspayable[$i]		= $this->monthlyaccountspayable[$i];
				$this->changeinlongtermdebt[$i]			= $this->monthlytotalbalance[$i];
			}
			
			
			$this->netcashflowfromoperations[$i] = $this->monthlynetprofit[$i]
			+ $this->monthlydepreciation[$i]
			+ $this->changeinaccountsreceivable[$i]
			+ $this->changeinaccountspayable[$i];

			//Assets purchase or sold		: -$this->monthlytotalmajorpurchases;
			
			$this->assetspurchasedorsold[$i]		= -$this->monthlytotalmajorpurchases[$i];
			
			$this->netcashflowfrominvesting[$i] = $this->assetspurchasedorsold[$i]
			+ $this->changeinlongtermdebt[$i];

			
			$this->netchangeincash[$i]			= $this->netcashflowfromoperations[$i]
			+ $this->netcashflowfrominvesting[$i];
			
			if ($i > 1) {
				$this->cashatbeginningofperiod[$i] = $this->cashatendofperiod[$i-1];
			}
			
			$this->cashatendofperiod[$i]		= $this->cashatbeginningofperiod[$i]
			+ $this->netchangeincash[$i];
			
		}
		 
		//YEARLY
		//NETPROFIT				:	$this->yearlynetprofit;
		//Depreciation			:	$this->yearlydepreciation
		
		$this->yearlycashatbeginningofperiod[1] = 0;
		
		for($i=1; $i < 4; $i++) {
			
			if($i>1) {
				$this->yearlychangeinaccountsreceivable[$i] = $this->yearlyaccountsreceivable[$i-1] - $this->yearlyaccountsreceivable[$i];
				$this->yearlychangeinaccountspayable[$i]	= $this->yearlyaccountspayable[$i] - $this->yearlyaccountspayable[$i-1];
				$this->yearlychangeinlongtermdebt[$i]		= $this->yearlylongtermdebt[$i]-$this->yearlylongtermdebt[$i-1];
			} else {
				$this->yearlychangeinaccountsreceivable[$i] = -$this->yearlyaccountsreceivable[$i];
				$this->yearlychangeinaccountspayable[$i]	= $this->yearlyaccountspayable[$i];
				$this->yearlychangeinlongtermdebt[$i]		= $this->yearlylongtermdebt[$i];
			}			
			
			$this->yearlynetcashflowfromoperations[$i] = $this->yearlynetprofit[$i]
			+ $this->yearlydepreciation[$i]
			+ $this->yearlychangeinaccountsreceivable[$i]
			+ $this->yearlychangeinaccountspayable[$i];
			
			//Assets purchase or sold		: -$this->monthlytotalmajorpurchases;
				
			$this->yearlyassetspurchasedorsold[$i]		= -$this->yearlytotalmajorpurchases[$i];
			
			$this->yearlynetcashflowfrominvesting[$i] = $this->yearlyassetspurchasedorsold[$i]
			+ $this->yearlychangeinlongtermdebt[$i];
			
			$this->yearlynetchangeincash[$i]			= $this->yearlynetcashflowfromoperations[$i]
			+ $this->yearlynetcashflowfrominvesting[$i];
			
			if ($i > 1) {
				$this->yearlycashatbeginningofperiod[$i] = $this->yearlycashatendofperiod[$i-1];
			}
				
			$this->yearlycashatendofperiod[$i]		= $this->yearlycashatbeginningofperiod[$i]
			+ $this->yearlynetchangeincash[$i];
			
			
		}
	
		
		
		/*
		public $yearlychangeinaccountsreceivable		= array();
		public $yearlychangeinaccountspayable			= array();
		
		public $yearlyassetspurchasedorsold			= array();
		public $yearlychangeinlongtermdebt			= array();
		
		public $yearlynetcashflowfromoperations		= array();
		public $yearlynetcashflowfrominvesting		= array();
		public $yearlycashatbeginningofperiod		= array();
		public $yearlynetchangeincash				= array();
		public $yearlycashatendofperiod				= array();
		*/
		
		
	}
	
	public function displayCashFlow()
	{
		echo '<br>Monthly Cash Flow<br>';
		$this->displayTotal($this->monthlynetprofit, "Net Profit");
		$this->displayTotal($this->monthlydepreciation, "Depreciation");
		$this->displayTotal($this->changeinaccountsreceivable, "Change in Accounts receivable");
		$this->displayTotal($this->changeinaccountspayable, "Change in Accounts payable");
		$this->displayTotal($this->netcashflowfromoperations, "Net Cash Flow from Operations");
		$this->displayTotal($this->assetspurchasedorsold, "Assets purchased or sold");
		$this->displayTotal($this->changeinlongtermdebt, "Change in long term debt");
		$this->displayTotal($this->netcashflowfrominvesting, "Net Cash Flow from investing & Financing");
		$this->displayTotal($this->cashatbeginningofperiod, "Cash at Beginning of Period");		
		$this->displayTotal($this->netchangeincash, "Net Change in Cash");
		$this->displayTotal($this->cashatendofperiod, "Cash at End of Period");
		
		echo '<br>Yearly Cash Flow<br>';
		$this->displayTotal($this->yearlynetprofit, "Net Profit");
		$this->displayTotal($this->yearlydepreciation, "Depreciation");
		$this->displayTotal($this->yearlychangeinaccountsreceivable, "Change in Accounts receivable");
		$this->displayTotal($this->yearlychangeinaccountspayable, "Change in Accounts payable");
		$this->displayTotal($this->yearlynetcashflowfromoperations, "Net Cash Flow from Operations");
		$this->displayTotal($this->yearlyassetspurchasedorsold, "Assets purchased or sold");
		$this->displayTotal($this->yearlychangeinlongtermdebt, "Change in long term debt");
		$this->displayTotal($this->yearlynetcashflowfrominvesting, "Net Cash Flow from investing & Financing");
		$this->displayTotal($this->yearlycashatbeginningofperiod, "Cash at Beginning of Period");		
		$this->displayTotal($this->yearlynetchangeincash, "Net Change in Cash");
		$this->displayTotal($this->yearlycashatendofperiod, "Cash at End of Period");
		
		
	}
	
	
	
	protected function displaySalesForecast()
	{
		echo "Monthly Detail <br>";
		
		echo 'Unit Sales';
						
		$this->display($this->monthlyunitsales);
			
		
		echo 'Price Per Unit';
		
		$this->display($this->monthlypriceperunit);
		
				
		echo 'Sales';
		
		$this->display($this->monthlysales);
		
				
		echo 'Total Sales';
		
		$this->displayTotal($this->monthlytotalsales);
		
		echo 'Direct Cost Per Unit';
		
		$this->display($this->monthlydirectunitcosts);
		
		echo 'Direct Cost';
		
		$this->display($this->monthlydirectcosts);
		
		
		echo 'Total Direct Cost';
		
		$this->displayTotal($this->monthlytotaldirectcosts);
		
		echo 'Gross Margin';
		
		$this->displayTotal($this->monthlygrossmargin);
		
		echo 'Gross Margin Percentage';
		
		$this->displayTotal($this->monthlygrossmarginpercent);
		
		
		echo "Yearly Detail <br>";
		
		echo 'Unit Sales';
						
		$this->display($this->yearlyunitsales);
			
		
		echo 'Price Per Unit';
		
		$this->display($this->yearlypriceperunit);
		
				
		echo 'Sales';
		
		$this->display($this->yearlysales);
		
				
		echo 'Total Sales';
		
		$this->displayTotal($this->yearlytotalsales);
		
		echo 'Direct Cost Per Unit';
		
		$this->display($this->yearlydirectunitcosts);
		
		echo 'Direct Cost';
		
		$this->display($this->yearlydirectcosts);
		
		
		echo 'Total Direct Cost';
		
		$this->displayTotal($this->yearlytotaldirectcosts);
		
		echo 'Gross Margin';
		
		$this->displayTotal($this->yearlygrossmargin);
		
		echo 'Gross Margin Percentage';
		
		$this->displayTotal($this->yearlygrossmarginpercent);
		
		
		
	}
	
	
	protected function displayPersonnelPlan()
	{
		echo "Personnel Plan Monthly Detail <br>";
	
		$this->display($this->monthlysalary);
		$this->displayTotal($this->monthlytotalsalary);
		
		
		echo "Yearly Personnel Plan";
		
		$this->display($this->yearlysalary);
		$this->displayTotal($this->yearlytotalsalary);
		
	}
	
	protected function displayBudget()
	{
		echo "Budget Monthly Detail <br>";
					
		$this->displayTotal($this->monthlytotalsalary, "Salary");		
	
		$this->displayTotal($this->monthlyemployeeexpenses, "Employee Expenses");
		
		$this->display($this->monthlyexpenses);		
				
		$this->displayTotal($this->monthlytotaloperatingexpenses, "Total Operating Expenses");
		
		$this->display($this->monthlymajorpurchases);
		
		$this->displayTotal($this->monthlytotalmajorpurchases, 'Total Major Purchases' );
		
		
		echo "Budget Yearly Detail";
		
		$this->displayTotal($this->yearlytotalsalary, "Salary");
		$this->displayTotal($this->yearlyemployeeexpenses, "Employee Expenses");
		$this->display($this->yearlyexpenses);
		
		$this->displayTotal($this->yearlytotaloperatingexpenses, "Total Operating Expenses");
		
		$this->display($this->yearlymajorpurchases);
		
		$this->displayTotal($this->yearlytotalmajorpurchases, 'Total Major Purchases' );
	
		$this->displayTotal($this->monthlydepreciation, 'Depreciation');
		$this->displayTotal($this->monthlyaccudepreciation, 'Accumulated Depreciation');
		
		
		

		
		
	}
	
	protected function displayLoans()
	{
		echo "Loans Monthly Detail <br>";
			
		$this->display($this->monthlyloans);		
		$this->displayTotal($this->monthlytotalamountreceive, "Total Amount Receive");
	
		
		echo "Estimated Repayments <br>";
		
		$this->display($this->monthlyrepayments);		
		$this->displayTotal($this->monthlytotalamountrepaid, "Total Amount Repaid");
		
		$this->display($this->monthlyestimatedbalance);
		$this->displayTotal($this->monthlytotalbalance, "Estimated Balance");
		
		$this->display($this->monthlyestimatedinterest);
		$this->displayTotal($this->monthlytotalinterest, "Total Interes Charged");
		
		
		echo "Yearly<br>";
		
		$this->display($this->yearlyloans);
		$this->displayTotal($this->yearlytotalloans, "Total Amount Receive");
		
		$this->displayTotal($this->yearlyamountrepaid, "Repayment");
		
		$this->displayTotal($this->yearlyinterest, "Interest");
		
		
	}
	
	protected function displayProfitAndLoss()
	{
		echo "Profit and Lost <br>";
			
		$this->displayTotal($this->monthlytotalsales, "Revenue");
		$this->displayTotal($this->monthlytotaldirectcosts, "Direct Costs");
		$this->displayTotal($this->monthlygrossmargin, "Gross Margin");
		$this->displayTotal($this->monthlygrossmarginpercent, "Gross Margin Percentage");
		echo "Operating Expenses <br>";
		$this->displayTotal($this->monthlytotalsalary, 'Salary');
		$this->displayTotal($this->monthlyemployeeexpenses, "Employee Expenses");		
		$this->display($this->monthlyexpenses);		
		$this->displayTotal($this->monthlytotaloperatingexpenses, "Total Operating Expenses");
		$this->displayTotal($this->monthlyoperatingincome, "Operating Income");
		$this->displayTotal($this->monthlytotalinterest, "Interest Incured");
		$this->displayTotal($this->monthlydepreciation, "Depreciation");
		$this->displayTotal($this->monthlypretaxprofit, "Pre Tax Income");
		$this->displayTotal($this->monthlyincometax, "Income Tax");
		$this->displayTotal($this->monthlytotalexpenses, "Total Expenses");
		$this->displayTotal($this->monthlynetprofit, "Net Profit");
				
		
		echo "Yearly Profit and Lost <br>";
		$this->displayTotal($this->yearlyrevenue, "Revenue");
		$this->displayTotal($this->yearlydirectcost, "Direct Cost");
		$this->displayTotal($this->yearlygrossmargin, "Gross Margin");
		$this->displayTotal($this->yearlygrossmarginpercent, "Gross Margin Percent");
		$this->displayTotal($this->yearlytotalsalary, "Salary");
		$this->displayTotal($this->yearlyemployeeexpenses, "Employee Expenses");
		$this->display($this->yearlyexpenses);
		$this->displayTotal($this->yearlytotaloperatingexpenses, "Total Operating Expenses");
		$this->displayTotal($this->yearlyoperatingincome, "Operating Income");
		$this->displayTotal($this->yearlyinterest, "Interest Incurred");
		$this->displayTotal($this->yearlydepreciation, "Depreciation");
		$this->displayTotal($this->yearlypretaxprofit, "Pre Tax Profit");
		$this->displayTotal($this->yearlyincometax, "Income Tax");
		$this->displayTotal($this->yearlytotalexpenses, "Total Expenses");
		$this->displayTotal($this->yearlynetprofit, "Net Profit");
		$this->displayTotal($this->yearlynetprofitpercent, "Net Profit/Sales");
		
		//Salary			: $this->yearlytotalsalary
		//Employee Expenses	: $this->yearlyemployeeexpenses
		//Other Expenses	: $this->yearlyexpenses
		//Total Operating Expenses	: $this->yearlytotaloperatingexpenses
		
		
		
	}
	
	
	protected function display($datalist) {
				
		$html = "<table class='hor-minimalist-a'>";
		
		foreach($datalist as $item)
		{
			$html .= '<tr>';
			$i = 0;
			foreach($item as $data) {
				
				$w = ($i == 0? '' : '7.5%');				
				
				if (is_numeric($data)) {
					$data = number_format($data,2);
					$data = $data >= 0 ? $data : "(" . $data . ")"; 
				} 
				
				$html .= '<td width="' . $w . 'px;">' . $data . '</td>';
				
				$i++;
			}
			
			$html .= '</tr>';
		}
						
		$html .= "</table>";			

		echo $html;		
		
	}
	
	protected function displayTotal($datalist, $title="") {
	
		$html = "<table class='hor-minimalist-a'><tr>\r\n<td  style='background: #f3f3f3;'>" . $title . "</td>\r\n";
	
						
		foreach($datalist as $data) {
			
			if (is_numeric($data)) {
				$data = number_format($data,2);
				$data = $data >= 0 ? $data : "(" . abs($data) . ")";
			}
			
			$html .= '<td width="7.5%" style="background: #f3f3f3;">' . $data . '</td>' . "\r\n";
			
		}				
			
	
		$html .= "</tr></table>";
	
		echo $html;
	
	}

	private static function _interestAndPrincipal($rate=0, $per=0, $nper=0, $pv=0, $fv=0, $type=0) {
		$pmt = self::PMT($rate, $nper, $pv, $fv, $type);
		$capital = $pv;
		for ($i = 1; $i<= $per; ++$i) {
			$interest = ($type && $i == 1)? 0 : -$capital * $rate;
			$principal = $pmt - $interest;
			$capital += $principal;
		}
		return array($interest, $principal);
	}	//	function _interestAndPrincipal()
	
	public static function IPMT($rate, $per, $nper, $pv, $fv = 0, $type = 0) {
		$rate	= self::flattenSingleValue($rate);
		$per	= (int) self::flattenSingleValue($per);
		$nper	= (int) self::flattenSingleValue($nper);
		$pv		= self::flattenSingleValue($pv);
		$fv		= self::flattenSingleValue($fv);
		$type	= (int) self::flattenSingleValue($type);
	
		// Validate parameters
		if ($type != 0 && $type != 1) {
			return self::$_errorCodes['num'];
		}
		if ($per <= 0 || $per > $nper) {
			return self::$_errorCodes['value'];
		}
	
		// Calculate
		$interestAndPrincipal = self::_interestAndPrincipal($rate, $per, $nper, $pv, $fv, $type);
		return $interestAndPrincipal[0];
	}	//	function IPMT()

	public static function flattenSingleValue($value = '') 
	{
        while (is_array($value)) {
			$value = array_pop($value);
		}
			
		return $value;
	}
	
	public static function PMT($rate = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0) {
		// Validate parameters
		if ($type != 0 && $type != 1) {
			return '#NUM!';
		}
	
		// Calculate
		if (!is_null($rate) && $rate != 0) {
			return (-$fv - $pv * pow(1 + $rate, $nper)) / (1 + $rate * $type) / ((pow(1 + $rate, $nper) - 1) / $rate);
		} else {
			return (-$pv - $fv) / $nper;
		}
	}	//	function PMT()
	
	
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
	
	public function farraynumber($tarray) {
	
		$currency = $this->currency;
		
	
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
	
	public function farraypercent($tarray) {
	
		foreach($tarray as $key=>$value) {
			
			$value = str_replace(array(','), '', $value);
			
			if (is_numeric($value)) {
				if ($value < 0) {
					$tarray[$key] = "(" . $this->number(percentage * -1, 0) . ')';
				}
				else {
					$tarray[$key] = $this->percentage($value, 0);
				}
			} else {
				$tarray[$key] = $value;
			}
			
			$tarray[$key] = $this->percentage($value, 0);
		}
	
		return $tarray;
	
	}
	
	
	public function writeWebTableRow($type="row-header", $data ) {
		
		$html = ""
		. '<div class="row ' . $type . ' singleline">'
    . '<span class="cell label column-0 singleline">'
    . '    <p class="overflowable">' . $data[0] . '</p>'
    . '</span>'
    . '<span class="cell data column-1 singleline">'
	. '	<p class="overflowable">' . $data[1] . '</p>'
	. '</span>'
	. '<span class="cell data column-1 singleline">'
	. '	<p class="overflowable">' . $data[2] . '</p>'
	. '</span>'
	. '<span class="cell data column-1 singleline">'
	. '	<p class="overflowable">' . $data[3] . '</p>'
	. '</span>'
	. '<div class="x-clear"></div>'
. '</div>';
		
		
		echo $html;
		
	}
	
	public function writeWebTableRowSpacer() {
	
		$html = ""
		. '<div class="row row-spacer singleline">'
		. '<span class="cell label column-0 singleline">'
		. '    <p class="overflowable"></p>'
		. '</span>'
		. '<span class="cell data column-0 singleline">'
		. '	<p class="overflowable"></p>'
		. '</span>'
		. '<span class="cell data column-0 singleline">'
		. '	<p class="overflowable"></p>'
		. '</span>'
		. '<span class="cell data column-0 singleline">'
		. '	<p class="overflowable"></p>'
		. '</span>'
		. '<div class="x-clear"></div>'
		. '</div>';
		
		echo $html;
	
	}
	
	
	
}

//$oWebcalc = new WebCalc();
//$oWebcalc->build();
//$oWebcalc->displayData();

?>



