<?php
require_once "../controllers/clients.controller.php";
require_once "../models/clients.model.php";

class AjaxCustomer{
    public $idCustomer;
    public function ajaxGetCustomer(){
      $item = "id";
      $value = $this->idCustomer;
      $answer = (new ControllerClients)->ctrShowCustomer($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idCustomer"])){
  $getCustomer = new AjaxCustomer();
  $getCustomer -> idCustomer = $_POST["idCustomer"];
  $getCustomer -> ajaxGetCustomer();
}