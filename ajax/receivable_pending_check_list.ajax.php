<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxCustomerPendingChecks{ 
   public $customercode;

   public function ajaxDisplayCustomerPendingChecks(){
     $customercode = $this->customercode;
     $answer = (new ControllerReceivable)->ctrShowReceivablePendingChecks($customercode);
     echo json_encode($answer);
   }
}

$receivable_pendings = new AjaxCustomerPendingChecks();
$receivable_pendings -> customercode = $_POST["customercode"];
$receivable_pendings -> ajaxDisplayCustomerPendingChecks();