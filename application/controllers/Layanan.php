<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Layanan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LayananModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_layanan = null){
        $layanan = $this->LayananModel->getall($id_layanan);
        if($layanan == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_layanan==null){
                $this->response(['Data'=>$layanan,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$layanan,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->LayananModel->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $Layanan = new dataLayanan();
            $Layanan->id_ukuran = $this->post('id_ukuran');
            $Layanan->id_jenishewan = $this->post('id_jenishewan');
            $Layanan->id_pegawai = $this->post('id_pegawai');
            $Layanan->nama_layanan = $this->post('nama_layanan');
            $Layanan->harga_layanan = $this->post('harga_layanan');
            $response = $this->LayananModel->store($Layanan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_layanan){
        // $id_layanan = $this->put('id_layanan');
        $Layanan = new dataLayanan();
        $Layanan->id_ukuran = $this->put('id_ukuran');
        $Layanan->id_jenishewan = $this->put('id_jenishewan');
        $Layanan->id_pegawai = $this->put('id_pegawai');
        $Layanan->nama_layanan = $this->put('nama_layanan');
        $Layanan->harga_layanan = $this->put('harga_layanan');
        $response = $this->LayananModel->update($Layanan,$id_layanan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function index_delete($id_layanan){
        // $id_layanan = $this->put('id_layanan');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->LayananModel->delete($now,$id_layanan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}

}
class dataLayanan{
    public $id_layanan;
    public $id_ukuran;
    public $id_jenishewan;
    public $id_pegawai;
    public $nama_layanan;
    public $harga_layanan;
}
?>