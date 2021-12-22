<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisHewanModel extends CI_Model{
    private $table='jenis_hewan';

    public $id_jenishewan;
    public $id_pegawai;
    public $jenishewan;

    public $rule=[
        [
            'field'=>'id_pegawai',
            'label'=>'id_pegawai',
            'rules'=>'required'
        ],
        [
            'field'=>'jenishewan',
            'label'=>'jenishewan',
            'rules'=>'required|is_unique[jenis_hewan.jenishewan]|alpha'
        ]
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('jenis_hewan.ID_JENISHEWAN,jenis_hewan.JENISHEWAN,PEGAWAI.NAMA_PEGAWAI,jenis_hewan.CREATE_AT_JHEWAN,jenis_hewan.UPDATE_AT_JHEWAN,jenis_hewan.DELETE_AT_JHEWAN')
                    ->from('jenis_hewan')
                    ->join('pegawai','jenis_hewan.id_pegawai = pegawai.id_pegawai');
            return $this->db->get()->result();
        }else{
            $this->db->select('jenis_hewan.ID_JENISHEWAN,jenis_hewan.JENISHEWAN,PEGAWAI.NAMA_PEGAWAI,jenis_hewan.CREATE_AT_JHEWAN,jenis_hewan.UPDATE_AT_JHEWAN,jenis_hewan.DELETE_AT_JHEWAN')
                    ->from('jenis_hewan')
                    ->join('pegawai','jenis_hewan.id_pegawai = pegawai.id_pegawai')
                    ->like('id_jenishewan',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_pegawai = $request->id_pegawai;
        $this->jenishewan = $request->jenishewan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = [
        'jenishewan' => $request->jenishewan,
        'id_pegawai'=>$request->id_pegawai];
        if($this->db->where('id_jenishewan',$id)->update($this->table, $updateData)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_jhewan'=>$time
        ];
        if($this->db->where('id_jenishewan',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>