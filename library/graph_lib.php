<?php

class graph_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	
	function __construct(){
		
		
		
		
	}
	
	public function _graph($values, $imageName, $bar_width, $xAxisFont, $xAxisPostion, $unitPosition, $unit)
	{
		$img_width=646;
		$img_height=300; 
		$margins=20;
	
	 
		# ---- Find the size of graph by substracting the size of borders
		$graph_width=$img_width - $margins * 2;
		$graph_height=$img_height - $margins * 2; 
		$img=imagecreate($img_width,$img_height);
	
	 
		//$bar_width=70;
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
		if($max_value == 0)
		{
			$ratio = 0;
		}
		else
		{
			$ratio= $graph_height/$max_value;
		}
		
		
	
	 
		# -------- Create scale and draw horizontal lines  --------
		$horizontal_lines=7;
		$horizontal_gap=$graph_height/$horizontal_lines;
	
		for($i=0;$i<=$horizontal_lines;$i++){
			$y=$img_height - $margins - $horizontal_gap * $i ;
			imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
			if($ratio == 0)
			{
				$v = 0;
			}
			else
			{
				$v=intval($horizontal_gap * $i /$ratio);
			}
			
			imagestring($img,4,-1,$y-8,$v,$bar_color);
	
		}
	 
	 
		# ----------- Draw the bars here ------
		for($i=0;$i< $total_bars; $i++){ 
			# ------ Extract key and value pair from the current pointer position
			list($key,$value)=each($values); 
			//$value = number_format($value, 0, '.', '');
			$x1= $margins + $gap + $i * ($gap+$bar_width) ;
			$x2= $x1 + $bar_width; 
			$y1=$margins + $graph_height - intval($value * $ratio) ;
			$y2=$img_height - $margins;
			if($unitPosition == "after")
			{
				imagestring($img,3,$x1+10,$y1-15,$value.$unit,$txt_color); // Height displayed on the bars	unit is after
			}
			else
			{
				imagestring($img,3,$x1+0,$y1-15,$unit.$value,$txt_color); // Height displayed on the bars	unit is place before the value
			}
			
			imagestring($img,$xAxisFont,$x1+$xAxisPostion,$img_height-15,$key,$bar_color);		
			imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
		}
		
		
			//header("Content-type:image/png");
			//imagepng($img);
			
			$resource = $img;
			$path = BASE_PATH."/".$imageName; // in .png
			$pngQuality = 9;
			
			imagepng($resource, $path, $pngQuality);
			imagedestroy($img);
	}
	
}
	?>