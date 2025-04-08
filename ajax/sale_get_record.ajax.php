<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class SaleDetails{
    public $invno;
    public function getSaleDetails(){
      $invno = $this->invno;
      $answer = (new ControllerSale)->ctrShowSale($invno);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["invno"])){
  $getSale = new SaleDetails();
  $getSale -> invno = $_POST["invno"];
  $getSale -> getSaleDetails();
}