<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Hewan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('HewanModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_hewan = null){
        $hewan = $this->HewanModel->getall($id_hewan);
        if($hewan == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_hewan==null){
                $this->response(['Data'=>$hewan,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$hewan,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->HewanModel->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $Hewan = new dataHewan();
            $Hewan->id_jenishewan = $this->post('id_jenishewan');
            $Hewan->id_pegawai = $this->post('id_pegawai');
            $Hewan->id_pelanggan = $this->post('id_pelanggan');
            $Hewan->nama_hewan = $this->post('nama_hewan');
            $Hewan->tgl_lahir_hewan = $this->post('tgl_lahir_hewan');
            $response = $this->HewanModel->store($Hewan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_hewan){
        // $id_hewan = $this->put('id_hewan');
        $Hewan = new dataHewan();
        $Hewan->id_jenishewan = $this->put('id_jenishewan');
        $Hewan->id_pegawai = $this->put('id_pegawai');
        $Hewan->id_pelanggan = $this->put('id_pelanggan');
        $Hewan->nama_hewan = $this->put('nama_hewan');
        $Hewan->tgl_lahir_hewan = $this->put('tgl_lahir_hewan');
        $response = $this->HewanModel->update($Hewan,$id_hewan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function index_delete($id_hewan){
        // $id_hewan = $this->put('id_hewan');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->HewanModel->delete($now,$id_hewan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}

}
class dataHewan{
    public $id_hewan;
    public $id_jenishewan;
    public $id_pegawai;
    public $id_pelanggan;
    public $nama_hewan;
    public $tgl_lahir_hewan;
}
?>