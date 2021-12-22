<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use chriskacerguis\RestServer\RestController;

use Dompdf\Dompdf;
use Dompdf\Options;
use chriskacerguis\RestServer\RestController;
// require_once 'dompdf/autoload.inc.php';

class CetakNota extends CI_Controller{
    public function __construct(){
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        // header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetilTransaksiLayananModel');
        $this->load->model('TransaksiLayananModel');
        $this->load->model('DetilPemesananModel');
        $this->load->model('PemesananModel');

        // $this->load->library('PdfGenerator');
    }
    public function printNota($id_detil)
	{
        $str = (explode("-",$id_detil));
        if($str[0]=="LY"){
            $type = "Jasa Layanan";
            $result['users'] = $this->DetilTransaksiLayananModel->getall($id_detil);
            $result['layanan'] = $this->TransaksiLayananModel->getall($id_detil);
        }else{
            $type = "Produk";
            $result['users'] = $this->DetilTransaksiprodukModel->getall($id_detil);
        }
        
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        $result['type']= $type;
        $result['id']= $id_detil;
        $html= $this->load->view('table_report.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            // $html = $this->load->view('table_report.html', $data, true);
            // $this->load->view('table_report.html',$data);
            $dompdf->loadHtml($html);

            // // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // // Render the HTML as PDF
            $dompdf->render();

            // // Output the generated PDF to Browser
            $dompdf->stream($id_detil,array('Attachment'=>1));
        
    }
    public function printPesanan($id_detil)
	{
        $awe['detil'] = $this->DetilPemesananModel->getall($id_detil);
        $awe['pemesanan'] = $this->PemesananModel->getall($id_detil);
        // echo $awe['pemesanan'];
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('d F Y');        
        $awe['tanggal']= $tgl;
        $awe['id']= $id_detil;
        $html= $this->load->view('pesanan.php',$awe,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            // $html = $this->load->view('table_report.html', $data, true);
            // $this->load->view('table_report.html',$data);
            $dompdf->loadHtml($html);

            // // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'potrait');

            // // Render the HTML as PDF
            $dompdf->render();

            // // Output the generated PDF to Browser
            $dompdf->stream($id_detil,array('Attachment'=>1));
        
    }
    
}
?>
