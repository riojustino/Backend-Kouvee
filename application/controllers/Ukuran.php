<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Ukuran extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('UkuranModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_ukuran = null){
        $ukuran = $this->UkuranModel->getall($id_ukuran);
        if($ukuran == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_ukuran==null){
                $this->response(['Data'=>$ukuran,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$ukuran,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->UkuranModel->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $Ukuran = new dataUkuran();
            $Ukuran->id_pegawai = $this->post('id_pegawai');
            $Ukuran->ukuran = $this->post('ukuran');
            $response = $this->UkuranModel->store($Ukuran);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_ukuran){
        // $id_ukuran = $this->put('id_ukuran');
        $Ukuran = new dataUkuran();
        $Ukuran->id_pegawai = $this->put('id_pegawai');
        $Ukuran->ukuran = $this->put('ukuran');
        $response = $this->UkuranModel->update($Ukuran,$id_ukuran);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function index_delete($id_ukuran){
        // $id_ukuran = $this->put('id_ukuran');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->UkuranModel->delete($now,$id_ukuran);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}

}
class dataUkuran{
    public $id_ukuran;
    public $id_pegawai;
    public $ukuran;
}
?>