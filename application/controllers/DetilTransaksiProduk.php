<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class DetilTransaksiProduk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetilTransaksiProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_detil = null){
        $detil_produk = $this->DetilTransaksiProdukModel->getall($id_detil);
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
        $rule = $this->DetilTransaksiProdukModel->Rules();
        $validation->set_rules($rule);
        
        if($id_detil==null){
            if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $DetilTransaksiProduk = new dataDetilTransaksiProduk();
                $DetilTransaksiProduk->id_transaksi_produk = $this->post('id_transaksi_produk');
                $DetilTransaksiProduk->id_produk = $this->post('id_produk');
                $DetilTransaksiProduk->jumlah_produk = $this->post('jumlah_produk');
                $response = $this->DetilTransaksiProdukModel->store($DetilTransaksiProduk);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }
    }
    
    public function index_put($id_detil){
        $DetilTransaksiProduk = new dataDetilTransaksiProduk();
        $DetilTransaksiProduk->id_produk = $this->put('id_produk');
        $DetilTransaksiProduk->jumlah_produk = $this->put('jumlah_produk');
        $response = $this->DetilTransaksiProdukModel->update($DetilTransaksiProduk,$id_detil);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    
    public function index_delete($id_detil){
        $response = $this->DetilTransaksiProdukModel->delete($id_detil);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataDetilTransaksiProduk{
    public $id_transaksi_produk;
    public $id_produk;
    public $jumlah_produk;
    
}
?>