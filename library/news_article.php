<?php

class news_article_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
		$this->photo = new Photo_lib2();
	}
	
	
	
	public function getArticlesCategories()
	{
		/*
		$query_category = mysql_query("SELECT *, COUNT(name) FROM news_article GROUP BY name ORDER BY name ASC")
		or die(mysql_error());
	
		// Print out result
		while($row = mysql_fetch_array($query_category))
		{
			echo '<li><a href="'.BASE_URL.'bedsonleg_category.php?cart_name='.$row['name'].'">';
			echo $row['name']." </a>(".$row['COUNT(name)'].")</li>";
		}
		
		*/
		
		$column = ' *, COUNT(news_category) ';
		$where = true." GROUP BY news_category ";
		$orderAsc = " news_category ";
		$_getArticlesCategories = $this->db->select( $column, NEWS_ARTICLE_TABLE, $where, $orderAsc, "", "");
		
		if(count($_getArticlesCategories) > 0)
		{
			return $_getArticlesCategories;
		}
	}
	
	
	public function getArticles($where, $orderDesc, $limit)
	{
		
		$table = NEWS_ARTICLE_TABLE.', '.NEWS_ARTICLE_PHOTO_TABLE;
		
		
		if(!empty($where)){$where .= 'and news_article.news_id = photo_article.article_id';}
		else{$where = 'news_article.news_id = photo_article.article_id';}
		
		$_getArticles = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		
		return $_getArticles;
		
		//($columns, $table, $where, $orderAsc='', $orderDesc='', $limit='')
		
	}
	
	
	public function selectAllNews($limit)
	{
		$newsTitle = '';
		$allNews = $this->getArticles('', 'publich_date', $limit);
		
		foreach ($allNews as $news)
		{
			$title = $this->format_f->truncate($news['new_title'], $length = 40, $ending = '...', true, false);
			
			$date = $this->global_func->gmDateFormat('d/m/Y  ', $news['publich_date']);
			
			$newsTitle .= "<p><a href='".BASE_URL."/update/news/".$news['news_id']."'>".$title."</a> 
			<br/><span class='smaller'>(".$date.")</span> </p>";	
		}
		return $newsTitle;
	}
	
	
	public function upload_news($table, $queryString, $img_tmp, $img_name, $article_name)
	{
		$isOk = false;
		$saveNewsImg = false;
		
		// Format news title to name that will match photo's and save photo to the folder.
		$img_new_name = strtolower($this->format_f->formatForUrl($article_name));
		$saveNewsImg = $this->photo -> save_image($img_tmp, $img_name, $img_new_name);	
		
		
		if ($saveNewsImg)
		{
			//-- Save news article data in database --
			if($this->db->insert($table, $queryString))
			{	
				
				// Get id for the last article uploaded --
				$where = ""; 
				$getMaxArticleId = $this->db->select("MAX(news_id)", NEWS_ARTICLE_TABLE, $where, "", "");
				if(count($getMaxArticleId) > 0)
				{
					$articleid = $getMaxArticleId[0]['MAX(news_id)'];
					//-- call function to save article image photo data the photo table
					$imgExtension = $this->photo->fileExt;
					$img_new_name = $img_new_name.'.'.$imgExtension;
					$saveArticlePhoto = $this->upload_news_photo($articleid, $article_name, $img_new_name);
					if($saveArticlePhoto)
					{
						$isOk = true;		
					}
					else
					{
						$this->allmsgs = "There was an error trying to upload photo data. Please Contact the administrator"; 
						$this->color = 'red'; 
						$isOk = false;	
					}
				}
				else
				{
					$this->allmsgs = "There was an error (article id cannot be retrieved). Notify the administrator please."; 
					$this->color = 'red'; 
					$isOk = false;
				}
			}
			else
			{
				$this->allmsgs = "There was an error. Try again"; 
				$this->color = 'red'; 
				$isOk = false;
			}
		}
		else
		{
			$this->allmsgs = $this->photo->allmsgs;
			$this->color = $this->photo->color;
			$isOk = false;
		}
		
		return $isOk;
	}
	
	
	public function update_news($table, $setArticleColumns,  $where, $img_tmp, $img_name, $article_name, $article_id)
	{
		$isOk = false;
		$editPhoto = false;
		$img_new_name = '';
		
		// check if new photo is uploaded
		if(!empty($img_tmp)){$editPhoto = true;	}
		
		// photo needs deleting and resaving
		if($editPhoto)
		{
			
			// Format news title to name that will match photo's and save photo to the folder.
			$img_new_name = strtolower($this->format_f->formatForUrl($article_name));
			if ($this->editImage($img_tmp, $img_name, $img_new_name, $article_name, $article_id))
			{
				$isOk = $this->call_performUpdates($table, $setArticleColumns, $where, $article_name, $article_id, $img_new_name);
				
				if($isOk){header("Location:".$_SERVER['REQUEST_URI']);}	
			}
			//else
			{
				$isOk = false;	
			}
			
		}
		else // Photo does not need modifying
		{
			$isOk = $this->call_performUpdates($table, $setArticleColumns, $where, $article_name, $article_id, $img_new_name);		
		}
		return $isOk;
	}
	
	private function call_performUpdates($table, $setArticleColumns, $where, $article_name, $article_id, $img_new_name)
	{
		if($this->performUpdates($table, $setArticleColumns, $where, $article_name, $article_id, $img_new_name))
		{
			$this->allmsgs = 'Update was Successful';
			$this->color = 'blue';		
			$isOk = true;
		}
		else
		{
			$this->allmsgs = 'There was an error, ';
			$this->color = 'red';		
			$isOk = false;
		
		}
		return $isOk;
	}
	
	private function editImage($img_tmp, $img_name, $img_new_name, $article_name, $article_id)
	{
		// Delete old image, and save the news one
		
		$whereArticleId = 'article_id = '.$article_id;
		
		$isOk = false;
		$selectPhotoName = $this->db->select("photo_name", NEWS_ARTICLE_PHOTO_TABLE, $whereArticleId, "", "");
		if(count($selectPhotoName) > 0)
		{
			
			$db_img_name = $selectPhotoName[0]['photo_name'];
			// Delete photo from 
			$imagePath =	IMAGE_ARTICLE_PATH;
			$photo = new Photo_lib2();
			
			// If photo already exist, then attemp to delete
			if(!empty($db_img_name))
			{
				$deletePhoto = $photo -> delete_image($db_img_name, $imagePath);
			}
			else // if no photo exist just return true as if delete was sussesfull
			{
				
				$deletePhoto = true;
			}
			
			
			if($deletePhoto)
			{
				// Save news photo
				$saveNewsImg = $this->photo -> save_image($img_tmp, $img_name, $img_new_name);	
				$imgExtension = $this->photo->fileExt;
				$this->newImageWithExtension = $img_new_name.'.'.$imgExtension;
				
				if ($saveNewsImg)
				{
					$isOk = true;	
				}
				else
				{
					$this->allmsgs = $this->photo->allmsgs;
					$this->color = $this->photo->color;
					$isOk = false;
				}
				
			}
			else // Could not Delele old image
			{
				$this->allmsgs = "Photo was not deleted and therefore can't update the database"; 
				$this->color = 'red'; 
				$isOk = false;	
			}
		}
			
		return $isOk;	
	}
	
	private function performUpdates($table, $setArticleColumns, $where, $article_name, $article_id, $img_new_name)
	{
		$isOk = false;
		// if photo was safe successfully, update news article table
		if($this->db->update($table, $setArticleColumns, $where))
		{
			// update photo table
			if($this->update_news_photo($article_id, $article_name, $img_new_name))
			{
				
				$isOk = true;	
			}
			
			else // could not update photo table
			{
				$this->allmsgs = "There was an error in updatng photo article. Please try again"; 
				$this->color = 'red'; 
				$isOk = false;
			}
		}
		
		else // Could not update news table
		{
			$this->allmsgs = "There was an error in updatng news article. Please try again"; 
			$this->color = 'red'; 
			$isOk = false;
		}
		return $isOk ;
	}
	
	private function update_news_photo($articleid, $article_name, $img_new_name)
	{
		// Instantiate and set the photo article query
		$articlePhotoData = new FormData();
		
		$htmalt = $article_name;
		$whereArticleId = 'article_id = '.$articleid;
		
		
		// IF image need chaning, then update the image attributes
		if(!empty($img_new_name))
		{
			$photoNewName = $this->newImageWithExtension;
			$articlePhotoData->ArticlePhotoFormData('updateAllPhotoAttributes', $articleid, $htmalt, $photoNewName);
		}
		else
		{
			$articlePhotoData->ArticlePhotoFormData('updateHtmlAltTag', $articleid, $htmalt, $img_new_name);
		}
		
		
		// get the query string
		$setPhotoColumns = $articlePhotoData->queryImageArticleStringUpdate;
		
		// insert photo data
		if($this->db->update(NEWS_ARTICLE_PHOTO_TABLE, $setPhotoColumns, $whereArticleId))
		{
			return true;
		}
		else
		{
			return false;	
		}	
	}
	
	
	
	private function upload_news_photo($articleid, $article_name, $img_new_name)
	{
		$htmalt = $article_name;
		
		$photoNewName = $img_new_name;
		
		// Instantiate and set the photo article query
		$articlePhotoData = new FormData();
		$articlePhotoData->ArticlePhotoFormData('create', $articleid, $htmalt, $photoNewName);
		
		// get the query string
		$photoQuery = $articlePhotoData->queryImageArticleStringInsert;
		
		// insert photo data
		if($this->db->insert(NEWS_ARTICLE_PHOTO_TABLE, $photoQuery))
		{
			return true;
		}
		else
		{
			return false;	
		}	

	}
	
	
	

	private function DisplayAllMsgs($arg1, $arg2)
	{
		if(empty($arg1)){$arg1 = $this->allmsgs;}
		if(empty($arg2)){$arg2 = $this->color;}
		return $this->global_func->DisplayAllMessages($arg1, $arg2);
	}
}// end of class
?>