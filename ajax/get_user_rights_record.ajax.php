<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";
class AjaxUserRights{
    public $userid;
    public function ajaxGetUserRights(){
      $item = "userid";
      $value = $this->userid;
      $answer = (new ControllerUserRights)->ctrShowUserRights($item, $value);
      echo json_encode($answer);
    }
}
 
if(isset($_POST["userid"])){
  $getUserRights = new AjaxUserRights();
  $getUserRights -> userid = $_POST["userid"];
  $getUserRights -> ajaxGetUserRights();
}