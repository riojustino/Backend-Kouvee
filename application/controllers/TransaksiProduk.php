<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class TransaksiProduk extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_trans = null){
        $transaksi_produk = $this->TransaksiProdukModel->getall($id_trans);
         if($transaksi_produk == null){
             $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
         }else{
             if($id_trans==null){
                 $this->response(['Data'=>$transaksi_produk,'Error'=>false],200);
             }
             else{
                 $this->response(['Data'=>$transaksi_produk,'Error'=>false],200);
             }
         }
     }
    public function index_post($id_trans = null){
        //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
        //,total_transaksi_produk,diskon_produk
        $validation = $this->form_validation;
        $rule = $this->TransaksiProdukModel->Rules();
        $validation->set_rules($rule);
        
        
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $TransaksiProduk = new dataTransaksiProduk();
                $TransaksiProduk->id_pegawai = $this->post('id_pegawai');
                $TransaksiProduk->peg_id_pegawai = $this->post('peg_id_pegawai');
                $TransaksiProduk->id_hewan = $this->post('id_hewan');
                $response = $this->TransaksiProdukModel->store($TransaksiProduk);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
    }
        
    
    public function index_put($id_trans){
        $TransaksiProduk = new dataTransaksiProduk();
        $TransaksiProduk->id_pegawai = $this->put('id_pegawai');
        $TransaksiProduk->peg_id_pegawai = $this->put('peg_id_pegawai');
        $TransaksiProduk->id_hewan = $this->put('id_hewan');
        $TransaksiProduk->diskon_produk = $this->put('diskon_produk');
        $TransaksiProduk->status_transaksi_produk = $this->put('status_transaksi_produk');
        $response = $this->TransaksiProdukModel->update($TransaksiProduk,$id_trans);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    
    public function index_delete($id_trans){
        $response = $this->TransaksiProdukModel->delete($id_trans);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataTransaksiProduk{
    public $peg_id_pegawai;
    public $id_pegawai;
    public $id_hewan;
    public $status_transaksi_produk;
    public $tgl_transaksi;
    public $subtotal_transaksi_produk;
    public $total_transaksi_produk;
    public $diskon_produk;
    
}
?>