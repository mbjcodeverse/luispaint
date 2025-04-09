<?php
require_once "../controllers/receivable.controller.php";
require_once "../models/receivable.model.php";

class receivableTransaction{
  public $trans_type; 
  public $paydate;
  public $paymode;
  public $checkdesc;
  public $bankcode;
  public $checknum;
  public $checkdate;
  public $amount;
  public $customercode;
  public $postedby;
  public $postdate;
  public $paymentlist;

  public function saveReceivableTransaction(){
    $trans_type = $this->trans_type;

    $paydate = $this->paydate;
  	$paymode = $this->paymode;
    $checkdesc = $this->checkdesc;
    $bankcode = $this->bankcode;
    $checknum = $this->checknum;
    $checkdate = $this->checkdate;
    $amount = $this->amount;
    $customercode = $this->customercode;
    $postedby = $this->postedby;
    $postdate = $this->postdate;
    $paymentlist = $this->paymentlist;

    $data = array("paydate"=>$paydate,
    	            "paymode"=>$paymode,
                  "checkdesc"=>$checkdesc,
                  "bankcode"=>$bankcode,
                  "checknum"=>$checknum,
                  "checkdate"=>$checkdate,
                  "amount"=>$amount,
                  "customercode"=>$customercode,
                  "postedby"=>$postedby,
                  "postdate"=>$postdate,
                  "paymentlist"=>$paymentlist);

    if ($trans_type == 'New'){
      $answer = (new ControllerReceivable)->ctrSaveReceivablePayment($data);
      echo $answer;
     }else{
      $answer = (new ControllerReceivable)->ctrEditReceivablePayment($data);
    }

  }
}

$processPayment = new receivableTransaction();
$processPayment -> trans_type = $_POST["trans_type"];

$processPayment -> paydate = $_POST["paydate"];
$processPayment -> paymode = $_POST["paymode"];
$processPayment -> checkdesc = $_POST["checkdesc"];
$processPayment -> bankcode = $_POST["bankcode"];
$processPayment -> checknum = $_POST["checknum"];
$processPayment -> checkdate = $_POST["checkdate"];
$processPayment -> amount = $_POST["amount"];
$processPayment -> customercode = $_POST["customercode"];
$processPayment -> postedby = $_POST["postedby"];
$processPayment -> postdate = $_POST["postdate"];
$processPayment -> paymentlist = $_POST["paymentlist"];

$processPayment -> saveReceivableTransaction();