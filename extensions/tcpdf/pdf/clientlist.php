<?php
session_start();

date_default_timezone_set('Asia/Manila');

require_once "../../../controllers/clients.controller.php";
require_once "../../../models/clients.model.php";

class printCustomerList{
public function getCustomerList(){
  $cust_list = (new ControllerClients)->ctrShowCustomerList();  
  $trans_title = "CUSTOMER LIST";

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/

  $pdf->AddPage();
  $header = <<<EOF
  <table>
    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">BACOLOD LUIS PAINT CENTER ENTERPRISES, INC.</td> 
    </tr>

    <tr>
      <td style="width:540px;text-align:center;font-size:7px;">Main: LN Bdlg., 2580 Tindalo Avenue, Capitol Shopping Center, Bacolod City</td> 
    </tr>  

    <tr>
      <td style="width:540px;text-align:center;font-size:1.2em;font-weight:bold;">$trans_title</td> 
    </tr>  

    <tr>
      <td></td>
    </tr>   

    <tr>
        <td style="width:15px;"></td>          
        <td style="border: 1px solid #680;width:210px;text-align:left;font-size:10px;">&nbsp; Name</td>  
        <td style="border: 1px solid #680;width:85px;text-align:left;font-size:10px;">&nbsp; Mobile</td>
        <td style="border: 1px solid #680;width:85px;text-align:left;font-size:10px;">&nbsp; Landline</td>
        <td style="border: 1px solid #680;width:130px;text-align:left;font-size:10px;">&nbsp; Contact Person</td>     
      </tr>                          
  </table>
EOF;
    $pdf->writeHTML($header, false, false, false, false, '');

  foreach ($cust_list as $key => $value) {
    $customercode = $value["customercode"];
    $name = $value["name"];
    $mobile = $value["mobile"];
    $landline = $value["landline"];
    $contactperson = $value["contactperson"];
        $content = <<<EOF
        <table style="border: none;">    
            <tr>
                <td style="width:15px;"></td>     
                <td style="width:210px;text-align:left;font-size:10px;">&nbsp;$name</td>
                <td style="width:85px;text-align:left;font-size:10px;">&nbsp;$mobile</td>
                <td style="width:85px;text-align:left;font-size:10px;">&nbsp;$landline</td>
                <td style="width:130px;text-align:left;font-size:10px;">&nbsp;$contactperson</td>
            </tr>                 
        </table>
    EOF;
        $pdf->writeHTML($content, false, false, false, false, '');  
  }

    $pdf->Output('clientlist.pdf', 'I');
   }
  }  

  $printSOA = new printCustomerList();  
  $printSOA -> getCustomerList();
