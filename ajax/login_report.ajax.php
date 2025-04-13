<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class AjaxLoginReport{ 
   public $start_date;
   public $end_date;

   public function ajaxDisplayLoginReport(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;

     $answer = (new ControllerUserRights)->ctrShowLoginReport($start_date, $end_date);
     echo json_encode($answer);
   }
}

$login_report = new AjaxLoginReport();
$login_report -> start_date = $_POST["start_date"];
$login_report -> end_date = $_POST["end_date"];
$login_report -> ajaxDisplayLoginReport();