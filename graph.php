<?php

	
    # ------- The graph values in the form of associative array
	/*
	if((session_id() == ""))
	{
	 	session_start();
	}
	$values = $_SESSION['values']; 
 	*/
	
	$values=array(
				"FY2013" => 960,
				"FY2014" => 360,
				"FY2015" => 2360,
				"FY2016" => 730,
				"FY2017" => 360
			);
								
	$img_width=646;
	$img_height=300; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	$img=imagecreate($img_width,$img_height);

 
	$bar_width=70;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$txt_color=imagecolorallocate($img,221,119,0);
	$bar_color=imagecolorallocate($img,19,124,198);
	$background_color=imagecolorallocate($img,255,255,255);
	$border_color=imagecolorallocate($img,255,255,255);
	$line_color=imagecolorallocate($img,220,220,220);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,0,0,$img_width-1,$img_height-1,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

	# ------- Max value is required to adjust the scale	-------
	$max_value=max($values);
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=4;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=0;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,10,2,$y-8,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,13,$x1+8,$y1-15,$value,$txt_color);
		imagestring($img,13,$x1+8,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	
	
	//header("Content-type:image/png");
	//imagepng($img);
	
	$resource = $img;
	//$path = BASE_PATH."/buget_graph.png";
	$path = "buget_graph.png"; // this is the location as this file.
	$pngQuality = 9;
	
	imagepng($resource, $path, $pngQuality);
	imagedestroy($img);
	
?>
    <img src="buget_graph.png" ?>
	
		
		
