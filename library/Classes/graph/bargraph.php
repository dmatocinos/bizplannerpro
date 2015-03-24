<?php // content="text/plain; charset=utf-8"

require_once('jpgraph.php');
require_once ('jpgraph_line.php');
require_once('jpgraph_bar.php');

/**
 * TCPDF class extension with custom header and footer for TOC page
 */
class GraphHandler {

	public function genSingGraph($data=null) {
	
		$datay = array(2500, 18000, 6000, 4000, 8000, 4500, 70000, 0, 45000, 0, 60000, 210);
		$datax = array("Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul");
	
		// Width and height of the graph
		$width = 589; $height = 389;
		 
		// Create a graph instance
		$graph = new Graph($width,$height);
		 
		// Specify what scale we want to use,
		// int = integer scale for the X-axis
		// int = integer scale for the Y-axis
		$graph->SetScale('textint');
		 
		// Setup a title for the graph
		$graph->title->Set('');
		 
		// Setup titles and X-axis labels
		$graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 8);		
		$graph->xaxis->SetTitle('Months in Year 1', 'center');
		
		
		
		// Setup Y-axis title
		$graph->yaxis->title->Set('');
						
		
		$graph->xaxis->SetTickLabels($datax);
		
		$graph->img->SetAntiAliasing(false); 
		
		//$graph->ygrid->Show();
		
		$graph->ygrid->SetFill('false', "#ffffff", '#ffffff');
		//$graph->ygrid->SetLineStyle('dashed');
		//$graph->ygrid->SetWeight(1);
		
		$graph->ygrid->SetLineStyle('dashed');
		$graph->ygrid->SetColor('#ccc');
		
		
		
		
		
		
		//Create the bar plot
		$barplot=new BarPlot($datay);
		
		// Add the plot to the graph
		$graph->Add($barplot);
		$barplot->setColor("#FECB38"); //#FECB38
		$barplot->setFillColor("#FECB38"); //#FECB38
		
		
		$graph->xaxis->SetColor('#888');
		$graph->yaxis->SetColor('#888');		
		$graph->yaxis->HideLine(true);
		$graph->yaxis->setWeight(0);
		
		
		$graph->SetBox(false);
		$graph->SetMargin(50,50,50,50);	
		
		//$fileName = "/tmp/imagefile.jpg";
		//$graph->img->Stream($fileName);	
 
		 
		// Display the graph
		$graph->Stroke();
	
	
	}
	
	
	}
	
	$grapher = new GraphHandler();
	
	$grapher-> genSingGraph();
	
