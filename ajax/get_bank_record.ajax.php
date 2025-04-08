<?php
require_once "../controllers/bank.controller.php";
require_once "../models/bank.model.php";

class AjaxBank{
    public $idBank;
    public function ajaxGetBank(){
      $item = "id";
      $value = $this->idBank;
      $answer = (new ControllerBank)->ctrShowBank($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idBank"])){
  $getBank = new AjaxBank();
  $getBank -> idBank = $_POST["idBank"];
  $getBank -> ajaxGetBank();
}