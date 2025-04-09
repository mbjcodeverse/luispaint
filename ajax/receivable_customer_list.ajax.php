<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxCustomerReceivableList{ 
   public $customercode;

   public function ajaxDisplayCustomerReceivableList(){
     $customercode = $this->customercode;
     $answer = (new ControllerReceivable)->ctrShowCustomerReceivableList($customercode);
     echo json_encode($answer);
   }
}

$receivablelist = new AjaxCustomerReceivableList();
$receivablelist -> customercode = $_POST["customercode"];
$receivablelist -> ajaxDisplayCustomerReceivableList();