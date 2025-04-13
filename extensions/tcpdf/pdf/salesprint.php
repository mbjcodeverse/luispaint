<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/sale.controller.php";
require_once "../../../models/sale.model.php";

require_once "../../../controllers/employees.controller.php";
require_once "../../../models/employees.model.php";

class printSalesReport{
public $reptype;
public $customercode;
public $salemode;
public $status;
public $start_date;
public $end_date;
public $generatedby;

public function getSalesList(){
  $reptype = $this->reptype;
  $customercode = $this->customercode;

  $salemode = $this->salemode;
  $status = $this->status;
  $start_date = $this->start_date;
  $end_date = $this->end_date;

  // Date Label
  if ($start_date == $end_date){
    $sales_date = 'Date: '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4);
  }else{
    $sales_date = 'From '.substr($start_date,5,2)."/".substr($start_date,8,2)."/".substr($start_date,0,4).' To '.substr($end_date,5,2)."/".substr($end_date,8,2)."/".substr($end_date,0,4);
  }
  
  $generatedby = $this->generatedby;

  $sales = (new ControllerSale)->ctrPrintSales($reptype, $customercode, $salemode, $status, $start_date, $end_date);

  $empid = "empid";
  $generatedby = $this->generatedby;
  $generated_by = (new ControllerEmployees)->ctrShowEmployees($empid, $generatedby);
  if ($generated_by['mi']=='')
    $printed_by = $generated_by['fname'].' '.$generated_by['lname'];
  else  
    $printed_by = $generated_by['fname'].' '.$generated_by['mi'].'. '.$generated_by['lname'];
  
  $current_date = date("m/d/Y");

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
          <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">INVOICE LISTING</td> 
        </tr>

        <tr>
          <td style="width:540px;text-align:center;font-size:10px;">$sales_date</td> 
        </tr>   

        <tr>
          <td></td>
        </tr>    

        <tr>       
            <td style="width:22px;"></td> 
            <td style="border: 1px solid black;width:65px;text-align:left;font-size:10px;">&nbsp; Date</td> 
            <td style="border: 1px solid black;width:65px;text-align:left;font-size:10px;">&nbsp; Receipt #</td>   
            <td style="border: 1px solid black;width:220px;text-align:left;font-size:10px;">&nbsp; Customer</td> 
            <td style="border: 1px solid black;width:55px;text-align:left;font-size:10px;">&nbsp; Mode</td>    
            <td style="border: 1px solid black;width:90px;text-align:right;font-size:10px;">Amount &nbsp;&nbsp;</td>        
        </tr>                          
    </table>
    EOF;
        $pdf->writeHTML($header, false, false, false, false, '');
    
    $total_sales = 0.00;
    foreach ($sales as $key => $value){
        $name = $value["name"];    
        $invno = $value["invno"];
        $receiptnum = $value["receiptnum"];
        $salemode = $value["salemode"];
        $sdate = substr($value["sdate"],5,2)."/".substr($value["sdate"],8,2)."/".substr($value["sdate"],0,4);
        $netamount = number_format($value["netamount"],2);

        $total_sales = $total_sales + $value["netamount"];

        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:22px;"></td> 
                <td style="width:65px;text-align:left;font-size:10px;">&nbsp;$sdate</td>  
                <td style="width:65px;text-align:left;font-size:10px;">&nbsp;$receiptnum</td>  
                <td style="width:220px;text-align:left;font-size:10px;">&nbsp;$name</td>  
                <td style="width:55px;text-align:left;font-size:10px;">&nbsp;$salemode</td>   
                <td style="width:90px;text-align:right;font-size:10px;">&nbsp;$netamount</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');  
    }
    $total_sales = number_format($total_sales,2);
    $footer = <<<EOF
        <table style="border: none;"> 
          <tr>  
            <td style="width:22px;"></td> 
            <td style="width:385px;text-align:right;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">TOTAL SALES</td>
            <td style="width:110px;text-align:right;border-top:1px solid black;border-bottom:1px solid black;">$total_sales</td>
          </tr>

          <tr>  
            <td style="width:22px;"></td> 
          </tr>
    
          <tr>  
            <td style="width:22px;"></td>
            <td style="width:370px;font-size:9px;font-style:italic;">Date: $current_date</td>
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
  // ----------------------------------------------------------------------------------------------------------------
  }

    $pdf->Output('salesprint.pdf', 'I');
   }  // end of getSalesList
  }   // end of class

  $printSales = new printSalesReport();
  $printSales -> reptype = $_GET["reptype"];
  $printSales -> customercode = $_GET["customercode"];
  $printSales -> salemode = $_GET["salemode"];
  $printSales -> status = $_GET["status"]; 
  $printSales -> start_date = $_GET["start_date"];
  $printSales -> end_date = $_GET["end_date"];
  $printSales -> generatedby = $_GET["generatedby"];   
  $printSales -> getSalesList();
