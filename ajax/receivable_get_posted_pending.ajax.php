<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxPostedPending{ 
   public $invno;

   public function ajaxDisplayPostedPending(){
     $invno = $this->invno;
     $posted_pending = (new ControllerReceivable)->ctrGetTotalPostedPendingPayment($invno);
     echo json_encode($posted_pending);
   }
}

$posted_pending_receivable = new AjaxPostedPending();
$posted_pending_receivable -> invno = $_POST["invno"];
$posted_pending_receivable -> ajaxDisplayPostedPending();