<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/receivable.controller.php";
require_once "../../../models/receivable.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printClientPayment{
public $paynum;
public $postedby;

public function getClientPayment(){
  $paynum = $this->paynum;
  $pay_num = "DR # : " . $paynum;
  $payment = (new ControllerReceivable)->ctrGetClientPayment($paynum);
  $paidby = strtoupper($payment[0]['name']);
  $amount_paid = number_format($payment[0]['amount'],2);
  $pay_date = $payment[0]['paydate'];
  $paydate = substr($pay_date,5,2)."/".substr($pay_date,8,2)."/".substr($pay_date,0,4);

  $empid = "empid";
  $postedby = $this->postedby;
  $posted_by = (new ControllerEmployees)->ctrShowEmployees($empid, $postedby);
  if ($posted_by['mi']=='')
    $posted_by = $posted_by['fname'].' '.$posted_by['lname'];
  else  
    $posted_by = $posted_by['fname'].' '.$posted_by['mi'].'. '.$posted_by['lname'];

  $trans_title = "ACKNOWLEDGEMENT RECEIPT";

  $current_date = date("m/d/Y");
  $current_time = date("h:i A");
  $running_date = "Run Date: " . $current_date . ' | ' . $current_time;

//   $salesitems = (new ControllerSale)->ctrShowSaleItems($paynum);
//   $nRec = count($salesitems);

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  // $pdf->SetLeftMargin(20);
  // $pdf->AddPage();

  // $pdf->AddPage('L', 'LEGAL');  

  $pdf->AddPage();  /*short-size portrait*/
  $header = <<<EOF
  <table>
    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">BACOLOD LUIS PAINT CENTER</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:8px;">Capitol Shopping Center, Tindalo Ave., Brgy. Villamonte</td> 
    </tr>  

    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">$trans_title</td> 
    </tr>

    <tr>
      <td style="width:426px;"></td>
      <td style="width:50px;text-align:right;font-size:10px;">AR #:</td>
      <td style="width:63px;text-align:left;font-size:10px;">&nbsp;$paynum</td> 
    </tr>    

    <tr>
      <td style="width:40px;text-align:left;font-size:10px;">&nbsp;Paid by:</td>
      <td style="width:200px;text-align:left;font-size:10px;">&nbsp;$paidby</td> 
      <td style="width:194px;"></td>
      <td style="width:50px;text-align:right;font-size:10px;">&nbsp;Date Paid:</td>
      <td style="width:53px;text-align:left;font-size:10px;">&nbsp;$paydate</td> 
    </tr>

    <tr>
        <td style="width:3px;"></td>       
                                      
        <td style="border: 1px solid black;width:50px;text-align:left;font-size:8px;">&nbsp; Mode</td> 
        <td style="border: 1px solid black;width:50px;text-align:right;font-size:8px;">Amount &nbsp;&nbsp;</td>
        <td style="border: 1px solid black;width:108px;text-align:left;font-size:8px;">&nbsp; Bank</td>  
        <td style="border: 1px solid black;width:50px;text-align:left;font-size:8px;">&nbsp; Chk Date</td>
        <td style="border: 1px solid black;width:60px;text-align:left;font-size:8px;">&nbsp; Chk #</td>
        <td style="border: 1px solid black;width:50px;text-align:left;font-size:8px;">&nbsp; Chk Desc</td>   
        <td style="border: 1px solid black;width:65px;text-align:left;font-size:8px;">&nbsp; Inv #</td>    
        <td style="border: 1px solid black;width:50px;text-align:left;font-size:8px;">&nbsp; Inv Date</td>
        <td style="border: 1px solid black;width:50px;text-align:right;font-size:8px;">Amount &nbsp;&nbsp;</td>      
      </tr>                          
  </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $num_lines = 0;
  $first_rec = 1;
  foreach ($payment as $key => $value) {
    // $name = $value["name"];
    // $pay_date = $value["paydate"];
    // $paydate = substr($pay_date,5,2)."/".substr($pay_date,8,2)."/".substr($pay_date,0,4);
    if ($first_rec == 1){
        $paymode = $value["paymode"];
        $amount = number_format($value["amount"],2);
        $bankname = $value["bankname"];
        $check_date = $value["checkdate"];
        $checkdate = substr($check_date,5,2)."/".substr($check_date,8,2)."/".substr($check_date,0,4);
        if ($checkdate == '00/00/0000'){
          $checkdate = '';
        }
        $checknum = $value["checknum"];
        $checkdesc = $value["checkdesc"];
        $particulars = $value["particulars"];
    }else{
        $paymode = '';
        $amount = '';
        $bankname = '';
        $checkdate = '';
        $checknum = '';
        $checkdesc = '';
    }

    $invno = $value["invno"];
    $sale_date = $value["sdate"];
    $sdate = substr($sale_date,5,2)."/".substr($sale_date,8,2)."/".substr($sale_date,0,4);
    $amount_posted = number_format($value["amount_posted"],2);
    $first_rec = $first_rec + 1;

    $num_lines = $num_lines + 1;
    
    $content = <<<EOF
     <table style="border: none;">    
        <tr>
          <td style="width:3px;"></td>     

          <td style="width:50px;text-align:left;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;">&nbsp;$paymode</td>
          <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;">$amount</td>
          <td style="width:108px;text-align:left;font-size:8px;border-right: 1px solid black;">$bankname</td>
          <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;">$checkdate</td>
          <td style="width:60px;text-align:left;font-size:8px;border-right: 1px solid black;">&nbsp;$checknum</td>
          <td style="width:50px;text-align:left;font-size:8px;border-right: 1px solid black;">&nbsp;$checkdesc</td>
          <td style="width:65px;text-align:left;font-size:8px;border-right: 1px solid black;">&nbsp;$invno</td>
          <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;">$sdate</td>
          <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;">$amount_posted</td>
        </tr>                 
      </table>
EOF;
      $pdf->writeHTML($content, false, false, false, false, '');       
  }

//   $total_amount_resetted = number_format($total_amount,2);
  
// Extra blank lines
if ($num_lines < 10){
	$num_lines = 10 - $num_lines;
	for ($e = 0; $e <= $num_lines; $e++) {
	  $extra_lines = <<<EOF
	    <table style="border: none;">
	      <tr>
            <td style="width:3px;"></td>

            <td style="width:50px;text-align:left;font-size:8px;border-right: 1px solid black;border-left: 1px solid black;"></td>
            <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:108px;text-align:right;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:60px;text-align:left;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:50px;text-align:left;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:65px;text-align:left;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:50px;text-align:left;font-size:8px;border-right: 1px solid black;"></td>
            <td style="width:50px;text-align:right;font-size:8px;border-right: 1px solid black;"></td>
	      </tr>
	    </table>
EOF;
      $pdf->writeHTML($extra_lines, false, false, false, false, '');
    }	
}

$close_content = <<<EOF
  <table style="border: none;">
    <tr>
      <td style="width:3px;"></td>

      <td style="width:50px;text-align:left;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;</td>
      <td style="width:50px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:108px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:50px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:60px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:50px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:65px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:50px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:50px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
    </tr>        
  </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $footer = <<<EOF
    <table style="border: none;"> 
      <tr>  
        <td style="width:3px;"></td>
        <td style="width:433px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">TOTAL AMOUNT PAID</td>
        <td style="width:100px;text-align:right;font-size:11px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">$amount_paid</td>
      </tr>

      <tr>  
        <td style="width:3px;"></td>
      </tr>

      <tr>  
        <td style="width:3px;"></td>
        <td style="width:130px;text-align:center;font-size:8px;font-style:italic;">$running_date</td>
        <td style="width:100px;"></td>
        <td style="width:155px;font-size:10px;">Posted by:</td>
        <td style="width:165px;font-size:10px;">Conformed by:</td>
      </tr> 
      
      <tr>  
        <td style="width:3px;"></td>
      </tr>
      
      <tr>  
        <td style="width:237px;"></td>
        <td style="width:100px;border-bottom: 1px solid black;"></td>
        <td style="width:55px;"></td>
        <td style="width:100px;border-bottom: 1px solid black;"></td>
      </tr>      
    
      <tr>  
        <td style="width:237px;"></td>
        <td style="width:155px;text-align:left;font-size:11px;">$posted_by</td>
      </tr>      
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
     

    $pdf->Output('paymentdetails.pdf', 'I');
   }
  }  

  $printForm = new printClientPayment();
  $printForm -> paynum = $_GET["paynum"];
  $printForm -> postedby = $_GET["postedby"];
  $printForm -> getClientPayment();
?>