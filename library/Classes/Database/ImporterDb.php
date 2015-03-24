<?php
/*
 * Class converts the xml object into an array which can then be inserted into the database
 */
class ImporterDb {

	// sets up db connection
	public function __construct()
	{
		$this->db = new Database();
	}
	
	public function checkForArticles($basicData)
	{
		$articles = array();
		foreach($basicData as $article)
		{
			$articleData = $this->db->select('article_id, last_modified', 'news_articles', 'article_id = '.$article['article_id'], '');
			if(!empty($articleData))
			{
				$last_modified = str_replace('T', ' ', $article['last_modified']);
				if($articleData[0]['last_modified'] != $last_modified)
				{
					$articles['update'][] = $article['article_id'];
				}
			}
			else
			{
				$articles['insert'][] = $article['article_id'];
			}
		}
		return $articles;
	}
	
	//main function for retrieving xml and writing it to the database
	//$articles parameter is simpleXmlObject containing news articles data
	public function insertArticles($newsArticleDataCollection)
	{
		try{
			//checks if data was retreived successfully, throws error if unsuccessful
			if(!empty($newsArticleDataCollection))
			{
				try{
					if($newsArticleDataCollection)
					{
						foreach($newsArticleDataCollection as $newsArticleData)
						{
							foreach($newsArticleData as $article)
							{
								$this->db->insertArrayIntoDatabase($article);
							}
						}
					}
				}
				catch(CustomException $e)
				{
					$e->logError("file");
				}
			}
			else
			{
				throw new CustomException('articles instance is empty');
			}
		}
		catch(CustomException $e)
		{
			$e->logError("file");
		}
	}
	
	public function updateArticles($newsArticleDataCollection)
	{
		try{
			//checks if data was retreived successfully, throws error if unsuccessful
			if(!empty($newsArticleDataCollection))
			{
				try{
					if($newsArticleDataCollection)
					{
						foreach($newsArticleDataCollection as $newsArticleData)
						{
							foreach($newsArticleData as $article)
							{
								$this->db->updateArrayIntoDatabase($article);
							}
						}
					}
				}
				catch(CustomException $e)
				{
					$e->logError("file");
				}
			}
			else
			{
				throw new CustomException('articles instance is empty');
			}
		}
		catch(CustomException $e)
		{
			$e->logError("file");
		}
	}
}
?>