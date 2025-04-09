<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxReceivableCustomer{ 
   public $customercode;

   public function ajaxDisplayReceivableCustomer(){
     $customercode = $this->customercode;

     $answer = (new ControllerReceivable)->ctrCustomerTotalReceivable($customercode);
     echo json_encode($answer);
   }
}

$receivable_customer = new AjaxReceivableCustomer();
$receivable_customer -> customercode = $_POST["customercode"];
$receivable_customer -> ajaxDisplayReceivableCustomer();