<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/receivable.controller.php";
require_once "../../../models/receivable.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printStatementOfAccount{
public $paydate;
public $customercode;
public $paymode;
public $reptype;
public $generatedby;

public function getReceivableList(){
  $paydate = $this->paydate;
  $customercode = $this->customercode;

  $paymode = $this->paymode;
  $reptype = $this->reptype;

  $asofdate = "As of " . substr($paydate,5,2)."/".substr($paydate,8,2)."/".substr($paydate,0,4);
  $generatedby = $this->generatedby;

  $receivable = (new ControllerReceivable)->ctrShowReceivableReport($paydate, $customercode, $paymode, $reptype);

  $empid = "empid";
  $generatedby = $this->generatedby;
  $generated_by = (new ControllerEmployees)->ctrShowEmployees($empid, $generatedby);
  if ($generated_by['mi']=='')
    $printed_by = $generated_by['fname'].' '.$generated_by['lname'];
  else  
    $printed_by = $generated_by['fname'].' '.$generated_by['mi'].'. '.$generated_by['lname'];
  
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
  
  if ($reptype == "1"){
    $header = <<<EOF
    <table>
        <tr>
          <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">BACOLOD LUIS PAINT CENTER</td> 
        </tr>

        <tr>
          <td style="width:540px;text-align:center;font-size:8px;">Capitol Shopping Center, Tindalo Ave., Brgy. Villamonte</td> 
        </tr>  

        <tr>
          <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RECEIVABLE SUMMARY</td> 
        </tr>

        <tr>
          <td style="width:540px;text-align:center;font-size:10px;">$asofdate</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:22px;"></td> 
            <td style="border: 1px solid black;width:220px;text-align:left;font-size:10px;">&nbsp; Customer</td>  
            <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Amount &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Paid &nbsp;&nbsp;</td>
            <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Balance &nbsp;&nbsp;</td>         
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');
    
    $total_credit = 0.00;
    $total_paid = 0.00;
    $total_balance = 0.00;

    $overall_credit = 0.00;
    $overall_paid = 0.00;
    $overall_balance = 0.00; 
    
    $i = 0;
    foreach ($receivable as $key => $value){
        $name = $value["name"];    
        $invno = $value["invno"];
        $receiptnum = $value["receiptnum"];
        $netamount = number_format($value["netamount"],2);
        $amount = number_format($value["amount"],2);
        $balance = number_format($value["balance"],2);

        if ($i == 0){
            $prev_name = $value["name"];                
            $prev_invno = $value["invno"];              

            $total_credit = $total_credit + $value["netamount"];
            $total_paid = $total_paid + $value["amount"];
            $total_balance = $total_balance + $value["balance"];

            $overall_credit = $overall_credit + $value["netamount"];
            $overall_paid = $overall_paid + $value["amount"];
            $overall_balance = $overall_balance + $value["balance"];
        }else{
            $curr_name = $value["name"];                
            $curr_invno = $value["invno"];              
            if ($prev_name == $curr_name){
                if ($prev_invno != $curr_invno){
                    $total_credit = $total_credit + $value["netamount"];
                    $total_paid = $total_paid + $value["amount"];
                    $total_balance = $total_balance + $value["balance"];
        
                    $overall_credit = $overall_credit + $value["netamount"];
                    $overall_paid = $overall_paid + $value["amount"];
                    $overall_balance = $overall_balance + $value["balance"];
                }
                $prev_invno = $curr_invno;
            }else{
                $t_credit = number_format($total_credit,2);
                $t_paid = number_format($total_paid,2);
                $t_balance = number_format($total_balance,2);
                $content = <<<EOF
                <table style="border: none;">    
                    <tr>
                        <td style="width:22px;"></td> 
                        <td style="width:220px;text-align:left;font-size:10px;">&nbsp;$prev_name</td>     
                        <td style="width:90px;text-align:right;font-size:10px;">&nbsp;$t_credit</td>
                        <td style="width:90px;text-align:right;font-size:10px;">$t_paid</td>
                        <td style="width:90px;text-align:right;font-size:10px;">$t_balance</td>
                    </tr>                 
                </table>
            EOF;
                $pdf->writeHTML($content, false, false, false, false, '');  

                $total_credit = $value["netamount"];
                $total_paid = $value["amount"];
                $total_balance = $value["balance"];

                $overall_credit = $overall_credit + $value["netamount"];
                $overall_paid = $overall_paid + $value["amount"];
                $overall_balance = $overall_balance + $value["balance"];
            }
            $prev_name = $curr_name;                       
            $prev_invno = $curr_invno;            
        }
        $i = $i + 1;
    }

    if ($i > 0){
        $t_credit = number_format($total_credit,2);
        $t_paid = number_format($total_paid,2);
        $t_balance = number_format($total_balance,2);
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:22px;"></td> 
                <td style="width:220px;text-align:left;font-size:10px;">&nbsp;$prev_name</td>     
                <td style="width:90px;text-align:right;font-size:10px;">&nbsp;$t_credit</td>
                <td style="width:90px;text-align:right;font-size:10px;">$t_paid</td>
                <td style="width:90px;text-align:right;font-size:10px;">$t_balance</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');   
        
        $o_credit = number_format($overall_credit,2);
        $o_paid = number_format($overall_paid,2);
        $o_balance = number_format($overall_balance,2);
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:22px;"></td> 
                <td style="width:220px;text-align:right;font-size:10px;border:1px solid black;">OVERALL AMOUNT</td>     
                <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">&nbsp;$o_credit</td>
                <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">$o_paid</td>
                <td style="width:90px;text-align:right;font-size:10px;border:1px solid black;">$o_balance</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');

        $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:80px;"></td>
          </tr>
    
          <tr>  
            <td style="width:22px;"></td>
            <td style="width:370px;font-size:9px;">Date: $current_date</td>
            <td style="width:155px;font-size:10px;">Generated by:</td>
          </tr> 
          
          <tr>  
            <td style="width:22px;"></td>
            <td style="width:374px;"></td>
            <td style="width:95px;border-bottom: 1px solid black;"></td>
          </tr>      
        
          <tr>
            <td style="width:22px;"></td>  
            <td style="width:372px;"></td>
            <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
          </tr>      
        </table>
    EOF;
          $pdf->writeHTML($footer, false, false, false, false, '');        
    }
  // ----------------------------------------------------------------------------------------------------------------
  } elseif ($reptype == "2"){
  $header = <<<EOF
  <table>
    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">BACOLOD LUIS PAINT CENTER</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:10px;">Capitol Shopping Center, Tindalo Ave., Brgy. Villamonte</td> 
    </tr>  

    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">RECEIVABLE DETAILS</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:10px;">$asofdate</td> 
    </tr>   

    <tr>
      <td></td>
    </tr>    

    <tr>        
        <td style="border: 1px solid #680;width:170px;text-align:left;font-size:10px;">&nbsp; Customer</td>  
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

  $client_credit = 0.00;
  $client_paid = 0.00;
  $client_balance = 0.00;

  $prev_name = '';
  $curr_name = '';

  $prev_invno = '';
  $curr_invno = '';

  $i = 0;
  $total_amount = 0.00;
  foreach ($receivable as $key => $value){
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
        $prev_name = $value["name"];
        $client_credit = $client_credit + $value["netamount"];
        $client_paid = $client_paid + $value["amount"];
        $client_balance = $client_balance + $value["balance"];
    }else{
        $curr_name = $value["name"];
        if ($prev_name == $curr_name){
          $name = '';
          $client_credit = $client_credit + $value["netamount"];
          $client_paid = $client_paid + $value["amount"];
          $client_balance = $client_balance + $value["balance"];
        }else{
          // Display sub-total balance of each client
          $sub_credit = number_format($client_credit,2);
          $sub_paid = number_format($client_paid,2);
          $sub_balance = number_format($client_balance,2);
          $content = <<<EOF
          <table style="border: none;">    
              <tr>
                <td style="width:170px;border-bottom:1px solid black;"></td>   
                <td style="width:70px;border-bottom:1px solid black;"></td>
                <td style="width:85px;font-size:10px;border-right: 2px solid white;text-align:right;color:crimson;border-bottom:1px solid black;border-top:1px solid gray;border-right:1px solid black;">SUB-TOTAL</td>
                <td style="width:75px;text-align:right;font-size:10px;color:darkblue;border-bottom:1px solid black;border-top:1px solid gray;">$sub_credit</td>
                <td style="width:75px;text-align:right;font-size:10px;color:green;border-bottom:1px solid black;border-top:1px solid gray;">$sub_paid</td>
                <td style="width:75px;text-align:right;font-size:10px;color:firebrick;border-bottom:1px solid black;border-top:1px solid gray;">$sub_balance</td>
              </tr>                 
          </table>
      EOF;
          $pdf->writeHTML($content, false, false, false, false, '');     
          
          $client_credit = $value["netamount"];
          $client_paid = $value["amount"];
          $client_balance = $value["balance"];
        }
        $prev_name = $curr_name;
    } 

    if ($i == 0){
        $prev_invno = $value["invno"];
        $total_credit = $total_credit + $value["netamount"];
        $total_paid = $total_paid + $value["amount"];
        $total_balance = $total_balance + $value["balance"];

        $total_amount = $total_amount + $value["balance"];
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:170px;text-align:left;font-size:10px;">&nbsp;$name</td>     
                <td style="width:70px;text-align:left;font-size:10px;">&nbsp;$sdate</td>
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
                    <td style="width:170px;text-align:left;font-size:10px;">&nbsp;$name</td>     
                    <td style="width:70px;text-align:left;font-size:10px;">&nbsp;$sdate</td>
                    <td style="width:85px;text-align:left;font-size:10px;border-right: 1px solid black;">&nbsp;$receiptnum</td>
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
    $i = $i + 1;
  } // end of for loop

  if ($i > 0){
    $sub_credit = number_format($client_credit,2);
    $sub_paid = number_format($client_credit,2);
    $sub_balance = number_format($client_balance,2);
    $content = <<<EOF
    <table style="border: none;">    
        <tr>
          <td style="width:170px;border-bottom:1px solid black;"></td>   
          <td style="width:70px;border-bottom:1px solid black;"></td>
          <td style="width:85px;font-size:10px;border-right: 2px solid white;text-align:right;color:crimson;border-bottom:1px solid black;border-top:1px solid gray;border-right:1px solid black;">SUB-TOTAL</td>
          <td style="width:75px;text-align:right;font-size:10px;color:darkblue;border-bottom:1px solid black;border-top:1px solid gray;">$sub_credit</td>
          <td style="width:75px;text-align:right;font-size:10px;color:green;border-bottom:1px solid black;border-top:1px solid gray;">$sub_paid</td>
          <td style="width:75px;text-align:right;font-size:10px;color:firebrick;border-bottom:1px solid black;border-top:1px solid gray;">$sub_balance</td>
        </tr>                 
    </table>     
EOF;
    $pdf->writeHTML($content, false, false, false, false, ''); 

    $overall_credit = number_format($total_credit,2);
    $overall_paid = number_format($total_paid,2);
    $overall_balance = number_format($total_balance,2);
    $content = <<<EOF
    <table style="border: none;">    
        <tr>
          <td style="width:325px;font-size:10px;text-align:right;border-bottom:1px solid black;border-right:1px solid black;">OVERALL AMOUNT</td>   
          <td style="width:75px;text-align:right;font-size:10px;color:darkblue;border-bottom:1px solid black;border-top:1px solid gray;">$overall_credit</td>
          <td style="width:75px;text-align:right;font-size:10px;color:green;border-bottom:1px solid black;border-top:1px solid gray;">$overall_paid</td>
          <td style="width:75px;text-align:right;font-size:10px;color:firebrick;border-bottom:1px solid black;border-top:1px solid gray;">$overall_balance</td>
        </tr>                 
    </table>     
EOF;
    $pdf->writeHTML($content, false, false, false, false, '');

    $footer = <<<EOF
    <table style="border: none;"> 
      <tr>  
        <td style="width:80px;"></td>
      </tr>

      <tr>  
        <td style="width:410px;font-size:9px;">Date: $current_date</td>
        <td style="width:155px;font-size:10px;">Generated by:</td>
      </tr> 
      
      <tr>  
        <td style="width:414px;"></td>
        <td style="width:95px;border-bottom: 1px solid black;"></td>
      </tr>      
    
      <tr>  
        <td style="width:412px;"></td>
        <td style="width:155px;text-align:left;font-size:10px;">$printed_by</td>
      </tr>      
    </table>
EOF;
      $pdf->writeHTML($footer, false, false, false, false, '');     
  }

  $total_amount = number_format($total_amount,2);   
     
  } // end of if statement

    $pdf->Output('receivableprint.pdf', 'I');
   }  // end of getReceivableList
  }   // end of class

  $printReceivable = new printStatementOfAccount();
  $printReceivable -> paydate = $_GET["paydate"];
  $printReceivable -> customercode = $_GET["customercode"];
  $printReceivable -> paymode = $_GET["paymode"];
  $printReceivable -> reptype = $_GET["reptype"]; 
  $printReceivable -> generatedby = $_GET["generatedby"];   
  $printReceivable -> getReceivableList();
