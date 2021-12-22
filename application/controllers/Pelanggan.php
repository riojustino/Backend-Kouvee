<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pelanggan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PelangganModel');
        $this->load->library('form_validation');
    }
    public function index_get($id_pelanggan = null){
        $pelanggan = $this->PelangganModel->getall($id_pelanggan);
        if($pelanggan == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_pelanggan==null){
                $this->response(['Data'=>$pelanggan,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$pelanggan,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->PelangganModel->rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $pelanggan = new dataPelanggan();
            $pelanggan->id_pegawai = $this->post('id_pegawai');
            $pelanggan->nama_pelanggan = $this->post('nama_pelanggan');
            $pelanggan->tgl_lahir_pelanggan = $this->post('tgl_lahir_pelanggan');
            $pelanggan->phone_pelanggan = $this->post('phone_pelanggan');
            $pelanggan->alamat_pelanggan = $this->post('alamat_pelanggan');
            $response = $this->PelangganModel->store($pelanggan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_pelanggan){
        $pelanggan = new dataPelanggan();
        $pelanggan->id_pegawai = $this->put('id_pegawai');
        $pelanggan->nama_pelanggan = $this->put('nama_pelanggan');
        $pelanggan->tgl_lahir_pelanggan = $this->put('tgl_lahir_pelanggan');
        $pelanggan->phone_pelanggan = $this->put('phone_pelanggan');
        $pelanggan->alamat_pelanggan = $this->put('alamat_pelanggan');
        $response = $this->PelangganModel->update($pelanggan,$id_pelanggan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function index_delete($id_pelanggan){
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->PelangganModel->delete($now,$id_pelanggan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}
}
class dataPelanggan{
    public $id_pegawai;
    public $nama_pelanggan;
    public $tgl_lahir_pelanggan;
    public $phone_pelanggan;
    public $alamat_pelanggan;
}
?>