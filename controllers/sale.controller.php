<?php
class ControllerSale{
	static public function ctrAddSale($data){
	   	$answer = (new ModelSale)->mdlAddSale($data);
		return $answer;
	}

	static public function ctrEditSale($data){
        $answer = (new ModelSale)->mdlEditSale($data);
     return $answer;
    }    

    static public function ctrSalesInfoList($customercode, $salemode, $start_date, $end_date, $status, $paystatus){
		$answer = (new ModelSale)->mdlSalesInfoList($customercode, $salemode, $start_date, $end_date, $status, $paystatus);
		return $answer;
	}

    static public function ctrShowSale($invno){
		$answer = (new ModelSale)->mdlShowSale($invno);
		return $answer;
	}
}