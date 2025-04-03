<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class userGetLoginCredential{
  public $username; 
  public $upassword;

  public function userRightsGetLoginCredential(){
    $username = $this->username;
    $upassword = $this->upassword;
    $answer = (new ControllerUserRights)->ctrGetUserLogin($username, $upassword);
    echo json_encode($answer);
  }
}

$getCredential = new userGetLoginCredential();

$getCredential -> username = $_POST["username"];
$getCredential -> upassword = $_POST["upassword"];

$getCredential -> userRightsGetLoginCredential();