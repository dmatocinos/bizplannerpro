<?php
class Database {  
 
	public function __construct()
	{
		$this->info = new Credentials();
		$this->connect();
	}	
     //open a connection to the database.   
    public function connect() 
	{
        $this->connection = mysql_connect($this->info->db_host, $this->info->db_user, $this->info->db_pass);  
        $this->selectDb(); 
		if(!$this->connection)
		{
			throw new CustomException("Could not connect to the database");
		}
    }
	
	public function disconnect()
	{
		$success = mysql_close();
		if(!$success)
		{
			throw new CustomException("Could not disconnect from the database");
		}
	}

	public function selectDb()
	{
        $db_select = mysql_select_db($this->info->db_name); 
		if(!$db_select)
		{
			throw new CustomException("Could not select database: ".$this->info->db_name);
		}
	}
   
     //takes a mysql row set and returns an associative array, where the keys  
     //in the array are the column names in the row set. If singleRow is set to  
     public function processRowSet($rowSet, $singleRow=false)  
     {  
         
         try{
         
         $resultArray = array();  
         while($row = mysql_fetch_assoc($rowSet))  
         {  
             array_push($resultArray, $row);  
         }
		 mysql_free_result($rowSet);
		 
		 } catch(CustomException $e)
		{
			
			$e->logError("file");
			// thrown again so exception can be caught in a more specific context if necessary
			throw new CustomException();
			return;
		}
		 
		 
         return $resultArray;  
     } 
	 
	 //query that doesnt fit standard methods, usually used for complex join queries
	public function select($columns, $table, $where, $orderAsc='', $orderDesc='', $limit='')
	{
		try{
			 $sql = "SELECT $columns FROM ".$table;
			
			
					 
			 
			if(!empty($where))
			{
				$sql .= " WHERE $where";
			}
			if(!empty($group_by))
			{
				$sql .= " GROUP BY $group_by";
			}
			if(!empty($orderAsc))
			{
				$sql .= " ORDER BY $orderAsc ASC";
			}
			if(!empty($orderDesc))
			{
				$sql .= " ORDER BY $orderDesc DESC";
			}
			if(!empty($limit))
			{
				$sql .= " LIMIT $limit";
			}
			//print_r($sql.'<hr />'); die();
			$result = mysql_query($sql);
			if(!$result)
			{
				throw new CustomException("select query failed: ".$sql);
			}
			if(mysql_num_rows($result) == 1)
			{
				return $this->processRowSet($result, true);
			}  
			return $this->processRowSet($result);   
		}
		catch(CustomException $e)
		{
			
			$e->logError("file");
			// thrown again so exception can be caught in a more specific context if necessary
			throw new CustomException();
			return;
		}
	}
	
	public function delelet($table, $queryString)
	{
		try{
			$delete = mysql_query("delete from `$table` WHERE ".$queryString)or die(mysql_error());
			if($delete)
			{return true;}
			else{return false;}
		}
		catch(CustomException $e)
		{
			$e->logError("file");
			return false;
			throw new CustomException();
			
		}
	}
	public function insert($table, $queryString)
	{
		try{
			$add = mysql_query("INSERT INTO `$table` VALUES ".$queryString)or die(mysql_error());
			
			if($add)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		catch(CustomException $e)
		{
			$e->logError("file");
			return false;
			throw new CustomException();
			
		}
	}
	
	public function insert_advance($table, $queryString)
	{
		try{
			$add = mysql_query("INSERT INTO `$table` ".$queryString)or die(mysql_error());
		
			if($add)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		catch(CustomException $e)
		{
			$e->logError("file");
			return false;
			throw new CustomException();
			
		}
	}
	
	
	public function update($table, $setColumns, $where) {  
		try{
			$columns = "";  
			$values = "";  
			$sqlString = "";
			
			if(!empty($where))
			{
				$where = " WHERE $where";
			}
			
			//$sqlString .= "'".htmlentities(addslashes($value),ENT_COMPAT, "UTF-8")."'";
			$sql = "UPDATE ".$table." SET ".$setColumns." ".$where;
			//print_r("<br/>".($sql)."<br/><br/><hr/>");
			
			$success = mysql_query($sql);
			if(!$success)
			{
				throw new CustomException("sql update query failed: ".$sql);
				return false;
			}
			return true;
		}
		catch(CustomException $e)
		{
			throw new CustomException("sql update query failed: ".$sql);
			$e->logError("file");
		}
	}  
	 
     //Select rows from the database.  
     //returns a full row or rows from $table using $where as the where clause.  
     //return value is an associative array with column names as keys.  
     public function select_old($columns, $table, $where, $order, $limit='') 
	 {	 
		try{
			$sql = "SELECT $columns FROM ".TBL_IDENT."_".$table;
			if(!empty($where))
			{
				$sql .= " WHERE $where";
			}
			if(!empty($order))
			{
				$sql .= " ORDER BY $order DESC";
			}
			if(!empty($limit))
			{
				$sql .= " LIMIT $limit";
			}
			$result = mysql_query($sql);			
			if(!$result)
			{
				throw new CustomException("select query failed: ".$sql);
			}
			if(mysql_num_rows($result) == 1)
			{
				return $this->processRowSet($result, true);
			}  
			return $this->processRowSet($result);  
		}
		catch(CustomException $e)
		{
			$e->logError("file");
			// thrown again so exception can be caught in a more specific context if necessary
			throw new CustomException();
			return;
		}
	}  
	

   
     //Updates a current row in the database.  
     //takes an array of data, where the keys in the array are the column names  
     //and the values are the data that will be inserted into those columns.  
     //$table is the name of the table and $where is the sql where clause.  
     public function update_old($data, $table, $where) {  
		try{
			$columns = "";  
			$values = "";  
			$sqlString = "";
			foreach ($data as $column => $value) 
			{
				$sqlString .= $column." =";
				$sqlString .= "'".htmlentities(addslashes($value),ENT_COMPAT, "UTF-8")."'";
				unset($data[$column]);
				if(!empty($data))
				{
					$sqlString .= ", ";
				}
				else
				{
					$sqlString .= " ";
				}
			}
			$sql = "UPDATE ".TBL_IDENT."_".$table." SET ".$sqlString.$where;
			$success = mysql_query($sql);
			if(!$success)
			{
				throw new CustomException("sql update query failed: ".$sql);
				return false;
			}
			return true;
		}
		catch(CustomException $e)
		{
			$e->logError("file");
		}
     }  
   
     //Inserts a new row into the database.  
     //takes an array of data, where the keys in the array are the column names  
     //and the values are the data that will be inserted into those columns.  
     //$table is the name of the table.  
     public function insert_old($data, $table) 
	 {
		try{
			$columns = "";  
			$values = "";  
			$sqlString = "";
			foreach ($data as $column => $value) 
			{
				$sqlString .= $column." =";
				$sqlString .= "'".htmlentities(addslashes($value),ENT_COMPAT, "UTF-8")."'";
				unset($data[$column]);
				if(!empty($data))
				{
					$sqlString .= ", ";
				}
				else
				{
					$sqlString .= " ";
				}
			}
			$sql = "insert ignore into ".TBL_IDENT."_".$table." set ".$sqlString;
			$success = mysql_query($sql);
			if(!$success)
			{
				throw new CustomException("sql insert query failed: ".$sql);
				return false;
			}
			return true;
		}
		catch(CustomException $e)
		{
			$e->logError("file");
		}
     }
	 

	 
	
	
	
 	// writes data to database using insert method
	// $arrayData parameter contains specifically formatted array for insert
	public function insertArrayIntoDatabase($arrayData)
	{
		$values="";
		foreach ($arrayData as $table => $value)
		{
			foreach($value as $insertValue)
			{
				$this->insert($insertValue, $table);
			}
		}
	}
	
	public function updateArrayIntoDatabase($arrayData)
	{
		$values="";
		foreach ($arrayData as $table => $value)
		{
			foreach($value as $insertValue)
			{
				if(array_key_exists('article_id', $insertValue))
				{
					$where = 'WHERE article_id = '.$insertValue['article_id'];
				}
				if(array_key_exists('photo_id', $insertValue))
				{
					$where = 'WHERE photo_id = '.$insertValue['photo_id'];
				}
				if(array_key_exists('category_id', $insertValue))
				{
					$where = 'WHERE category_id = '.$insertValue['category_id'];
				}
				$this->update($insertValue, $table, $where);
			}
		}
	}  	
 }  
?>
