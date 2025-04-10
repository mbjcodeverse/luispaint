<?php
class ControllerReceivable{
	static public function ctrSaveReceivablePayment($data){
	   	$answer = (new ModelReceivable)->mdlSaveReceivablePayment($data);
		return $answer;
	}

	// explored
	static public function ctrShowReceivableList(){
		$answer = (new ModelReceivable)->mdlShowReceivableList();
		return $answer;
	}	

	// explored
	static public function ctrShowCustomerReceivableList($customercode){
		$answer = (new ModelReceivable)->mdlShowCustomerReceivableList($customercode);
		return $answer;
	}

	static public function ctrShowReceivablePendingChecks($customercode){
		$answer = (new ModelReceivable)->mdlShowReceivablePendingChecks($customercode);
		return $answer;
	}	

	// Cash/On-date POSTED and PENDING Receivable payment per Customer Delivery
	static public function ctrGetTotalPostedPendingPayment($invno){
		$posted_pending = (new ModelReceivable)->mdlGetTotalPostedPendingPayment($invno);
		return $posted_pending;
	}	
	
	// ------------------------------------------------------------------------------------
	// Receivable Reset Display - NOT YET Resetted
	static public function ctrReceivableReset($branchcode, $postedby){
		$answer = (new ModelReceivable)->mdlReceivableReset($branchcode, $postedby);
		return $answer;
	}

	// Receivable Reset Display - NOT YET Resetted - Per Product Account
	static public function ctrReceivableResetAccount($branchcode, $postedby){
		$answer = (new ModelReceivable)->mdlReceivableResetAccount($branchcode, $postedby);
		return $answer;
	}	

	// Receivable Reset PRINT
	static public function ctrPrintReceivableReset($resetcode){
		$answer = (new ModelReceivable)->mdlPrintReceivableReset($resetcode);
		return $answer;
	}	

	// Receivable Reset - PRINT - Per Product Account
	static public function ctrPrintReceivableResetAccount($resetcode){
		$answer = (new ModelReceivable)->mdlPrintReceivableResetAccount($resetcode);
		return $answer;
	}	

	// Receivable Report
	static public function ctrShowReceivableReport($paydate, $customercode, $paymode, $reptype){
		$answer = (new ModelReceivable)->mdlShowReceivableReport($paydate, $customercode, $paymode, $reptype);
		return $answer;
	}	

	static public function ctrCustomerTotalReceivable($customercode){
		$answer = (new ModelReceivable)->mdlCustomerTotalReceivable( $customercode);
		return $answer;
	}

	static public function ctrGetClientPayment($paynum){
		$answer = (new ModelReceivable)->mdlGetClientPayment($paynum);
		return $answer;
	}
}
