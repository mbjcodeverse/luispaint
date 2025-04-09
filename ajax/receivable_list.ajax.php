<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class AjaxReceivableList{ 
   public function ajaxDisplayReceivableList(){
     $answer = (new ControllerReceivable)->ctrShowReceivableList();
     echo json_encode($answer);
   }
}

$receivablelist = new AjaxReceivableList();
$receivablelist -> ajaxDisplayReceivableList();