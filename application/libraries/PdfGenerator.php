<?php
// require_once 'dompdf/autoload.inc.php';
// use Dompdf\Dompdf;
class PdfGenerator{
  function generate($html,$filename)
  {
    $dompdf = new Dompdf();
    $dompdf->loadHtml('hello world');

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream($filename,array('Attachment'=>0));
  }
}
?>