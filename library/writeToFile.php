<?php
/*
* Class extends original php exception class
* allows for more complex errors not to be shown to the user
* errors can be emailed or saved to file
* Custom exception is the default exception 
*/
class writeToFile{
	
	
	
	
	function __construct(){
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
	}
	
	public function createFolder($folder01, $folder_02)
	{
		$isOk = false;
		$dir = USER_FOLDER."/".$folder01;
		$this->folder_dir = $dir;
		// if folder exist
		if(is_dir($dir)) 
		{
			
			echo "Folder already exist";
			$isOk = true;	
		} 
		else
		{
			echo "creating folder...";
			// if folder 2 is not empty
			if(!empty($folder_02))
			{
				$child_dir = $dir.'/'.$folder_02;
				if(mkdir($child_dir, 0777, true))
				{
					if((chmod($child_dir, 0777)) and (chmod($dir, 0777)))	
					{
						$this->folder_dir = $child_dir;
						$isOk = true;
					}
					else{$isOk = false;}
				}
				else{$isOk = false;}
			}
			
			else // create main folder, $dir 
			{	
				if(mkdir($dir, 0777, true))
				{
					if(chmod($dir, 0777))
					{
						$isOk = true;
					}	
					else{$isOk = false;}	
				}
				else{$isOk = false;}
			}
		}
	
		
		// Create a file
		/*
		$filename = "write_folder/testFile.txt";
	
		$f = fopen($filename, 'w');
	
		if($f)
		{
			$msg = 'File created.';
			fclose($f);
		}	
		else
		{
			$write_msg = 'Unable to write to file.';	
		}
		*/
		
		return $isOk;
	
	}
	
	public function wrteToFile2($folder01, $folder_02, $mode)
	{
		/* 
			$mode can be a or r or w and so on
			a = Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
			r = Open for reading only; place the file pointer at the beginning of the file.
			w = Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. 
																				If the file does not exist, attempt to create it.
		*/
			if($this->createFolder($folder01, $folder_02))
			{
				//
				$error_content = $this->getContent();
				$file_name = $this->getFileName2();
				$handle = fopen($this->folder_dir.'/'.$file_name, $mode);
				$write_success = fwrite($handle, $error_content);
				if(!$write_success || !$handle)
				{
					$isOK = false;
					throw new CustomException("error could not be written to file");
				}
				else
				{
					$isOK = true;
				}
				$close_success = fclose($handle);
				if(!$close_success)
				{
					$isOK = false;
					throw new CustomException("File could not be closed");
				}
				
			}
			else
			{
				// cannot access folder
				$isOK = false;
			}
			
			return $isOK;
	}
	
	public function getFileName2()
	{
		$file_name = "PLN_PLNID.doc";
		//$date = getDate(time());
		//$file_name = "ErrorLog-".$date['mday'].$date['month'].".txt";
		
		return $file_name;
	}
	
	public function getContent()
	{
		return "hello world".TEXT_FILE_NEWLN;
	}
	
	
	public function logError($log_type)
	{
		$this->errorFormat($log_type);
	}
	
	
	public function writeToFile_()
	{
		$error_content = $this->formatErrorText();
		$file_name = $this->getFileName();
		$handle = fopen(USER_FOLDER.$file_name, 'a');
		$write_success = fwrite($handle, $error_content);
		if(!$write_success || !$handle)
		{
			throw new CustomException("error could not be written to file");
		}
		$close_success = fclose($handle);
		if(!$close_success)
		{
			throw new CustomException("File could not be closed");
		}
		return true;
	}
	
	
	
	public function formatErrorText()
	{
		$error_text = "Error: ".$this->getMessage()." at time: ".$this->getTime().TEXT_FILE_NEWLN;
		//$error_text .= $this->getTraceAsString().TEXT_FILE_NEWLN;
		$error_text .= TEXT_FILE_NEWLN;
		return $error_text;
	}
	
	

	public function getFileName()
	{
		$date = getDate(time());
		$file_name = "ErrorLog-".$date['mday'].$date['month'].".txt";
		return $file_name;
	}
	
	public function getTime()
	{
		$timeStamp = time();
		return date('H:i:s', $timeStamp);
	}
}

?>