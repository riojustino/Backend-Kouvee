<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Auth extends RestController{
    public function __construct(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, DELETE, OPTIONS, POST, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
    }
    public function index_post(){
        $user = new UserData();
        $user->password = $this->post('password');
        $user->username = $this->post('username');

        if ($result = $this->PegawaiModel->verify($user)) {
            $response = ['Data' => $result];
            $this->response($response,202);
        } else {
            return  $this->response(['msg' => 'Invalid username or password!'], parent::HTTP_NOT_FOUND);
        }
    }
}

Class UserData{
    public $username;
	public $password;
}
?>