<?php
require_once "../controllers/collectionreport.controller.php";
require_once "../models/collectionreport.model.php";

class AjaxCollectionReport{ 
   public $start_date;  
   public $end_date;
   public $customercode;
   public $paymode;
   public $reptype;

   public function ajaxDisplayCollectionReport(){
     $start_date = $this->start_date;
     $end_date = $this->end_date;
     $customercode = $this->customercode;
     $paymode = $this->paymode;
     $reptype = $this->reptype;

     $answer = (new ControllerCollection)->ctrShowCollectionReport($start_date, $end_date, $customercode, $paymode, $reptype);
     echo json_encode($answer);
   }
}

$collection_report = new AjaxCollectionReport();
$collection_report -> start_date = $_POST["start_date"];
$collection_report -> end_date = $_POST["end_date"];
$collection_report -> customercode = $_POST["customercode"];
$collection_report -> paymode = $_POST["paymode"];
$collection_report -> reptype = $_POST["reptype"];
$collection_report -> ajaxDisplayCollectionReport();