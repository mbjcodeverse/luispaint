<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class userRightsEntry{
  public $trans_type; 
  public $userid;
  public $empid;
  public $invoices;
  public $receivable;
  public $reports;
  public $dashboard;
  public $clients;
  public $employees;
  public $branch;
  public $accessprivilege;
  public $username;
  public $upassword;

  public function userRightsEntrySave(){
    $trans_type = $this->trans_type;
    $userid = $this->userid;
    $empid = $this->empid;
    $invoices = $this->invoices;
  	$receivable = $this->receivable;
    $reports = $this->reports;
  	$dashboard = $this->dashboard;
    $clients = $this->clients;
  	$employees = $this->employees;
    $branch = $this->branch;
  	$accessprivilege = $this->accessprivilege;
    $username = $this->username;
    $upassword = $this->upassword;

    $data = array("userid"=>$userid,
                  "empid"=>$empid,
                  "invoices"=>$invoices,
                  "receivable"=>$receivable,
                  "reports"=>$reports,
                  "dashboard"=>$dashboard,
                  "clients"=>$clients,
                  "employees"=>$employees,
                  "branch"=>$branch,
                  "accessprivilege"=>$accessprivilege,
                  "username"=>$username,
                  "upassword"=>$upassword);

    if ($trans_type == 'New'){
      $answer = (new ControllerUserRights)->ctrAddUserRights($data);
      echo $answer;
    }else{
      $answer = (new ControllerUserRights)->ctrEditUserRights($data);
      echo $answer;
    }

  }
}

$inputUserRights = new userRightsEntry();

$inputUserRights -> trans_type = $_POST["trans_type"];
$inputUserRights -> userid = $_POST["userid"];
$inputUserRights -> empid = $_POST["empid"];
$inputUserRights -> invoices = $_POST["invoices"];
$inputUserRights -> receivable = $_POST["receivable"];
$inputUserRights -> reports = $_POST["reports"];
$inputUserRights -> dashboard = $_POST["dashboard"];
$inputUserRights -> clients = $_POST["clients"];
$inputUserRights -> employees = $_POST["employees"];
$inputUserRights -> branch = $_POST["branch"];
$inputUserRights -> accessprivilege = $_POST["accessprivilege"];
$inputUserRights -> username = $_POST["username"];
$inputUserRights -> upassword = $_POST["upassword"];

$inputUserRights -> userRightsEntrySave();