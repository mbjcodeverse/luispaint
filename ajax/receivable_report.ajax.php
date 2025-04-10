<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxReceivableReport{ 
   public $paydate;  
   public $customercode;
   public $paymode;
   public $reptype;

   public function ajaxDisplayReceivableReport(){
     $paydate = $this->paydate;
     $customercode = $this->customercode;
     $paymode = $this->paymode;
     $reptype = $this->reptype;

     $answer = (new ControllerReceivable)->ctrShowReceivableReport($paydate, $customercode, $paymode, $reptype);
     echo json_encode($answer);
   }
}

$sale_report = new AjaxReceivableReport();
$sale_report -> paydate = $_POST["paydate"];
$sale_report -> customercode = $_POST["customercode"];
$sale_report -> paymode = $_POST["paymode"];
$sale_report -> reptype = $_POST["reptype"];
$sale_report -> ajaxDisplayReceivableReport();