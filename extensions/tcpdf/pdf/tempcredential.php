<?php
class printTempCredential{
public $username;
public $upassword;

public function getTempCredential(){
  $username = $this->username;   
  $upassword = $this->upassword;

  require_once('tcpdf_include.php');
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(180,200), true, 'UTF-8', false);
  $pdf->startPageGroup();
  $pdf->setPrintHeader(false);	/*remove line on top of the page*/
  $pdf->setPrintFooter(false);  /*remove line at the bottom of the page*/
  // $pdf->AddPage();

  $width = $pdf->pixelsToUnits(230); 
  $height = $pdf->pixelsToUnits(600);

  $resolution= array($width, $height);
  $pdf->SetMargins(8, 4, 8, true);

  $pdf->AddPage('P', $resolution);

  $header = <<<EOF
    <table>
      <tr>
        <td style="width:180px;text-align:center;font-size:11px;">Username: $username</td> 
      </tr> 
      <tr>
        <td style="width:180px;text-align:center;font-size:11px;">Password: $upassword</td> 
      </tr>               
    </table>
EOF;
  $pdf->writeHTML($header, false, false, false, false, '');
 
  $pdf->Output('tempcredential.pdf', 'I');
 }
}

$temp_credential = new printTempCredential();
$temp_credential -> username = $_GET["username"];
$temp_credential -> upassword = $_GET["upassword"];
$temp_credential -> getTempCredential();
?>