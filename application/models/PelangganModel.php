<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PelangganModel extends CI_Model{
    private $table = 'pelanggan';

    public $id_pelanggan;
    public $id_pegawai;
    public $nama_pelanggan;
    public $tgl_lahir_pelanggan;
    public $phone_pelanggan;
    public $alamat_pelanggan;
    public $rule =[
        [
            'field' => 'id_pegawai',
            'label' => 'id_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_pelanggan',
            'label' => 'nama_pelanggan',
            'rules' => 'required|is_unique[pelanggan.nama_pelanggan]|alpha'
        ],
        [
            'field' => 'tgl_lahir_pelanggan',
            'label' => 'tgl_lahir_pelanggan',
            'rules' => 'required'
        ],
        [
            'field' => 'phone_pelanggan',
            'label' => 'phone_pelanggan',
            'rules' => 'required|numeric|exact_length[12]'
        ],
        [
            'field' => 'alamat_pelanggan',
            'label' => 'alamat_pelanggan',
            'rules' => 'required'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('PELANGGAN.ID_PELANGGAN,PELANGGAN.NAMA_PELANGGAN,PELANGGAN.PHONE_PELANGGAN,
            PELANGGAN.ALAMAT_PELANGGAN,PELANGGAN.TGL_LAHIR_PELANGGAN,PEGAWAI.NAMA_PEGAWAI,PELANGGAN.CREATE_AT_PELANGGAN,PELANGGAN.UPDATE_AT_PELANGGAN,PELANGGAN.DELETE_AT_PELANGGAN')
                    ->from('PELANGGAN')
                    ->join('PEGAWAI','PELANGGAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI');
            return $this->db->get()->result();
        }else{
            $this->db->select('PELANGGAN.ID_PELANGGAN,PELANGGAN.NAMA_PELANGGAN,PELANGGAN.PHONE_PELANGGAN,
            PELANGGAN.ALAMAT_PELANGGAN,PELANGGAN.TGL_LAHIR_PELANGGAN,PEGAWAI.NAMA_PEGAWAI,PELANGGAN.CREATE_AT_PELANGGAN,PELANGGAN.UPDATE_AT_PELANGGAN,PELANGGAN.DELETE_AT_PELANGGAN')
                    ->from('PELANGGAN')
                    ->join('PEGAWAI','PELANGGAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI')
                    ->like('ID_PELANGGAN',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_pegawai = $request->id_pegawai;
        $this->nama_pelanggan = $request->nama_pelanggan; 
		$this->tgl_lahir_pelanggan = $request->tgl_lahir_pelanggan;
		$this->phone_pelanggan = $request->phone_pelanggan;
        $this->alamat_pelanggan = $request->alamat_pelanggan; 
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = ['nama_pelanggan' => $request->nama_pelanggan,
        'id_pegawai'=>$request->id_pegawai, 
        'tgl_lahir_pelanggan' =>$request->tgl_lahir_pelanggan,
        'phone_pelanggan'=>$request->phone_pelanggan,
        'alamat_pelanggan'=>$request->alamat_pelanggan,];
        if($this->db->where('id_pelanggan',$id)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_pelanggan'=>$time
        ];
        if($this->db->where('id_pelanggan',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

}
?>