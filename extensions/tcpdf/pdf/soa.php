<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/receivable.controller.php";
require_once "../../../models/receivable.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

require_once "../../../controllers/clients.controller.php";
require_once "../../../models/clients.model.php";

class printStatementOfAccount{
public $paydate;
public $customercode;
public $client_name;
public $paymode;
public $reptype;
public $generatedby;

public function getStatement(){
  $paydate = $this->paydate;
  $customercode = $this->customercode;

  $client_name = strtoupper($this->client_name);

  $paymode = $this->paymode;
  $reptype = $this->reptype;

  $asofdate = "As of " . substr($paydate,5,2)."/".substr($paydate,8,2)."/".substr($paydate,0,4);
  $generatedby = $this->generatedby;

  $soa = (new ControllerReceivable)->ctrShowReceivableReport($paydate, $customercode, $paymode, $reptype);

  $empid = "empid";
  $generatedby = $this->generatedby;
  $generated_by = (new ControllerEmployees)->ctrShowEmployees($empid, $generatedby);
  if ($generated_by['mi']=='')
    $printed_by = $generated_by['fname'].' '.$generated_by['lname'];
  else  
    $printed_by = $generated_by['fname'].' '.$generated_by['mi'].'. '.$generated_by['lname'];
  
  $trans_title = "STATEMENT OF ACCOUNT";
  $current_date = date("m/d/Y");

//   $salesitems = (new ControllerSale)->ctrShowSaleItems($resetcode);
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
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">BACOLOD LUIS PAINT CENTER ENTERPRISES, INC.</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:7px;">Main: LN Bdlg., 2580 Tindalo Avenue, Capitol Shopping Center, Bacolod City</td> 
    </tr> 
    
    <tr>
      <td style="width:540px;text-align:center;font-size:7px;">Tel. Nos. 435-2227 / 709-0264 / 434-1609 *FAX (034) 433-4956</td> 
    </tr> 

    <tr>
      <td style="width:540px;text-align:center;font-size:7px;">Branch: Door #2 LM Bldg., Gonzaga Street, Bacolod City</td> 
    </tr> 

    <tr>
      <td style="width:540px;text-align:center;font-size:7px;">Tel. Nos. 435-0301 / 707-8276 *FAX (034) 435-3108</td> 
    </tr> 

    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">$trans_title</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:10px;">$asofdate</td> 
    </tr>   

    <tr>
      <td></td>
    </tr> 
    
    <tr>
      <td style="width:80px;"></td>
      <td style="width:300px;text-align:left;font-size:10px;font-weight:bold;">To: $client_name</td> 
    </tr> 

    <tr>
        <td style="width:80px;"></td>
        <td style="width:300px;text-align:left;font-size:8px;font-style:italic;">Please process payment for the following invoices:</td> 
    </tr>    

    <tr>
        <td style="width:80px;"></td>          
        <td style="border: 1px solid #680;width:70px;text-align:left;font-size:10px;">&nbsp; Date</td>  
        <td style="border: 1px solid #680;width:85px;text-align:left;font-size:10px;">&nbsp; Receipt #</td>
        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:10px;">Amount &nbsp;&nbsp;</td>
        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:10px;">Paid &nbsp;&nbsp;</td>
        <td style="border: 1px solid #680;width:75px;text-align:right;font-size:10px;">Balance &nbsp;&nbsp;</td>         
      </tr>                          
  </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

// ------------------------------------------------------------
  $total_credit = 0.00;
  $total_paid = 0.00;
  $total_balance = 0.00;

  $prev_invno = '';
  $curr_invno = '';

  $i = 0;
  $num_lines = 0;
  $total_amount = 0.00;
  foreach ($soa as $key => $value) {
    $detail = $value["detail"];
    $customercode = $value["customercode"];
    $name = $value["name"];

    $sale_date = $value["sdate"];
    $sdate = substr($sale_date,5,2)."/".substr($sale_date,8,2)."/".substr($sale_date,0,4);

    $invno = $value["invno"];
    $receiptnum = $value["receiptnum"];
    $netamount = number_format($value["netamount"],2);
    $amount = number_format($value["amount"],2);
    $balance = number_format($value["balance"],2);

    if ($i == 0){
        $prev_invno = $value["invno"];
        $total_credit = $total_credit + $value["netamount"];
        $total_paid = $total_paid + $value["amount"];
        $total_balance = $total_balance + $value["balance"];

        $total_amount = $total_amount + $value["balance"];
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
            <td style="width:80px;"></td>     
            <td style="width:70px;text-align:left;font-size:10px;border-right: 1px solid black;">&nbsp;$sdate</td>
            <td style="width:85px;text-align:left;font-size:10px;border-right: 1px solid black;">&nbsp;$receiptnum</td>
            <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;">$netamount</td>
            <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;">$amount</td>
            <td style="width:75px;text-align:right;font-size:10px;">$balance</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');  
    }else{
        $curr_invno = $value["invno"];
        if ($prev_invno != $curr_invno){
            $total_credit = $total_credit + $value["netamount"];
            $total_paid = $total_paid + $value["amount"];
            $total_balance = $total_balance + $value["balance"];

            $total_amount = $total_amount + $value["balance"];
            $content = <<<EOF
            <table style="border: none;">    
                <tr>
                <td style="width:80px;"></td>     
                <td style="width:70px;text-align:left;font-size:10px;border-right: 1px solid black;">&nbsp;$sdate</td>
                <td style="width:85px;text-align:left;font-size:10px;border-right: 1px solid black;">&nbsp;$invno</td>
                <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;">$netamount</td>
                <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;">$amount</td>
                <td style="width:75px;text-align:right;font-size:10px;">$balance</td>
                </tr>                 
            </table>
        EOF;
            $pdf->writeHTML($content, false, false, false, false, '');             
        }
        $prev_invno = $curr_invno;
    }
    $num_lines = $num_lines + 1;      
  }

  $total_amount = number_format($total_amount,2);
  
// Extra blank lines
if ($num_lines < 10){
	$num_lines = 10 - $num_lines;
	for ($e = 0; $e <= $num_lines; $e++) {
	  $extra_lines = <<<EOF
	    <table style="border: none;">
	      <tr>
            <td style="width:80px;"></td>
            <td style="width:70px;text-align:right;font-size:11px;border-right: 1px solid black;"></td>
            <td style="width:85px;text-align:right;font-size:11px;border-right: 1px solid black;"></td>
            <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;"></td>
            <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;"></td>
            <td style="width:75px;text-align:right;font-size:11px;"></td>
	      </tr>
	    </table>
EOF;
      $pdf->writeHTML($extra_lines, false, false, false, false, '');
    }	
}

$close_content = <<<EOF
  <table style="border: none;">
    <tr>
      <td style="width:80px;"></td>
      <td style="width:70px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:85px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:75px;text-align:right;font-size:11px;border-right: 1px solid black;border-bottom: 1px solid black;"></td>
      <td style="width:75px;text-align:right;font-size:11px;border-bottom: 1px solid black;"></td>
    </tr>        
  </table>
EOF;
  $pdf->writeHTML($close_content, false, false, false, false, '');

  $footer = <<<EOF
    <table style="border: none;"> 
      <tr>  
        <td style="width:80px;"></td>
        <td style="width:305px;text-align:right;font-size:10px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;">TOTAL BALANCE</td>
        <td style="width:75px;text-align:right;font-size:10px;border-right: 1px solid black;border-left: 1px solid black;border-bottom: 1px solid black;font-weight:bold;">$total_amount</td>
      </tr>

      <tr>  
        <td style="width:78px;"></td>
        <td style="width:245px;font-size:9px;"></td>
        <td style="width:170px;font-size:8px;"></td>
      </tr> 

      <tr>  
        <td style="width:78px;"></td>
        <td style="width:245px;font-size:9px;">THANK YOU.</td>
        <td style="width:170px;font-size:8px;">Received by:</td>
      </tr> 
      
      <tr>  
        <td style="width:78px;"></td>
        <td style="width:249px;font-size:8px;">Make all checks payable to:</td>
        <td style="width:136px;border-bottom: 1px solid black;"></td>
      </tr>      
    
      <tr>  
        <td style="width:78px;"></td>
        <td style="width:290px;font-size:8px;">Bacolod Luis Paint Center Enterprises, Inc.</td>
        <td style="width:147px;text-align:left;font-size:10px;"></td>
      </tr>    
      
      <tr>  
        <td style="width:78px;"></td>
        <td style="width:282px;font-size:7px;">Date Printed:&nbsp;$current_date</td>
        <td style="width:155px;text-align:left;font-size:10px;"></td>
      </tr>  

      <tr>  
        <td style="width:78px;"></td>
        <td style="width:282px;font-size:7px;">Generated by:&nbsp;$printed_by</td>
      </tr>
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
     

    $pdf->Output('soa.pdf', 'I');
   }
  }  

  $printSOA = new printStatementOfAccount();
  $printSOA -> paydate = $_GET["paydate"];
  $printSOA -> customercode = $_GET["customercode"];
  $printSOA -> client_name = $_GET["client_name"];
  $printSOA -> paymode = $_GET["paymode"];
  $printSOA -> reptype = $_GET["reptype"]; 
  $printSOA -> generatedby = $_GET["generatedby"];   
  $printSOA -> getStatement();
