<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PegawaiModel extends CI_Model{
    private $table = 'pegawai';

    public $id_pegawai;
    public $nama_pegawai;
    public $tgl_lahir_pegawai;
    public $phone_pegawai;
    public $alamat_pegawai;
    public $jabatan;
    public $password;
    public $rule =[
        [
            'field' => 'nama_pegawai',
            'label' => 'nama_pegawai',
            'rules' => 'required|is_unique[pegawai.nama_pegawai]|alpha'
        ],
        [
            'field' => 'tgl_lahir_pegawai',
            'label' => 'tgl_lahir_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'phone_pegawai',
            'label' => 'phone_pegawai',
            'rules' => 'required|numeric|max_length[12]'
        ],
        [
            'field' => 'alamat_pegawai',
            'label' => 'alamat_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'jabatan',
            'label' => 'jabatan',
            'rules' => 'required'
        ],
        [
            'field' => 'password',
            'label' => 'password',
            'rules' => 'required|max_length[8]'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            return $this->db->get('pegawai')->result();
        }else{
            return $this->db->get_where('pegawai', [ 'id_pegawai' => $id] )->result();
        }
    }
    public function store($request) { 
		$this->nama_pegawai = $request->nama_pegawai; 
		$this->tgl_lahir_pegawai = $request->tgl_lahir_pegawai;
		$this->phone_pegawai = $request->phone_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->jabatan = $request->jabatan;
        $this->password = $request->password;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = ['nama_pegawai' => $request->nama_pegawai, 
        'tgl_lahir_pegawai' =>$request->tgl_lahir_pegawai,
        'phone_pegawai'=>$request->phone_pegawai,
        'alamat_pegawai'=>$request->alamat_pegawai,'
        jabatan'=>$request->jabatan];
        if($this->db->where('id_pegawai',$id)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_pegawai'=>$time
        ];
        if($this->db->where('id_pegawai',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Delete','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function verify($request)
    {
        // $this->db->select('ID_PEGAWAI,JABATAN')
        //     ->where('NAMA_PEGAWAI',$id);
        $user = $this->db->select('ID_PEGAWAI,JABATAN,PASSWORD')->get_where('pegawai', [ 'nama_pegawai' => $request->username] )->row_array();
        if (!empty($user)) {
            if(strcmp($request->password,$user['PASSWORD']) == 0){
                return $user;
            }
            else{
                return null;
            }
        } else {
            return null;
        }
    }

}
?>