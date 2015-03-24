<?php
	error_reporting(E_ALL);
	$display_errors = isset($_GET['de']) ? 1 : 0;
	ini_set('display_errors', $display_errors);
	
	// CHange this to match the folder in whcih you place this in
	define ("MAIN_FOLDER", "virtualfd/public/bizplannerpro");
	
	$slash = (substr($_SERVER['DOCUMENT_ROOT'], -1)=='/'? '' : '/');
	
	//define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']."/".MAIN_FOLDER);
	define("BASE_PATH", $_SERVER['DOCUMENT_ROOT'] . $slash . MAIN_FOLDER);
	
	date_default_timezone_set('Europe/London');
	
	//require(BASE_PATH."/Definitions.php");
	require(BASE_PATH."/Definitions.php");
	
	// library files
	include(LIBRARY_PATH."/global_lib.php");
	include(LIBRARY_PATH."/page_lib.php");
	include(LIBRARY_PATH."/register_lib.php");
	include(LIBRARY_PATH."/activate_lib.php");
	include(LIBRARY_PATH."/login_lib.php");
	include(LIBRARY_PATH."/update_lib.php");
	
	include(LIBRARY_PATH."/archive_lib.php");
	include(LIBRARY_PATH."/employee_lib.php");
	include(LIBRARY_PATH."/expenditure_lib.php");
	include(LIBRARY_PATH."/sales_forecast_lib.php");
	include(LIBRARY_PATH."/cashFlowProjection_lib.php");
	include(LIBRARY_PATH."/LoansInvestments_lib.php");
	include(LIBRARY_PATH."/jpgraph_lib.php");
	include(LIBRARY_PATH."/BusinessPlan_lib.php");
	include(LIBRARY_PATH."/writeToFile.php");
	include(LIBRARY_PATH."/bizplannerpro_pdf.php");
	
	
	
	include(LIBRARY_PATH."/FormData.php");
	
	require_once(CLASS_PATH. "/format/frontendformat.php");
	
	
	require_once(CLASS_PATH.'/Settings/Settings.php');
	
	
	
	
	require_once(ERROR_CLASS_PATH.'/CustomException.php');
	require_once(CREDENTIALS_PATH."/Credentials.php"); 
	require_once(DB_CLASS_PATH.'/Database.php');
	

	//connect to the database
	try{
		//$db = new Database();
		//$db->connect();
	
	}
	catch(CustomException $e)
	{
		$e->logError("file");
	
	}
	
	
	
	$global_func = new global_lib();
	$outputMsg = array();
	$color = array();
	$msgs = '';
	
	include_once BASE_PATH . 'include/page-setter.php';
?>
