<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class ReceivableReset{
    public $branchcode;
    public $postedby;

    public function ajaxReceivableReset(){
      $branchcode = $this->branchcode;
      $postedby = $this->postedby;

      $answer = (new ControllerReceivable)->ctrReceivableReset($branchcode, $postedby);
      echo json_encode($answer);
    }
}
 
$resetCashCredit = new ReceivableReset();
$resetCashCredit -> branchcode = $_POST["branchcode"];
$resetCashCredit -> postedby = $_POST["postedby"];
$resetCashCredit -> ajaxReceivableReset();