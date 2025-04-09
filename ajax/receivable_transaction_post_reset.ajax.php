<?php
require_once "../controllers/reset.controller.php";
require_once "../models/reset.model.php";

class resetEntry{ 
  public $branchcode;
  public $resetcode;
  public $resetby;

  public function resetEntrySave(){
    $branchcode = $this->branchcode;
    $resetcode = $this->resetcode;
    $resetby = $this->resetby;

    $answer = (new ControllerReset)->ctrPostReceivableReset($branchcode, $resetcode, $resetby);
  }
}

$processReset = new resetEntry();
$processReset -> branchcode = $_POST["branchcode"];
$processReset -> resetcode = $_POST["resetcode"];
$processReset -> resetby = $_POST["resetby"];
$processReset -> resetEntrySave();