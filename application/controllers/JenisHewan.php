<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class JenisHewan extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('JenisHewanModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_jenishewan = null){
        $jenishewan = $this->JenisHewanModel->getall($id_jenishewan);
        if($jenishewan == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_jenishewan==null){
                $this->response(['Data'=>$jenishewan,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$jenishewan,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->JenisHewanModel->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $JenisHewan = new dataJenisHewan();
            $JenisHewan->id_pegawai = $this->post('id_pegawai');
            $JenisHewan->jenishewan = $this->post('jenishewan');
            $response = $this->JenisHewanModel->store($JenisHewan);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_jenishewan){
        // $id_jenishewan = $this->put('id_jenishewan');
        $JenisHewan = new dataJenisHewan();
        $JenisHewan->id_pegawai = $this->put('id_pegawai');
        $JenisHewan->jenishewan = $this->put('jenishewan');
        $response = $this->JenisHewanModel->update($JenisHewan,$id_jenishewan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function index_delete($id_jenishewan){
        // $id_jenishewan = $this->put('id_jenishewan');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->JenisHewanModel->delete($now,$id_jenishewan);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}

}
class dataJenisHewan{
    public $id_jenishewan;
    public $id_pegawai;
    public $jenishewan;
}
?>