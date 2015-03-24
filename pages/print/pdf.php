<?php
// @important: this allows us to get the data since some queries
// depends on the session data directly
session_start();
// @important: i have to add this on every page i will create
// to not mess with pdf printing
ob_start();	
include_once("../../Base.php"); 
ob_end_clean();

$pdf = new BizPlannerProPDF('Biz Plan 123');
$pdf->build();
$pdf->output('plan');
