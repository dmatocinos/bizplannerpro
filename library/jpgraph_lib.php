<?php // content="text/plain; charset=utf-8"

require_once(LIBRARY_PATH . '/Classes/graph/jpgraph.php');
require_once(LIBRARY_PATH . '/Classes/graph/jpgraph_line.php');
require_once(LIBRARY_PATH . '/Classes/graph/jpgraph_bar.php');

/**
 * Graph handler specific to bizplannerpro
 */
class GraphHandler {

	public $fontsize = 11;
	
	public function genSngBar($data=null, $fillcolor = null, $txtcolor = null , $showvalue=false, $unit= null, $varwidth = null, $webflag = false) {
	
		$datay = null;
		$datax = null;
		$title = 'Months in Year 1';
		
		if ($webflag) { $this->fontsize = 9;}
	
		if ($data == null) {
			$datay = array(0);
			$datax = array(" ");	
		} else {
		
			if (count($data['datay']) == 0 || count($data['datax']) == 0) {
				$datay = array(0);
				$datax = array(" ");	
				
			} else {
		
				$datay = $data['datay'];
				$datax = $data['datax'];
				
			}
			
			$title = $data['title'];;
		
		}
	
		

		// Width and height of the graph
		$width = 680; $height = 389;

		$width = ($varwidth==null?$width:$varwidth);
		
		// Create a graph instance
		$graph = new Graph($width,$height);
				
		$graph->SetScale('textlin');
		 
		// Setup a title for the graph
		$graph->title->Set('');		 
			
		$graph->xaxis->SetTitle($title, 'center');		
		
		// Setup Y-axis title
		$graph->yaxis->title->Set('');						
		
		$graph->xaxis->SetTickLabels($datax);
		$gf = new GFormatHandler();		
		$graph->yaxis->SetLabelFormatCallback(array($gf, 'fyvalue'));		
		
		//Create the bar plot
		$barplot=new BarPlot($datay);
		
		// Add the plot to the graph
		$graph->Add($barplot);



		$color = ($fillcolor==null?"#FECB38":$fillcolor);
		$txtcolor = ($txtcolor==null?"#333":$txtcolor);
		
		$barplot->setColor($color); //#FECB38
		$barplot->setFillColor($color); //#FECB38

		if ($unit!= null) {
			//$barplot->value->SetFormat($val_fmt, $val_fmt);
			$gf->unit = $unit;
			
			if ($unit != "%") {
				$barplot->value->SetFormatCallback(array($gf, 'ymoney'));			
			} else {
				$barplot->value->SetFormatCallback(array($gf, 'ypercent'));	
			}


			$barplot->value->HideZero(true);
			
		}

		$barplot->value->setColor("#385179");
		$barplot->value->SetFont(FF_ARIAL, FS_NORMAL, $this->fontsize);
		$barplot->value->show($showvalue);
		

		
		$this->setGraphStyle($graph, $txtcolor);
		$graph->xaxis->SetPos("min");
		$graph->SetMargin(80,50,30,50); 
		// Display the graph
		
		
		$imghandler = $graph->Stroke(_IMG_HANDLER);
		//return $graph->Stroke($filename);
	
		//$filename = BASE_PATH . "/images/graph/image.png";
		//$graph->img->Stream($filename);
		
		return $imghandler;
		
	
	}
	
	
	public function genAccuBar($data=null) {
		
		$data1y = null;
		$data2y = null;
		$datax = null;
		$title = 'Months in Year 1';
	
	
		if ($data == null) {	
			$data1y = array(2500, 18000, 6000, 4000, 8000, 4500, 70000, 0, 45000, 0, 60000, 210);
			$data2y = array(250, 1800, 60000, 42000, 80500, 4900, 20000, 0, 65000, 0, 2000, 5210);
			$datax = array("Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul");
		} else {
			$data1y = $data['data1y'];
			$data2y = $data['data2y'];
			$datax = $data['datax'];
			$title = $data['title'];;		
		}
	
	
	
	
		// Width and height of the graph
		$width = 630; $height = 389;
		 
		// Create a graph instance
		$graph = new Graph($width,$height);
				
		$graph->SetScale('textlin');
		 
		// Setup a title for the graph
		$graph->title->Set('');
		 
		// Setup titles and X-axis labels
		
		$graph->xaxis->SetTitle($title, 'center');		
		
		// Setup Y-axis title
		$graph->yaxis->title->Set('');						
		
		$graph->xaxis->SetTickLabels($datax);				
		
		//Create the bar plot
		$barplot1=new BarPlot($data1y);
		$barplot2=new BarPlot($data2y);
		
		
		$ab1plot = new AccBarPlot(array($barplot1,$barplot2)); 
		
		// Add the plot to the graph
		$graph->Add($ab1plot);
		$barplot1->setColor("#FECB38"); //#FECB38
		$barplot1->setFillColor("#FECB38"); //#FECB38
		$barplot1->setLegend("Other Expenses");
		
		$barplot2->setColor("#000"); //#FECB38
		$barplot2->setFillColor("#000"); //#FECB38
		$barplot2->setLegend("Direct Cost");
		
			
		$this->setLegendStyle($graph);
		$this->setGraphStyle($graph);
		$graph->SetMargin(50,140,30,50);	
		
		//$fileName = "/tmp/imagefile.jpg";
		//$graph->img->Stream($fileName);	
 
		 
		// Display the graph
		return $graph->Stroke(_IMG_HANDLER);
	
	}	
	
	protected function setGraphStyle($graph, $color=null) {

		$color = ($color==null?"#333":$color);


		$graph->xaxis->SetColor($color);
		$graph->yaxis->SetColor($color);		
		$graph->yaxis->HideLine(true);
		$graph->yaxis->setWeight(0);		
		$graph->img->SetAntiAliasing(false); 
		
		$graph->ygrid->SetFill('false', "#ffffff", '#ffffff');		
		$graph->ygrid->SetLineStyle('dashed');
		$graph->ygrid->SetColor('#ccc');		
		$graph->SetBox(false);
		$graph->SetTickDensity(TICKD_SPARSE, TICKD_NORMAL);
		
		$graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, $this->fontsize);
		$graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, $this->fontsize);
		$graph->xaxis->title->SetFont(FF_ARIAL, FS_NORMAL, $this->fontsize);	
		$graph->xaxis->title->setColor($color);

		$graph->graph_theme=null;
		
	
	}
	
	protected function setLegendStyle($graph) {
		$graph->legend->setColor('#333');
		$graph->legend->SetColumns(1);
		$graph->legend->SetPos(0.9,0.45,'center','top');
		$graph->legend->SetMarkAbsSize(10);
		$graph->legend->SetFont(FF_ARIAL, FS_NORMAL, $this->fontsize);
		$graph->legend->SetLineWeight(0);	
	}
	
	
	
	
	public function genDblBar($data=null) {
	
		
		$data1y = array(2500, 18000, 6000, -4000, 8000, -4500, 70000, 0, -45000, 0, 60000, -210);
		$data2y = array(250, 1800, 60000, 42000, 80500, 4900, 20000, 0, 65000, 0, 2000, 5210);
		$datax = array("Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul");
	
	
	
		// Width and height of the graph
		$width = 630; $height = 389;
		 
		// Create a graph instance
		$graph = new Graph($width,$height);
		if (function_exists('imageantialias')) {
			$graph->img->SetAntiAliasing(false);
		}	

		
		$graph->SetScale('textint');
		 
		// Setup a title for the graph
		$graph->title->Set('');
		 
		// Setup titles and X-axis labels
		
		$graph->xaxis->SetTitle('Months in Year 1', 'center');		
		
		
		// Setup Y-axis title
		$graph->yaxis->title->Set('');						
		
		$graph->xaxis->SetTickLabels($datax);	

		$gf = new GFormatHandler();

		
		$graph->yaxis->SetLabelFormatCallback(array($gf, 'fyvalue'));
		
		
		//Create the bar plot
		$barplot1=new BarPlot($data1y);
		$barplot2=new BarPlot($data2y);
		
		

		$ab1plot = new GroupBarPlot(array($barplot1,$barplot2)); 
		
		// Add the plot to the graph
		$graph->Add($ab1plot);
		$barplot1->setColor("#FECB38"); //#FECB38
		$barplot1->setFillColor("#FECB38"); //#FECB38
		$barplot1->setLegend("Net Cash Flow");
		
		$barplot2->setColor("#000"); //#FECB38
		$barplot2->setFillColor("#000"); //#FECB38
		$barplot2->setLegend("Cash Balance");
		
			
		$this->setLegendStyle($graph);
		$this->setGraphStyle($graph);
		$graph->xaxis->SetPos("min");
		$graph->SetMargin(50,140,30,50);	
			 
		// Display the graph
		return $graph->Stroke(_IMG_HANDLER);
	
	}
	
	
	public function parseSngData($values) {

		$datay = array();
		$datax = array();	


		for($i=0;$i< count($values); $i++){ 
			list($key,$value)=each($values); 
			array_push($datay, $value);
			array_push($datax, $key);
		}	

		return array('datax'=>$datax, 'datay'=>$datay);

	}


	public static function getImgB64($data, $valformat, $fname = null, $width= null) {

		$data 		= GraphDataHandler::parseGraphData($data);
		$data["title"]  = "";
		$grapher 	= new GraphHandler();

		$img 		= $grapher->genSngBar($data, "#224681","#224681", true, $valformat, $width, true);
	

		$filename = GRAPH_IMAGES_PATH . $fname;
		
		$filename = ($fname == null? null: $filename);
		
		ob_start();
		imagepng($img, $filename, 5);
		$imageData = ob_get_contents();
		ob_end_clean();
		
		return base64_encode($imageData);	

	}
	
	
	

	
}
	
	Class GFormatHandler {

		public $unit = "";
	
		public function fyvalue($v) {

						
			$r = abs(floatval($v)) >1000000 ? $v/1000000 : $v;
			$u = abs(floatval($v)) >1000000 ? "M" : "";
			$r = floatval($v) >-1 ? $r : "(" . -1*$r . ")";
			

			return $r . $u;
		

		}
		

		public function ymoney($v) {
			
						
			return $this->unit.number_format($v, 0, ".", ",");	

		}

		public function ypercent($v) {
						
			return $v."%";	

		}



	
	}

Class GraphDataHandler {
	

	//sales
	public $salesobj = null;
	public $sales	 = null;	
	public $totalsales = null;	
	public $grossmargin = null;


	//expenses
	public $employee 		= null;
	public $allEmpDetails 		= null;
	public $allExpDetails 		= null;
	public $personalRelatedExpenses = null;
	public $yearexpenses		= null;

	public function getSalesByYearGraphData($sales=null, $salesobj=null, $totalSales=null) {


			if ($salesobj == null && $salesobj == null && $totalSales == null) 
			{ 
				if ($this->salesobj == null )
				{
					return null; 
				} else {
					$salesobj = $this->salesobj;
					$sales	  = $this->sales;
					$totalSales = $this->totalsales;
				}
			} 

			if(!$sales) { return array('datax'=>array(), 'datay'=>array()); }

			$financialYearSales = $salesobj->startFinancialYear;
			$financialYearSales = $financialYearSales + 1;
			$yearSalesCounter = 0; 	

			$datax =  array();
			$datay =  array();
	
			foreach ($sales[0]['financial_status'] as $eachFinStat)
			{
		
				//$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
		
				// Strip the commas from the digit that gets to thousand
				$totalSales[$yearSalesCounter] = str_replace(",", "", $totalSales[$yearSalesCounter]);

				//$each_Sales_Year["FY".$financialYearSales] = $totalSales[$yearSalesCounter] ;

				array_push($datax, "FY".$financialYearSales);
				array_push($datay, $totalSales[$yearSalesCounter]);

				$yearSalesCounter = $yearSalesCounter + 1;
				$financialYearSales = $financialYearSales + 1;

			}

			return  array('datax'=>$datax, 'datay'=>$datay);

	}




	public function getSalesByMonthGraphData($sales = null, $salesobj=null) {
		

		if ($salesobj == null && $salesobj == null) 
		{ 
			if ($this->salesobj == null ) return null; 

		} 

		$salesobj = $this->salesobj;
		$sales	  = $this->sales;
		
	
		$each_month_counter = 0;
		//use to get 12 months from Jan
		$list12Months2 = $salesobj->twelveMonths(date('Y'), "Jan");



	 	foreach($sales as $monthlySalary)
		{ 
			
			for($c = 0; $c<12; $c++)
			{
				
				// Calculate Monthly Gross Margin Percentage  
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
				
				$monthlySalesPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['price']);
				
				
				
				$monthlySalesPerUnit = number_format($monthlySalesPerUnit, 0, '.', ','); 
				
				$eachSaleMonth_[$c][$each_month_counter]  = $monthlySalesPerUnit;
			}
			
			$each_month_counter = $each_month_counter+1;
		}
		
	
	
			/*--------------------------------------------------------------------------
				Get start month and loop to get every other 12 months
			 ---------------------------------------------------------------------------*/
			$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
			$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
			$list12Months = $salesobj->twelveMonths($start_years, $start_month);
			$monthCounter = 0;

			$datax =  array();
			$datay =  array();

			foreach($list12Months as $monthList)
			{
				$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
				//$sales_monthly_axis[$allExpensesMonths[$monthCounter]] = array_sum($eachSaleMonth_[$monthCounter]);
				array_push($datax, $allExpensesMonths[$monthCounter]);
				array_push($datay, array_sum($eachSaleMonth_[$monthCounter]));
				$monthCounter = $monthCounter+1;
			}

			//var_dump(array('datax'=>$datax, 'datay'=>$datay));

		return  array('datax'=>$datax, 'datay'=>$datay);

	}


	public function getSalesGrossByYearGraphData($sales = null, $salesobj = null, $grossMarginPercentage = null) {

		
		if ($salesobj == null && $salesobj == null && $grossMarginPercentage == null) 
		{ 
			if ($this->salesobj == null )
			{
				return null; 
			} else {
				$salesobj = $this->salesobj;
				$sales	  = $this->sales;
				$grossMarginPercentage = $this->grossmargin;
			}
		} 			


		$datax =  array();
		$datay =  array();

			$financialYearGM = $salesobj->startFinancialYear;
			$financialYearGM = $financialYearGM + 1;
			$monthCounterGM = 0; 		
			foreach ($sales[0]['financial_status'] as $eachFinStat)
			{
		
				// Strip the commas from the digit that gets to thousand
				$grossMarginPercentage[$monthCounterGM] = str_replace(",", "", $grossMarginPercentage[$monthCounterGM]);
		
				//$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
				//$each_Gross_Margin_Year["FY".$financialYearGM] = $grossMarginPercentage[$monthCounterGM+1] ;
				array_push($datax, "FY".$financialYearGM);
				array_push($datay, str_replace("%", "", $grossMarginPercentage[$monthCounterGM+1]));

				$monthCounterGM = $monthCounterGM + 1;
				$financialYearGM = $financialYearGM + 1;
			}
			
			//echo '<br><br>getGrossByYearGraphData<br>';
			//var_dump (array('datax'=>$datax, 'datay'=>$datay));

			return  array('datax'=>$datax, 'datay'=>$datay);

	}

	public function getSalesGrossByMonthGraphData($sales=null, $salesobj=null) {
		
		if ($salesobj == null && $salesobj == null) 
		{ 
			if ($this->salesobj == null )
			{
				return null; 
			} else {
				$salesobj = $this->salesobj;
				$sales	  = $this->sales;
				
			}
		} 


		
		$each_month_counter = 0;
		//use to get 12 months from Jan
		$list12Months2 = $salesobj->twelveMonths(date('Y'), "Jan");
	 	foreach($sales as $monthlySalary)
		{ 
			
			for($c = 0; $c<12; $c++)
			{
				
				// Calculate Monthly Gross Margin Percentage  
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
				
				$monthlySalesPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['price']);
				
				$monthlyCostPerUnit = ($monthlySalary["month_".$monthsInNumbers] * $monthlySalary['cost']);
				
				$monthlyGrossMargin = ($monthlySalesPerUnit - $monthlyCostPerUnit);
				
				if($monthlySalesPerUnit == 0)
				{
					$monthlyGrossMarginPercent = 0;	
				}
				else
				{
					$monthlyGrossMarginPercent = (($monthlyGrossMargin * 100) / $monthlySalesPerUnit);
				}
				
				$monthlyGrossMarginPercent = number_format($monthlyGrossMarginPercent, 0, '.', ','); 
				
				$eachGrossMarginMonth_[$c][$each_month_counter]  = $monthlyGrossMarginPercent;
			}
			
			$each_month_counter = $each_month_counter+1;
		}
		
	
	
		/*--------------------------------------------------------------------------
		 	Get start month and loop to get every other 12 months
		 ---------------------------------------------------------------------------*/
		$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$list12Months = $salesobj->twelveMonths($start_years, $start_month);
		$monthCounter = 0;

		$datax =  array();
		$datay =  array();

		foreach($list12Months as $monthList)
		{
			$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
			
			array_push($datax, $allExpensesMonths[$monthCounter]);
			array_push($datay, array_sum($eachGrossMarginMonth_[$monthCounter]));

			$monthCounter = $monthCounter+1;
		}

		return  array('datax'=>$datax, 'datay'=>$datay);

	}


	public function getExpensesByMonthGraphData($employee = null, $allEmpDetails= null, $allExpDetails= null, $personalRelatedExpenses= null) {

		if ($employee == null) 
		{ 
			if ($this->employee == null )
			{
				return null; 
			} else {
				$employee 		= $this->employee;
				$allEmpDetails	  	= $this->allEmpDetails;
				$allExpDetails	  	= $this->allExpDetails;
				$personalRelatedExpenses= $this->personalRelatedExpenses;
				$yearexpenses		= $this->yearexpenses;
			}
		} 



		// $eachMonth_ // An array that holds the data in order to sum them together		
		$each_month_counter = 0;
	
		//use to get 12months from Jan
		$list12Months2 = $employee->twelveMonths(date('Y'), "Jan");
	
	
		/*--------------------------------------------------------------------------
		 	Employees Salary loop
		 ---------------------------------------------------------------------------*/
		foreach($allEmpDetails as $monthlySalary)
		{
			for($c = 0; $c<12; $c++)
			{
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
			
				$eachMonth_[$c][$each_month_counter]  = $monthlySalary["month_".$monthsInNumbers];
			}
		
			$each_month_counter = $each_month_counter+1;
	
		}
	
		/*--------------------------------------------------------------------------
		 	Repeat Employees Salary loop but multiply it with $monthlyReleatedExp 
		 ---------------------------------------------------------------------------*/
		if(isset($personalRelatedExpenses))
		{
			$monthlyReleatedExp = ($personalRelatedExpenses / 100);
		}
		else
		{
			$monthlyReleatedExp = 0;
		}
	
		foreach($allEmpDetails as $monthlySalary)
		{
			for($c = 0; $c<12; $c++)
			{
				$monthsInNumbers = date("m", strtotime($list12Months2[$c]));	
			
				// This is where the multiplication take place for each salary month data
				$eachMonth_[$c][$each_month_counter]  = ($monthlySalary["month_".$monthsInNumbers] * $monthlyReleatedExp);
			}
		
			$each_month_counter = $each_month_counter+1;
		}

		/*--------------------------------------------------------------------------
		 	Expenditure loop
		 ---------------------------------------------------------------------------*/
		foreach($allExpDetails as $monthlyExpenses)
		{
			for($b = 0; $b<12; $b++)
			{
				// $monthsInNumbers i.e 01 ... 12
				$monthsInNumbers = date("m", strtotime($list12Months2[$b]));	
				//echo $b."<br/>";
				 $eachMonth_[$b][$each_month_counter]  = $monthlyExpenses["month_".$monthsInNumbers];
			}
		
			$each_month_counter = $each_month_counter+1;
		}
	
		/*--------------------------------------------------------------------------
		 	Get start month and loop to get every other 12 months
		 ---------------------------------------------------------------------------*/
		$start_month  = date("M", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$start_years  = date("Y", strtotime($_SESSION['bpFinancialStartDate'])) ;
		$list12Months = $employee->twelveMonths($start_years, $start_month);
		$monthCounter = 0;
		
		$datax =  array();
		$datay =  array();


		foreach($list12Months as $monthList)
		{
			$allExpensesMonths[$monthCounter]  = date("M", strtotime($monthList));	
			//$monthly_axis[$allExpensesMonths[$monthCounter]] = array_sum($eachMonth_[$monthCounter]);
			array_push($datax, $allExpensesMonths[$monthCounter]);
			array_push($datay, array_sum($eachMonth_[$monthCounter]));

			$monthCounter = $monthCounter+1;
		}
		//print_r($monthly_axis);

		return  array('datax'=>$datax, 'datay'=>$datay);

	}


	public function getExpensesByYearGraphData($yearexpenses=null) {


		if ($yearexpenses == null) 
		{ 
			if ($this->yearexpenses == null )
			{
				return null; 
			} else {				
				$yearexpenses		= $this->yearexpenses;
			}
		} 

		$datax =  array();
		$datay =  array();

		for($i=0;$i< count($yearexpenses); $i++){ 
			list($key,$value)=each($yearexpenses);
			array_push($datax, 'FY' . $key);
			array_push($datay, $value);	
		}

		return  array('datax'=>$datax, 'datay'=>$datay);
	}


	public static function parseGraphData($values) {

		$datay = array();
		$datax = array();	


		for($i=0;$i< count($values); $i++){ 
			list($key,$value)=each($values); 
			array_push($datay, $value);
			array_push($datax, $key);
		}	

		return array('datax'=>$datax, 'datay'=>$datay);

	}
	

}

	
	
	//$grapher = new GraphHandler();
	
	//$grapher->genNegGraph();
	//$grapher->genAccuBar();
	//$grapher->genDblBar();
	//$grapher->genSngByMonth();
	

