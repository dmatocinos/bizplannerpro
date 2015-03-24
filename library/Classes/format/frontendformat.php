<?php

class format_FrontEndFormat{

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

	public function getPageNav($articles, $archive_month, $archive_year, $articles_per_page)
	{	
		$pageNavHtml = "";
		$html = "";
		$article_number = count($articles);
		$queryCartid = "";
		$queryDate = "";
		if(!empty($_GET['catid']))
		{
			$CATID = $_GET['catid'];
			$queryCartid = $CATID;
			$catName = $this->getCategoryName($CATID);
			$formatCatName = "/".$this->formatForUrl($catName[0]['category_name']);
			
		}
		else
		{
			$queryDate = $archive_month."-".$archive_year;
			$formatCatName="";
		}
		if($article_number > 0)
		{
			if($article_number > $articles_per_page)
			{
				if (isset($_GET["pg"]))
				{
					$page = $_GET["pg"];
					$prev = $page-1;
					$next = $page+1;
				}
				else
				{
					$page = 1;
					$next = 2;
				}
				$pages = ceil(($article_number/$articles_per_page));
				$articles_end = $articles_per_page*$page;
				$articles_start = $articles_end-$articles_per_page;
				$count=0;
				foreach ($articles as $article)
				{
					if ($count < $articles_end && $count >= $articles_start)
					{
						$html .= $this->generateArticleHtml($article);
					}
					$count++;
				}
				
				$intResult = (Integer)($count/$articles_per_page); 
				$floatResult = (float)($count/$articles_per_page);
				if((float)($floatResult > $intResult))
				{
					$lastPage = (Integer)($intResult+1);
				}
				
				
				$pageNavHtml = "<div id=\"page_nav\">";			
				if($page != 1)
				{
					$pageNavHtml .= '<span class="prev"><a href="'.BASE_URL."Archive".$formatCatName."/".$queryCartid.$queryDate."/1/".'"> &laquo; First</a></span>';
					$pageNavHtml .= '<span class="prev"><a href="'.BASE_URL."Archive".$formatCatName."/".$queryCartid.$queryDate."/".$prev."/".'">&lt; Previous</a></span>';
				}
				$i = $page-2;
				if($i < 1)
				{
					$i = 1;	
				}
				$page_to = $page+2;
				if($page_to > $pages)
				{
					$page_to = $pages;
				}
				
				for($i; $i <= $page_to; $i++)
				{
					
					if($page == $i)
					{
						$pageNavHtml .= '<span class="current_page">'.$i.'</span>';
					}
					else
					{
						$pageNavHtml .= '<span class="pg_nmbrs"><a href="'.BASE_URL."Archive".$formatCatName."/".$queryCartid.$queryDate."/".$i."/".'">'.$i.'</a></span>';
					}
				}
				if ($pages != $page)
				{
					$pageNavHtml .= '<span class="nxt"><a href="'.BASE_URL."Archive".$formatCatName."/".$queryCartid.$queryDate."/".$next."/".'">Next &gt;</a></span>';
					$pageNavHtml .= '<span class="nxt"><a href="'.BASE_URL."Archive".$formatCatName."/".$queryCartid.$queryDate."/".$lastPage."/".'">Last &raquo;</a></span>';
				}
				$pageNavHtml .= "<div class='clear'></div></div>";
			}
			else
			{
				foreach ($articles as $article)
				{
					$html .= $this->generateArticleHtml($article);
				}									
			}
			return array($html, $pageNavHtml);
		}
		else
		{
			$archive_date = getDate(mktime(0,0,0,$archive_month,1,$archive_year));
			throw new DisplayException("<div class=\"error\"><p>No bathroom equipment news articles were found</p></div>");
		}
	}

	public function convert_datetime($str) 
	{
		if(!empty($str))
		{
			list($date, $time) = explode(' ', $str);
			list($year, $month, $day) = explode('-', $date);
			list($hour, $minute, $second) = explode(':', $time);
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			return $timestamp;
		}
	}

	public function formatPhotos($articles, $photo_size, $height=false, $width=false)
	{
		$count = 0;
		if($articles > 0){
			foreach($articles as $article)
			{
				if(!empty($article[$photo_size]))
				{
					if(TIM_THUMB)
					{
						$article[$photo_size] = IMAGE_URL.$height.'/'.$width.'/'.ZC.'/'.$article[$photo_size];
					}
					else
					{
						$article[$photo_size] = IMAGE_URL.$article[$photo_size];
					}
				}
				unset($articles[$count]);
				$articles[$count] = $article;
				$count++;
			}
			return $articles;
		}
	}

	// format date for display on webpage
	// date paramter in string format ideally with time too but not required
	// format parameter is a string defining hwo the date should be displayed
	public function formatDate($date, $format)
	{
		$timestamp = strtotime($date);
		$date = date($format, $timestamp);
		return $date;
	}

	// removes non alpha numeric charaters
	// adds dashes instead of spaces
	public function formatForUrl($text)
	{
		$text = preg_replace("/[^a-zA-Z0-9\s]/", "", $text);
		return str_replace(" ", "-", $text);
	}

	public function formatArticles($articles, $first_trim='', $default_trim, $format_date=true)
	{
		if(!array_key_exists(0, $articles))
		{
			$articles2[0] = $articles;
			$articles = $articles2;
		}
		$formatted_articles="";
		try{
			$count=0;
			foreach($articles as $article)
			{
				$formatted_article = $article;
				if($format_date)
				{
					if(!empty($article['publish_date']))
					{
						$formatted_article['publish_date'] = $this->formatDate($article['publish_date'], 'jS F Y');
					}
					else
					{
						throw new error_CustomException("Article date missing");
					}
				}
				if(!empty($article['headline']))
				{
					$category_for_url = $this->formatForUrl($article['category_name']);
					$headline_for_url = $this->formatForUrl($article['headline']);
					$formatted_article['url_headline'] = BASE_URL.strtolower($category_for_url).'/'.strtolower($headline_for_url).'/'.$formatted_article['article_id'];
				}
				else
				{
					throw new error_CustomException("Article headline missing");
				}
				if(!empty($article['text']))
				{
					$trim = true;
					if($count == 0 && !empty($first_trim))
					{
						$trim_chars = $first_trim;
					}
					elseif(!empty($default_trim))
					{
						$trim_chars = $default_trim;
					}
					else
					{
						$trim = false;
					}
					$text = html_entity_decode($article['text']);
					if($trim)
					{
						$text = $this->truncate($text , $trim_chars, '...', true, true);
					}
					$formatted_article['text'] = $text;
				}
				else
				{
					throw new error_CustomException("Article category name missing");
				}
				$formatted_articles[$count] = $formatted_article;
				$count++;
			}
		}
		catch(error_CustomException $e)
		{
			$e->logError("file");
		}
		return $formatted_articles;
	}
}
?>