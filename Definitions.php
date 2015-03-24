	<?php
	
	define("BASE_URL", "http://".$_SERVER['HTTP_HOST']."/".MAIN_FOLDER);
	define('EXIT_URL', BASE_URL . '/exit');
	define('LOGOUT_URL', BASE_URL . '/../auth/logout');
	
	
	define("CREDENTIALS_PATH", BASE_PATH."/credentials"); 
	define("ERROR_FOLDER", BASE_PATH."/error logs/"); //path of where the error log files will be stored
	define("USER_FOLDER", BASE_PATH."/u_folder/"); //path of where the error log files will be stored
	define('ERROR_EMAIL', ''); //**change this to your own email address to receive error noification** //
	define('TEXT_FILE_NEWLN', "\r\n "); //add a new line for a text file

	define("LIBRARY_PATH", 		BASE_PATH."/library"); // library path
	define("CLASS_PATH", 		LIBRARY_PATH."/Classes"); //path where all site files are stored
	define("DB_CLASS_PATH", 	CLASS_PATH."/Database"); //path to database classes
	define("ERROR_CLASS_PATH", 	CLASS_PATH."/Error"); //path to error classes
	define("STANDALONES", 		BASE_URL."/standalone_apps");
	define("PLUGIN_APP_URL", 				BASE_URL."/plugin_app");
	define("TINYMCE_EDITOR_APP_URL", 		PLUGIN_APP_URL."/tinymce");
	
	define("PDF_IMAGES_PATH", 	BASE_PATH."/images/pdf/");
	define("GRAPH_IMAGES_PATH", 	BASE_PATH."/images/graph/");  
	define("TCPDF_PATH", 		CLASS_PATH."/pdf/tcpdf/");
	define("TTF_DIR", 		CLASS_PATH."/graph/fonts/");
	
	// define pages URL
	
	define ("plan",					  				BASE_URL."/plan");
	define ("executive_summary_url",  				BASE_URL."/executive-summary");
	
	define ("company_url",  						BASE_URL."/company");
	
	define ("products_and_services_url",  			BASE_URL."/products-and-services");
	
	define ("target_market_url",  					BASE_URL."/target-market");
	
	define ("strategy_and_implementation_url",  	BASE_URL."/strategy-and-implementation");
	
	define ("financial_plan_url", 					BASE_URL."/financial-plan");
	
	define("OPEN_BRACKET", "(", true);

	define("CLOSED_BRACKET", ")", true);

	$open_bracket  = "";
	$closed_bracket = "";
	
	// define image properties
	define ("IMAGE_URL",  BASE_URL."/images");
	define ("IMAGE_GRAPH_URL",  IMAGE_URL."/graph/");
	define ("IMAGE_DB_URL", BASE_URL.'/library/image_upload/getImage.php', true);
	
	//DEFINE LOGIN EXPIRE TIME IN SECONDS HERE 
	define ("LOGIN_EXPIRE_TINE", 500, true);

	// define table names
	define ("BUSINESS_PLAN", 				"business_plan", true);
	define ("PAGE_TB",  					"pages", true);
	define ("SECTION_TB",  					"page_sections", true);
	define ("BP_PAGES_TB", 					"bp_pages", true);
	define ("BP_SECTION_TB",  				"bp_page_sections", true);
	
	
	define ("EMPLOYEE_TB",  				"employee", true);
	define ("_12_MONTH_EP_TB",  			"employee_12_month_plan", true);
	define ("E_FINANCIAL_FORECAST_TB",  	"employee_financial_forecast", true);
	
	define("MAJOR_PURCHASE_TB", 			"major_purchases", true);
	
	define ("EXPENDITURE_TB",  				"expenditure", true);
	define ("EX_12_MONTH_P_TB",  			"expenditure_12_month_plan", true);
	define ("EX_FINANCIAL_FORECAST_TB",  	"expenditure_financial_forecast", true);

	
	define ("SALES_FORECAST_TB",  				"sales_forecast", true);
	define ("SALES_12_MONTH_F_TB",  			"sales_12_month_forecast", true);
	define ("SALES_FINANCIAL_FORECAST_TB",  	"sales_financial_forecast", true);
	
	define ("LOAN_INVESTMENT_TB",  					"loan_investment", true);
	define ("LOAN_INVEST_12_M_RECEIVE_TB",  		"loan_investment_12m_received", true);
	define ("LOAN_INVEST_12_M_PAYMENT_TB",  		"loan_investment_12m_payment", true);
	define ("LOAN_INVEST_FINANCIAL_F_RECEIVE_TB",  	"loan_investment_received_f_yrs", true);
	define ("LOAN_INVEST_FINANCIAL_F_PAYMENT_TB",  	"loan_investment_payment_f_yrs", true);
	
	
	
	
	define ("CASH_FLOW_PROJ",  				"cash_flow_projection", true);
	
	// include files	
	define("TOP", 				BASE_PATH."/include/top.php");
	define("TOP2", 				BASE_PATH."/include/top2.php", true);
	define("BOTTOM", 			BASE_PATH."/include/bottom.php");
	define("BOTTOM2", 			BASE_PATH."/include/bottom2.php");
	define("BOTTOM3", 			BASE_PATH."/include/bottom3.php");
	define("LEFTMENU", 			BASE_PATH."/include/leftmenu.php");
	define("HEAD", 				BASE_PATH."/include/head.php");
	define ("INTRO_PARAGRAPH",  BASE_PATH."/include/paragraph_intro.php");
	define ("text_editor_url",  BASE_PATH."/tinymce_js.php");
	
	
	define("SOCIAL_BLOCK", 	BASE_PATH."/include/social_media.php");	 
	define("LEFTPANEL", 	BASE_PATH."/include/left_panel.php");	 
	define("VALIDATE_FORM", BASE_PATH."/include/form_validate.php");
	
	
	
	//EMAILS
	define("DEVELOPER_EMAIL", 		"tosin.oginni@mulburyhamilton.co.uk", true);
	define("DEVELOPER_EMALI_AXIL", 	"tosyn800@gmail.com", true);
	
	
	//Dummy date of Birth
	define("DUMMY_BIRTHDAY", 				"DD-MM-YYYY", true);
	
	define ("salesForecastImg", 			'Sale_forec.gif', true);
	define ("personnelPlanImg", 			'Pers_pan.gif', true);
	define ("budgetImg", 					'Budget.gif', true);
	define ("loansAndInvestmentsImg", 		'bookmark.png', true);
	define ("profitAndLossStatementImg",	'pie_chart.png', true);
	define ("balanceSheetImg", 				'balance_icon.png', true);
	define ("cashFlowStatementImg", 		'coins_icon.png', true);
	
	
	
?>
	
