<?php
require_once "../controllers/sale.controller.php";
require_once "../models/sale.model.php";

class salesEntry{
  public $trans_type; 
  public $invno;
  public $receiptnum;
  public $sdate;
  public $salemode;
  public $customercode;
  public $status;
  public $postedby;
  public $amount;
  public $discount;
  public $netamount;
  public $remarks;

  public function salesEntrySave(){
    $trans_type = $this->trans_type;
    $invno = $this->invno;
    $receiptnum = $this->receiptnum;
  	$sdate = $this->sdate;
    $salemode = $this->salemode;
  	$customercode = $this->customercode;
    $status = $this->status;
  	$postedby = $this->postedby;
    $amount = $this->amount;
    $discount = $this->discount;
    $netamount = $this->netamount;
  	$remarks = $this->remarks;

    $data = array("invno"=>$invno,
                  "receiptnum"=>$receiptnum,
                  "sdate"=>$sdate,
                  "salemode"=>$salemode,
                  "customercode"=>$customercode,
                  "status"=>$status,
                  "postedby"=>$postedby,
                  "amount"=>$amount,
                  "discount"=>$discount,
                  "netamount"=>$netamount,
                  "remarks"=>$remarks);

    if ($trans_type == 'New'){
      $answer = (new ControllerSale)->ctrAddSale($data);
      echo $answer;
    }else{
      $answer = (new ControllerSale)->ctrEditSale($data);
      echo $answer;
    }

  }
}

$processSales = new salesEntry();

$processSales -> trans_type = $_POST["trans_type"];
$processSales -> invno = $_POST["invno"];
$processSales -> receiptnum = $_POST["receiptnum"];
$processSales -> sdate = $_POST["sdate"];
$processSales -> salemode = $_POST["salemode"];
$processSales -> customercode = $_POST["customercode"];
$processSales -> status = $_POST["status"];
$processSales -> postedby = $_POST["postedby"];
$processSales -> amount = $_POST["amount"];
$processSales -> discount = $_POST["discount"];
$processSales -> netamount = $_POST["netamount"];
$processSales -> remarks = $_POST["remarks"];

$processSales -> salesEntrySave();