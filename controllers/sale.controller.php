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

	static public function ctrSalesReport($reptype, $customercode, $salemode, $start_date, $end_date, $status, $paystatus){
		$answer = (new ModelSale)->mdlSalesReport($reptype, $customercode, $salemode, $start_date, $end_date, $status, $paystatus);
		return $answer;
	}

    static public function ctrShowSale($invno){
		$answer = (new ModelSale)->mdlShowSale($invno);
		return $answer;
	}

	static public function ctrPrintSales($reptype, $customercode, $salemode, $status, $start_date, $end_date){
		$answer = (new ModelSale)->mdlPrintSales($reptype, $customercode, $salemode, $status, $start_date, $end_date);
		return $answer;
	}

	static public function ctrGetReceiptNumber($receiptnum){
		$answer = (new ModelSale)->mdlGetReceiptNumber($receiptnum);
		return $answer;
	}

	
	static public function ctrVoidSale($invno){
		$answer = (new ModelSale)->mdlVoidSale($invno);
		return $answer;
	}	
}