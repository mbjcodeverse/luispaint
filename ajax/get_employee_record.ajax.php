<?php
require_once "../controllers/employees.controller.php";
require_once "../models/employees.model.php";
class AjaxEmployee{
    public $idEmployee;
    public function ajaxGetEmployee(){
      $item = "id";
      $value = $this->idEmployee;
      $answer = (new ControllerEmployees)->ctrShowEmployees($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["idEmployee"])){
  $getEmployee = new AjaxEmployee();
  $getEmployee -> idEmployee = $_POST["idEmployee"];
  $getEmployee -> ajaxGetEmployee();
}