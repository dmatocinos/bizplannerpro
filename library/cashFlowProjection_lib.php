<?php

class cashFlowProjection_lib{
	
	public $outputMsg =  array();	
	public $allmsgs = array();
	public $color = array();
	
	
	function __construct(){
		$this->db = new Database();
		$this->global_func = new global_lib();
		$this->format_f = new format_FrontEndFormat();
	}
	
	public function Payments($businessPlanId)
	{
		$orderDesc = "";
		$limit = "";
		$where = "cash_fp_bpid = '$businessPlanId'";
		$table = CASH_FLOW_PROJ;
		$_payments = 0;
		$_payments = $this->db->select("*", $table, $where, "", $orderDesc, $limit);
		return $_payments;
	}
	
	
	/*-----------------------------------------------------------------------------
		UPDATE THE INCOMING PAYMENTS SECTION, IF NEVER EXIST CALL UPLOAD FUNCTION  
	-----------------------------------------------------------------------------*/
	public function updateIncomingPayments($businessPlanId, $incomingPercentage, $collectIncomingPayment)
	{
		$isOK = false;
		$table = CASH_FLOW_PROJ;
		$where = "cash_fp_bpid = '$businessPlanId'";
		
		$_CheckIfExist = $this->db->select("cash_fp_bpid", $table, $where, "", "", "");
		if($_CheckIfExist)
		{
			$setColumn = "percentage_sale = '$incomingPercentage', days_collect_payments = '$collectIncomingPayment'";
			if($this->db->update($table, $setColumn, $where))
			{
				$isOK = true;
			}	
		}
		else
		{
			$isOK = $this->UploadIncomingPayments($table, $businessPlanId, $incomingPercentage, $collectIncomingPayment);
		}
		return $isOK;	
	}
	
	/*-----------------------------------------------------------------------------
		UPDATE THE OUTGOING PAYMENTS SECTION, IF NEVER EXIST CALL UPLOAD FUNCTION  
	-----------------------------------------------------------------------------*/

	public function updateOutgoingPayments($businessPlanId, $outgoingPercentage, $collectOutgoingPayment)
	{
		$isOK = false;
		$table = CASH_FLOW_PROJ;
		$where = "cash_fp_bpid = '$businessPlanId'";
		
		$_CheckIfExist = $this->db->select("cash_fp_bpid", $table, $where, "", "", "");
		if($_CheckIfExist)
		{
			$setColumn = "percentage_purchase = '$outgoingPercentage', days_make_payments = '$collectOutgoingPayment'";
			if($this->db->update($table, $setColumn, $where))
			{
				$isOK = true;
			}	
		}
		else
		{
			$isOK = $this->UploadOutgoingPayments($table, $businessPlanId, $outgoingPercentage, $collectOutgoingPayment);	
		}
		return $isOK;	
		
	}
	
	
	/*-----------------------------------------------------------------------------
		PRIVATE UPLOAD FUNCTION  
	-----------------------------------------------------------------------------*/
	private function UploadIncomingPayments($table, $businessPlanId, $incomingPercentage, $collectIncomingPayment)
	{
		$isOK = false;
		$query = "(percentage_sale, days_collect_payments, cash_fp_bpid) VALUES ('$incomingPercentage', '$collectIncomingPayment', '$businessPlanId')";
		if($this->db->insert_advance($table, $query))
		{
			$isOK = true;
		}
		return $isOK;	
	}


	/*-----------------------------------------------------------------------------
		PRIVATE UPLOAD FUNCTION  
	-----------------------------------------------------------------------------*/
	private function UploadOutgoingPayments($table, $businessPlanId, $outgoingPercentage, $collectOutgoingPayment)
	{
		$isOK = false;
		$query = "(percentage_purchase, days_make_payments, cash_fp_bpid) VALUES ('$outgoingPercentage', '$collectOutgoingPayment', '$businessPlanId')";
		if($this->db->insert_advance($table, $query))
		{
			$isOK = true;
		}
		return $isOK;	
	}
}// end of class
?>