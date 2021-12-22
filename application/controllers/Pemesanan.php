<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pemesanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PemesananModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_trans = null){
        $transaksi_pemesanan = $this->PemesananModel->getall($id_trans);
         if($transaksi_pemesanan == null){
             $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
         }else{
             if($id_trans==null){
                 $this->response(['Data'=>$transaksi_pemesanan,'Error'=>false],200);
             }
             else{
                 $this->response(['Data'=>$transaksi_pemesanan,'Error'=>false],200);
             }
         }
     }
    public function index_post($id_trans = null){
        $validation = $this->form_validation;
        $rule = $this->PemesananModel->Rules();
        $validation->set_rules($rule);
        
        if($id_trans==null){
            $TransaksiPemesanan = new dataTransaksiPemesanan();
            $TransaksiPemesanan->id_supplier = $this->post('id_supplier');
            $response = $this->PemesananModel->store($TransaksiPemesanan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error'],'Data'=>$response['data']],200);
        }
        else{
            if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $TransaksiPemesanan = new dataTransaksiPemesanan();
                $TransaksiPemesanan->id_supplier = $this->post('id_supplier');
                $TransaksiPemesanan->status_pemesanan = $this->post('status_pemesanan');
                $response = $this->PemesananModel->update($TransaksiPemesanan,$id_trans);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }
    }
    
    public function index_delete($id_trans){
        $response = $this->PemesananModel->delete($id_trans);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataTransaksiPemesanan{
    public $status_pemesanan;
    public $tanggal_pemesanan;
    public $total;
    public $id_supplier;
}
?>