<?php
class ControllerCollection{
	// Collection Report
	static public function ctrShowCollectionReport($start_date, $end_date, $customercode, $paymode, $reptype){
		$answer = (new ModelCollection)->mdlShowCollectionReport( $start_date, $end_date, $customercode, $paymode, $reptype);
		return $answer;
	}	
}
