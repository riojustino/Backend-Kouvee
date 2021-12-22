<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Pegawai extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
    }
    public function index_get($id_pegawai = null){
        $pegawai = $this->PegawaiModel->getall($id_pegawai);
        if($pegawai == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_pegawai==null){
                $this->response(['Data'=>$pegawai,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$pegawai,'Error'=>false],200);
            }
        }
    }
    public function index_post($id_pegawai = null){
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $pegawai = new dataPegawai();
            $pegawai->nama_pegawai = $this->post('nama_pegawai');
            $pegawai->tgl_lahir_pegawai = $this->post('tgl_lahir_pegawai');
            $pegawai->phone_pegawai = $this->post('phone_pegawai');
            $pegawai->alamat_pegawai = $this->post('alamat_pegawai');
            $pegawai->jabatan = $this->post('jabatan');
            $pegawai->password = $this->post('password');
            $response = $this->PegawaiModel->store($pegawai);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_pegawai){
        $pegawai = new dataPegawai();
        $pegawai->nama_pegawai = $this->put('nama_pegawai');
        $pegawai->tgl_lahir_pegawai = $this->put('tgl_lahir_pegawai');
        $pegawai->phone_pegawai = $this->put('phone_pegawai');
        $pegawai->alamat_pegawai = $this->put('alamat_pegawai');
        $pegawai->jabatan = $this->put('jabatan');
        $response = $this->PegawaiModel->update($pegawai,$id_pegawai);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function index_delete($id_pegawai){
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->PegawaiModel->delete($now,$id_pegawai);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function returnData($msg,$error,$sts){
        $response['Message']=$msg;
        $response['Error']=$error;
        return $this->response($response,$sts);
	}
}
class dataPegawai{
    public $id_pegawai;
    public $nama_pegawai;
    public $tgl_lahir_pegawai;
    public $phone_pegawai;
    public $alamat_pegawai;
    public $jabatan;
    public $password;
}
?>