<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class TransaksiLayanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiLayananModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_trans = null){
        $transaksi_layanan = $this->TransaksiLayananModel->getall($id_trans);
         if($transaksi_layanan == null){
             $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
         }else{
             if($id_trans==null){
                 $this->response(['Data'=>$transaksi_layanan,'Error'=>false],200);
             }
             else{
                 $this->response(['Data'=>$transaksi_layanan,'Error'=>false],200);
             }
         }
     }
    public function index_post($id_trans = null){
        //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
        //,total_transaksi_produk,diskon_produk
        $validation = $this->form_validation;
        $rule = $this->TransaksiLayananModel->Rules();
        $validation->set_rules($rule);
        
        if($id_trans==null){
            $TransaksiLayanan = new dataTransaksilayanan();
            $TransaksiLayanan->id_pegawai = $this->post('id_pegawai');
            $TransaksiLayanan->peg_id_pegawai = $this->post('peg_id_pegawai');
            $TransaksiLayanan->id_hewan = $this->post('id_hewan');
            $TransaksiLayanan->diskon_layanan = $this->post('diskon_layanan');
            $TransaksiLayanan->status_layanan = 0;
            $TransaksiLayanan->progres_layanan = 0;
            $response = $this->TransaksiLayananModel->store($TransaksiLayanan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error'],'Data'=>$response['data']],200);
        }
        else{
            if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
            }else{
                $TransaksiLayanan = new dataTransaksilayanan();
                $TransaksiLayanan->id_pegawai = $this->post('id_pegawai');
                $TransaksiLayanan->peg_id_pegawai = $this->post('peg_id_pegawai');
                $TransaksiLayanan->id_hewan = $this->post('id_hewan');
                $TransaksiLayanan->status_layanan = $this->post('status_layanan');
                $TransaksiLayanan->progres_layanan = $this->post('progres_layanan');
                $TransaksiLayanan->diskon_layanan = $this->post('diskon_layanan');
                $TransaksiLayanan->subtotal_transaksi_layanan = $this->post('subtotal_transaksi_layanan');
                $response = $this->TransaksiLayananModel->update($TransaksiLayanan,$id_trans);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }
    }
    
    public function index_delete($id_trans){
        $response = $this->TransaksiLayananModel->delete($id_trans);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }

}
class dataTransaksilayanan{
    public $peg_id_pegawai;
    public $id_pegawai;
    public $id_hewan;
    public $status_layanan;
    public $progres_layanan;
    public $tgl_transaksi_layanan;
    public $subtotal_transaksi_layanan;
    public $total_transaksi_layanan;
    public $diskon_layanan;
    
}
?>