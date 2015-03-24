<?php

class global_lib{
	public $outputMsg =  array();	
	public $color = array();
	
	public $allMessages = "";
	private $data = array();
	
	public function __set($dt, $vl) {
		$this->data[$dt] = $vl;
	}
	
	public function __get($dt) {
		return $this->data[$dt];
	}
	
	public function convertMonthNameToNumber($monthName)
	{
		return date('m',strtotime($monthName));
	}
	public function gmDateFormat($format, $date)
	{
		//$format = "d/M/Y";
		date_default_timezone_set('Europe/London');
		return gmdate($format, strtotime($date));
	}
	
	
	
	public function formatText($msg) 
	{
		return html_entity_decode($msg, ENT_QUOTES, 'ISO-8859-15');
	}
	
	
	public function DisplayAllMessages($msgs, $color)
	{
		$class = "";
		//$msgs="";
		if(!empty($msgs))
		{
			
		}
		else if(!empty($this->outputMsg))
		{
			$msgs = $this->outputMsg;
			$color = $this->color;	
		}
		else{return false;}
		
		if ($color == "red"){	$class = "'errorbg'";	}
		else if ($color == "orange"){	$class = "'warning'";}
		else if ($color == "blue")	{	$class = "'success'";}
		
		
		echo "<div class=".$class."><ul>";
		if(is_array($msgs))
		{
			foreach($msgs as $msg)
			{
				echo "<li><span class='" .$color."'> $msg</span></li>";
			}
		}
		else
		{
			echo "<li><span class='" .$color."'> $msgs</span></li>";
		}
		echo "</ul></div>";
		return true;
	}
	

	public function generateMd5Code($arg1, $arg2)
	{
		$md5code = $arg1.$arg2.date('mYs');
		$md5code = md5($md5code);
		$md5code = substr($md5code, 1, 7); 
		return 	$md5code;
	}
	public function getLondonDateTimeZone()
	{
		//$registered_date = gmdate('Y-m-d  H:i:s');
		//echo $registered_date. '<br />'; 
		//gmdate('c').'<br />';
		
		date_default_timezone_set('Europe/London');
		$londonDate = date('c'); 
		return $londonDate;
	}
	

	public function setExpiredTime($expiredTime, $table)
	{
		// set expired time in seconds
		if(empty($expiredTime) && !is_numeric($expiredTime))	{$inactive = 10;}
		else { $inactive = $expiredTime;}
		
		// check to see if $_SESSION['timeout'] is set
		if(isset($_SESSION['timeout']) ) 
		{
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive)
			{ 
				$this->destroySession();
				$_SESSION = array(); 
				
				$this->logoutTo($table);
				
			}
		}
		return $_SESSION['timeout'] = time();	
	}
	
	
	public function startNewSession($forHowLong, $table)
	{
		$session = '';
		$this->destroySession();
		$session .= session_start();
		//$session .= session_regenerate_id(true);
		$session .= $_SESSION['sessionid'] = session_id();
		
		$session .= $this->setExpiredTime($forHowLong, $table);
			
		return $session;	
	}
	
	public function destroySession()
	{
		if(session_id())
		{	session_regenerate_id();
			session_unset();
			session_destroy();	
			unset($_SESSION);
		}	
	}
	
	
	public function logoutTo($table)
	{
		if($table == PLAYER_TABLE)
		{
			header("Location:".URL_PLAYER_LOGIN); 
		}
		else if($table == COACH_TABLE)
		{
			header("Location:".URL_COACH_LOGIN); 
		}
		else if($table == ADMIN_TABLE)
		{
			header("Location:".URL_ADMIN_LOGIN); 
		}

		else
		{
			$mgs = "Table has not been selected for loggin out section";
			$color = 'red';
			return 	$this->DisplayAllMessages($mgs, $color);
		}
	}

	public function Redirect($location)
	{
		header("Location:".$location); 
	}
	public function sendEmail($to, $from, $cc, $subject, $message)
	{
		if(!empty($cc))
		{
			$cc = 'Cc: '. $cc . "\r\n";
		}
		$headers = 'From: '.$from . "\r\n" .
		$cc.			
		'Reply-To: '. $from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
			
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $to))
		{
			if(mail($to, $subject, $message, $headers))
			{return true;}
			else {return false;}
		}
		else {return false;}
	}

	// To send HTML mail, the Content-type header must be set
	public function SendHtmlEmail($to, $from, $cc, $subject, $body, $reply){
	
		$htmlHeadTitleTag = '<head><title>From your Info</title></head>';
		$htmlStyle = '<style>p{margin: 15px 10px; padding:0;font: 12px Geneva, Arial, Helvetica, sans-serif; line-height:20px; }</style>';
		$HTML_BEGIN = '<html>'.$htmlHeadTitleTag.$htmlStyle;
		$HTML_BODY ='<body><p>'.$body.'</p></body></html>';
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From:'.$from . "\r\n";
		$headers .= 'Cc:'.$cc . "\r\n";
		
		if($reply){$headers .= 'Reply-To: '. $from . "\r\n" ;}
		$headers .= 'X-Mailer: PHP/' . phpversion();
		
		$mail = mail($to, $subject, $HTML_BODY, $headers);
		if ($mail){return true;}
		else {return false;}
	}
	
	
	public function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag (f.e. </b>)
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
						unset($open_tags[$pos]);
					}
				// if tag is an opening tag (f.e. <b>)
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
	public static function log($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		
		
	}
	
	public static function formatDisplayWithBrackets($value, $prefix = "", $suffix = "") {
		if($value < 0)
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

		return ($open_bracket . $prefix . number_format(($value * $cancelNegative), 0, '.', ',') . $suffix . $closed_bracket);
	}
}// end of class
	
	
	
?>