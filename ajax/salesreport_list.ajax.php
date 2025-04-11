<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class SalesReport{ 
   public $reptype;
   public $customercode;
   public $salemode;
   public $start_date;
   public $end_date;   
   public $status;
   public $paystatus;

   public function DisplaySalesReport(){
     $reptype = $this->reptype;
     $customercode = $this->customercode;
     $salemode = $this->salemode;
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $status = $this->status;
     $paystatus = $this->paystatus;

     $answer = (new ControllerSale)->ctrSalesReport($reptype, $customercode, $salemode, $start_date, $end_date, $status, $paystatus);
     echo json_encode($answer);
   }
}

$sales = new SalesReport();
$sales -> reptype = $_POST["reptype"];
$sales -> customercode = $_POST["customercode"];
$sales -> salemode = $_POST["salemode"];
$sales -> start_date = $_POST["start_date"];
$sales -> end_date = $_POST["end_date"];
$sales -> status = $_POST["status"];
$sales -> paystatus = $_POST["paystatus"];
$sales -> DisplaySalesReport();