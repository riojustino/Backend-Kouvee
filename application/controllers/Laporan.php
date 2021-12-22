<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use chriskacerguis\RestServer\RestController;

use Dompdf\Dompdf;
use Dompdf\Options;
// use chriskacerguis\RestServer\RestController;
// require_once 'dompdf/autoload.inc.php';

class Laporan extends CI_Controller{
    public function __construct(){
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        // header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('DetilTransaksiLayananModel');
        $this->load->model('TransaksiLayananModel');
        $this->load->model('LaporanModel');
        $this->load->model('DetilTransaksiProdukModel');
        $this->load->model('TransaksiProdukModel');
        // $this->load->library('PdfGenerator');
    }
    
    public function printLaporanLayananTahun($year)
	{   
        $result['layanan'] = $this->LaporanModel->getDataLayananTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_layanan_terlaris_tahun.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Layanan Terlaris Tahun ' .$year ,array('Attachment'=>1));
        
	}
	
	public function printLaporanProdukTahun($year)
	{   
	    
        $result['produk'] = $this->LaporanModel->getDataProdukTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_produk_terlaris_tahun.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Produk Terlaris Tahun ' .$year ,array('Attachment'=>1));
        
	}
	
	public function printLaporanPendapatanTahun($year)
	{   
        $result['pendapatan'] = $this->LaporanModel->getDataPendapatanTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pendapatan_tahunan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pendapatan Tahun ' .$year ,array('Attachment'=>1));
        
	}
	
	public function printLaporanPengadaanTahun($year)
	{   
        $result['pengadaan'] = $this->LaporanModel->getDataPengadaanTahun($year);
        $result['year'] = $year;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pengadaan_tahunan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pengadaan Tahun ' .$year ,array('Attachment'=>1));
        
    }
    public function printLaporanPengadaanBulan($year,$month)
	{   
        $result['pengadaan'] = $this->LaporanModel->getDataPengadaanBulan($year,$month);
        $result['year'] = $year;
        $result['month'] = $month;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pengadaan_bulanan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pengadaan Bulanan ' .$month.' '.$year ,array('Attachment'=>1));
        
	}
	
	public function printLaporanPendapatanBulan($year,$month)
	{   
        $result['produk'] = $this->LaporanModel->getDataPendapatanProdukBulan($year,$month);
        $result['layanan'] = $this->LaporanModel->getDataPendapatanLayananBulan($year,$month);
        $result['year'] = $year;
        $result['month'] = $month;
        $tgl = date('d F Y H:i');        
        $result['tanggal']= $tgl;
        date_default_timezone_set('Asia/Jakarta');
        $html= $this->load->view('table_pendapatan_bulanan.php',$result,true);
            $dompdf = new Dompdf();
            $dompdf->set_option('isRemoteEnabled', TRUE);
            
            $dompdf->loadHtml($html);

            
            $dompdf->setPaper('A4', 'potrait');

            
            $dompdf->render();

            $dompdf->stream('Laporan Pendapatan Bulanan ' .$month.' '.$year ,array('Attachment'=>1));
        
	}
	
	
}
?>