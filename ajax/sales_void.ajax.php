invno<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class AjaxVoidSale{ 
   public $invno;

   public function ajaxDisplayVoidSale(){
     $invno = $this->invno;
     $answer = (new ControllerSale)->ctrVoidSale($invno);
     echo $answer;
   }
}

$void_sale = new AjaxVoidSale();
$void_sale -> invno = $_POST["invno"];
$void_sale -> ajaxDisplayVoidSale();