<?php
/*
* Class extends original php exception class
* allows for more complex errors not to be shown to the user
* errors can be emailed or saved to file
* Custom exception is the default exception 
*/
class CustomException extends Exception {
	
	public function logError($log_type)
	{
		$this->errorFormat($log_type);
	}
	public function writeErrorToFile()
	{
		$error_content = $this->formatErrorText();
		$file_name = $this->getFileName();
		$handle = fopen(ERROR_FOLDER.$file_name, 'a');
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
	
	public function errorFormat($log_type)
	{
		try{
			switch($log_type)
			{
				case "file":
					$this->writeErrorToFile();
					break;
				case "email":
					$this->emailError();
					break;
				case "database":
					break;
			}
		}
		catch(CustomException $e)
		{
			$this->errorFormat("file");
			echo $e->getMessage();
		}
	}
	
	public function formatErrorText()
	{
		$error_text = "Error: ".$this->getMessage()." at time: ".$this->getTime().TEXT_FILE_NEWLN;
		$error_text .= $this->getTraceAsString().TEXT_FILE_NEWLN;
		$error_text .= TEXT_FILE_NEWLN;
		return $error_text;
	}
	
	public function emailError()
	{
            /*
		$error = $this->formatErrorText();
		$to = ERROR_EMAIL;
		$subject = "Error on: ".SITE;
		$header = 'From:'.SITE;
		$mail_success = mail($to, $subject, $error, $header);
		if(!$mail_success)
		{
			throw new CustomException("Error email sending failed");
		}
             
             */
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


class DisplayException extends Exception {

	public function displayError()
	{
		return "<div class=\"error\"><p id=\"userinfo\">".$this->getMessage()."</p></div>";
	}
	
}

?>