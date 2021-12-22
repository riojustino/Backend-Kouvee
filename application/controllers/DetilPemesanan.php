<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class DetilPemesanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetilPemesananModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_detil = null){
        $detil_produk = $this->DetilPemesananModel->getall($id_detil);
         if($detil_produk == null){
             $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
         }else{
             if($id_detil==null){
                 $this->response(['Data'=>$detil_produk,'Error'=>false],200);
             }
             else{
                 $this->response(['Data'=>$detil_produk,'Error'=>false],200);
             }
         }
     }
    public function index_post($id_detil = null){
        //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
        //,total_transaksi_produk,diskon_produk
        $validation = $this->form_validation;
        $rule = $this->DetilPemesananModel->Rules();
        $validation->set_rules($rule);
        
        if($id_detil==null){
            if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $DetilPemesanan = new dataDetilPemesanan();
                $DetilPemesanan->id_pemesanan = $this->post('id_pemesanan');
                $DetilPemesanan->id_produk = $this->post('id_produk');
                $DetilPemesanan->jumlah_pesanan = $this->post('jumlah_pesanan');
                $response = $this->DetilPemesananModel->store($DetilPemesanan);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }
        else{
                $DetilPemesanan = new dataDetilPemesanan();
                $DetilPemesanan->id_produk = $this->post('id_produk');
                $DetilPemesanan->jumlah_pesanan = $this->post('jumlah_pesanan');
                $response = $this->DetilPemesananModel->update($DetilPemesanan,$id_detil);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    
    public function index_delete($id_detil){
        $response = $this->DetilPemesananModel->delete($id_detil);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataDetilPemesanan{
    public $id_pemesanan;
    public $id_produk;
    public $jumlah_pesanan;
    
}
?>