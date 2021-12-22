<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HewanModel extends CI_Model{
    private $table='hewan';

    public $id_hewan;
    public $id_jenishewan;
    public $id_pegawai;
    public $id_pelanggan;
    public $nama_hewan;
    public $tgl_lahir_hewan;

    public $rule=[
        [
            'field'=>'id_jenishewan',
            'label'=>'id_jenishewan',
            'rules'=>'required'
        ],
        [
            'field'=>'id_pegawai',
            'label'=>'id_pegawai',
            'rules'=>'required'
        ],
        [
            'field'=>'id_pelanggan',
            'label'=>'id_pelanggan',
            'rules'=>'required'
        ],
        [
            'field'=>'nama_hewan',
            'label'=>'nama_hewan',
            'rules'=>'required'
        ],
        [
            'field'=>'tgl_lahir_hewan',
            'label'=>'tgl_lahir_hewan',
            'rules'=>'required'
        ]
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('HEWAN.ID_HEWAN,JENIS_HEWAN.JENISHEWAN,HEWAN.NAMA_HEWAN,
            HEWAN.TGL_LAHIR_HEWAN,PELANGGAN.NAMA_PELANGGAN,PEGAWAI.NAMA_PEGAWAI,HEWAN.CREATE_AT_HEWAN,HEWAN.UPDATE_AT_HEWAN,HEWAN.DELETE_AT_HEWAN,HEWAN.ID_JENISHEWAN,HEWAN .ID_PELANGGAN')
                    ->from('HEWAN')
                    ->join('PELANGGAN','HEWAN.ID_PELANGGAN = PELANGGAN.ID_PELANGGAN')
                    ->join('JENIS_HEWAN','HEWAN.ID_JENISHEWAN = JENIS_HEWAN.ID_JENISHEWAN')
                    ->join('PEGAWAI','HEWAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI');
            return $this->db->get()->result();
        }else{
            $this->db->select('HEWAN.ID_HEWAN,JENIS_HEWAN.JENISHEWAN,HEWAN.NAMA_HEWAN,
            HEWAN.TGL_LAHIR_HEWAN,PELANGGAN.NAMA_PELANGGAN,PEGAWAI.NAMA_PEGAWAI,HEWAN.CREATE_AT_HEWAN,HEWAN.UPDATE_AT_HEWAN,HEWAN.DELETE_AT_HEWAN')
                    ->from('HEWAN')
                    ->join('PELANGGAN','HEWAN.ID_PELANGGAN = PELANGGAN.ID_PELANGGAN')
                    ->join('JENIS_HEWAN','HEWAN.ID_JENISHEWAN = JENIS_HEWAN.ID_JENISHEWAN')
                    ->join('PEGAWAI','HEWAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI')
                    ->like('id_hewan',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_jenishewan = $request->id_jenishewan; 
		$this->id_pegawai = $request->id_pegawai;
		$this->id_pelanggan = $request->id_pelanggan;
        $this->nama_hewan = $request->nama_hewan;
        $this->tgl_lahir_hewan = $request->tgl_lahir_hewan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = [
        'nama_hewan' => $request->nama_hewan,
        'id_jenishewan' =>$request->id_jenishewan, 
        'id_pegawai'=>$request->id_pegawai,
        'id_pelanggan'=>$request->id_pelanggan,
        'nama_hewan' =>$request->nama_hewan,
        'tgl_lahir_hewan'=>$request->tgl_lahir_hewan];
        if($this->db->where('id_hewan',$id)->update($this->table, $updateData)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_hewan'=>$time
        ];
        if($this->db->where('id_hewan',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>