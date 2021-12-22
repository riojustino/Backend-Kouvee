<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Produk extends RestController{
    public function __construct(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, DELETE, OPTIONS, POST, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
    }

    public function index_get($id_produk = null){
        $produk = $this->ProdukModel->getall($id_produk);
        if($produk == null){
            $this->response(['Message'=>'Data Tidak Ditemukan','Error'=>true],404);
        }else{
            if($id_produk==null){
                $this->response(['Data'=>$produk,'Error'=>false],200);
            }
            else{
                $this->response(['Data'=>$produk,'Error'=>false],200);
            }
        }
    }
    public function index_post($id_produk = null){
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->Rules();
        $validation->set_rules($rule);
        if($id_produk==null){
            if (!$validation->run()) {
                return $this->returnData($this->form_validation->error_array(), true,400);
            }
            else{
                $Produk = new dataProduk();
                $Produk->id_pegawai = $this->post('id_pegawai');
                $Produk->nama_produk = $this->post('nama_produk');
                $Produk->stock = $this->post('stock');
                $Produk->min_stock = $this->post('min_stock');
                $Produk->satuan_produk = $this->post('satuan_produk');
                $Produk->harga_beli = $this->post('harga_beli');
                $Produk->harga_jual = $this->post('harga_jual');
                $Produk->gambar = $this->_uploadImage();
                $response = $this->ProdukModel->store($Produk);
                $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
            }
        }else{
            $Produk = new dataProduk();
            $Produk->id_pegawai = $this->post('id_pegawai');
            $Produk->nama_produk = $this->post('nama_produk');
            $Produk->stock = $this->post('stock');
            $Produk->min_stock = $this->post('min_stock');
            $Produk->satuan_produk = $this->post('satuan_produk');
            $Produk->harga_beli = $this->post('harga_beli');
            $Produk->harga_jual = $this->post('harga_jual');
            if (!empty($_FILES['gambar']['name'])) {
                $Produk->gambar = $this->_uploadImage();
            } else {
                $Produk->gambar = $this->post('gambar');
            }
            $response = $this->ProdukModel->update($Produk,$id_produk);
            $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
        }
    }
    
    public function index_delete($id_produk){
        // $id_produk = $this->put('id_produk');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        $response = $this->ProdukModel->delete($now,$id_produk);
        $this->response(['Message'=>$response['msg'],'Error'=>$response['error']],200);
    }

    public function returnData($msg,$error,$sts){
        $response['message']=$msg;
        $response['error']=$error;
        return $this->response($response,$sts);
    }
    private function _uploadImage()
    {
        $config['upload_path']          = './upload/produk/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->post('nama_produk');
        $config['overwrite']			= true;
        $config['max_size']             = 1024;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data("file_name");
        }
        
        return "default.jpg";
    }

}
class dataProduk{
    public $id_produk;
    public $id_pegawai;
    public $nama_produk;
    public $stock;
    public $min_stock;
    public $satuan_produk;
    public $harga_beli;
    public $harga_jual;
    public $gambar;
}
?>