<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Supplier extends RestController{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('SupplierModel');
        $this->load->library('form_validation');
    }
    public function index_get($id_supplier = null){
        $supplier = $this->SupplierModel->getall($id_supplier);
        if($supplier == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_supplier==null){
                $this->response(['Data'=>$supplier,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$supplier,'Error'=>false],200);
            }
        }
    }
    public function index_post(){
        $validation = $this->form_validation;
        $rule = $this->SupplierModel->rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true,400);
        }else{
            $supplier = new dataSupplier();
            $supplier->id_pegawai = $this->post('id_pegawai');
            $supplier->nama_supplier = $this->post('nama_supplier');
            $supplier->phone_supplier = $this->post('phone_supplier');
            $supplier->alamat_supplier = $this->post('alamat_supplier');
            $response = $this->SupplierModel->store($supplier);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    public function index_put($id_supplier){
        $supplier = new dataSupplier();
        $supplier->id_pegawai = $this->put('id_pegawai');
        $supplier->nama_supplier = $this->put('nama_supplier');
        $supplier->phone_supplier = $this->put('phone_supplier');
        $supplier->alamat_supplier = $this->put('alamat_supplier');
        $response = $this->SupplierModel->update($supplier,$id_supplier);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function index_delete($id_supplier){
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->SupplierModel->delete($now,$id_supplier);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }
    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
	}
}
class dataSupplier{
    public $id_pegawai;
    public $nama_supplier;
    public $phone_supplier;
    public $alamat_supplier;
}
?>