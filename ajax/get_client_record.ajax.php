<?php
require_once "../controllers/clients.controller.php";
require_once "../models/clients.model.php";
class AjaxClient{
    public $idClient;
    public function ajaxGetClient(){
      $item = "id";
      $value = $this->idClient;
      $answer = (new ControllerClients)->ctrShowClients($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idClient"])){
  $getClient = new AjaxClient();
  $getClient -> idClient = $_POST["idClient"];
  $getClient -> ajaxGetClient();
}