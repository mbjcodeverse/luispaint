<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxReceiptNumber{ 
   public $receiptnum;

   public function ajaxDisplayReceiptNumber(){
     $receiptnum = $this->receiptnum;
     $answer = (new ControllerSale)->ctrGetReceiptNumber($receiptnum);
     echo $answer;
   }
}

$receipt_number = new AjaxReceiptNumber();
$receipt_number -> receiptnum = $_POST["receiptnum"];
$receipt_number -> ajaxDisplayReceiptNumber();