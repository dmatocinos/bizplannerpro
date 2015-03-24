<?php
session_start();
$bp_user_id = $_SESSION['bp_user_id'];
$keys = array(
		'bp_user_id',
		'bpRelatedExpensesInPercentage',
		'bpFinancialStartDate',
		'bpcurrency',
		'array_grossMargin',
		'capt_key',
		'act_code',
		'bpName',
		'bpNumberOfFinancialForecastYr',
		'bpYrsOfMonthlyFinancialDetails',
		'bpId',
		'bpIncomeTaxInPercentage',
		'verifiedTable',
		'verifiedUser',
		'verifiedUserId',
		'verifiedUserMod',
		'PatiallyVerifiedUser',
		'levelTwoIsActivated',
		'act_code',
		'useremail',
		'table',
		'timeout',
		'sessionid',
		'auth_message',
		'appreferrer',
		'authentic',
        'login_type'
		);

foreach($keys as $key)
{
	unset ($_SESSION[$key]);
}

//header('location: http://localhost/virtualfdpro/public/');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $base_url = "http://localhost/virtualfd/public";
}
else {
    $base_url = "";
}

if ($bp_user_id) {
	header('Location: ' . $base_url . "/clients/{$bp_user_id}/profile");
}
else {
	header('Location: ' . $base_url . '/clients/home');
}
die();




?>
