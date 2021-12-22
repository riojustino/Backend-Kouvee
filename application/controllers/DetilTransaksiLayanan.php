<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class DetilTransaksiLayanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('DetilTransaksiLayananModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_detil = null){
        $detil_layanan = $this->DetilTransaksiLayananModel->getall($id_detil);
         if($detil_layanan == null){
            
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
         }else{
             if($id_detil==null){
                $this->response(['Data'=>$detil_layanan,'Error'=>false],200);
             }
             else{
                 $this->response(['Data'=>$detil_layanan,'Error'=>false],200);
             }
         }
     }
    public function index_post($id_detil = null){
        //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
        //,total_transaksi_produk,diskon_produk
        $validation = $this->form_validation;
        $rule = $this->DetilTransaksiLayananModel->Rules();
        $validation->set_rules($rule);
        
        if($id_detil==null){
            if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $DetilTransaksilayanan = new dataDetilTransaksiLayanan();
                $DetilTransaksilayanan->id_transaksi_layanan = $this->post('id_transaksi_layanan');
                $DetilTransaksilayanan->id_layanan = $this->post('id_layanan');
                $DetilTransaksilayanan->jumlah_detil_layanan = 1;
                $response = $this->DetilTransaksiLayananModel->store($DetilTransaksilayanan);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }
        else{
                $DetilTransaksilayanan = new dataDetilTransaksiLayanan();
                $DetilTransaksilayanan->id_layanan = $this->post('id_layanan');
                $DetilTransaksilayanan->jumlah_detil_layanan = 1;
                $response = $this->DetilTransaksiLayananModel->update($DetilTransaksilayanan,$id_detil);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    
    public function index_delete($id_detil){
        $response = $this->DetilTransaksiLayananModel->delete($id_detil);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataDetilTransaksiLayanan{
    public $id_transaksi_layanan;
    public $id_layanan;
    public $jumlah_detil_layanan;
    
}
?>