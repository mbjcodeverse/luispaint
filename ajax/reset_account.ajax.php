<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class resetAccount{
  public $userid;
  public $username;
  public $password;

  public function resetAccountSave(){
    $userid = $this->userid;
    $username = $this->username;
    $password = $this->password;
    $data = array("userid"=>$userid,
                  "username"=>$username,
                  "password"=>$password);
    $answer = (new ControllerUserRights)->ctrResetAccount($data);
    echo $answer;
  }
}

$inputUserID = new resetAccount();
$inputUserID -> userid = $_POST["userid"];
$inputUserID -> username = $_POST["username"];
$inputUserID -> password = $_POST["password"];
$inputUserID -> resetAccountSave();