<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LayananModel extends CI_Model{
    private $table='layanan';

    public $id_layanan;
    public $id_ukuran;
    public $id_jenishewan;
    public $id_pegawai;
    public $nama_layanan;
    public $harga_layanan;

    public $rule=[
        [
            'field'=>'id_ukuran',
            'label'=>'id_ukuran',
            'rules'=>'required'
        ],
        [
            'field'=>'id_pegawai',
            'label'=>'id_pegawai',
            'rules'=>'required'
        ],
        [
            'field'=>'nama_layanan',
            'label'=>'nama_layanan',
            'rules'=>'required|alpha'
        ],
        [
            'field'=>'harga_layanan',
            'label'=>'harga_layanan',
            'rules'=>'required|numeric'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('LAYANAN.ID_LAYANAN,CONCAT(LAYANAN.NAMA_LAYANAN," ",UKURAN.UKURAN," ",JENIS_HEWAN.JENISHEWAN) AS NAMA_LAYANAN,LAYANAN.HARGA_LAYANAN,PEGAWAI.NAMA_PEGAWAI,LAYANAN.CREATE_AT_LAYANAN,LAYANAN.UPDATE_AT_LAYANAN,LAYANAN.DELETE_AT_LAYANAN,LAYANAN.ID_JENISHEWAN,LAYANAN.ID_UKURAN')
                    ->from('LAYANAN')
                    ->join('UKURAN','LAYANAN.ID_UKURAN = UKURAN.ID_UKURAN')
                    ->join('JENIS_HEWAN','LAYANAN.ID_JENISHEWAN = JENIS_HEWAN.ID_JENISHEWAN')
                    ->join('PEGAWAI','LAYANAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI');
                    // ->where('delete_at_layanan','0000-00-00 00:00:00');
            return $this->db->get()->result();
        }else{
            $this->db->select('LAYANAN.ID_LAYANAN,UKURAN.UKURAN,JENIS_HEWAN.JENISHEWAN,LAYANAN.NAMA_LAYANAN,LAYANAN.HARGA_LAYANAN,PEGAWAI.NAMA_PEGAWAI,LAYANAN.CREATE_AT_LAYANAN,LAYANAN.UPDATE_AT_LAYANAN,LAYANAN.DELETE_AT_LAYANAN')
                    ->from('LAYANAN')
                    ->join('UKURAN','LAYANAN.ID_UKURAN = UKURAN.ID_UKURAN')
                    ->join('JENIS_HEWAN','LAYANAN.ID_JENISHEWAN = JENIS_HEWAN.ID_JENISHEWAN')
                    ->join('PEGAWAI','LAYANAN.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI')
                    ->like('ID_LAYANAN',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_pegawai = $request->id_pegawai;
        $this->id_jenishewan = $request->id_jenishewan;
        $this->id_ukuran = $request->id_ukuran;
        $this->nama_layanan = $request->nama_layanan;
        $this->harga_layanan = $request->harga_layanan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil Menbahkan Data','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = [
        'id_ukuran'=>$request->id_ukuran,
        'id_jenishewan'=>$request->id_jenishewan,
        'id_pegawai'=>$request->id_pegawai,
        'nama_layanan' => $request->nama_layanan,
        'harga_layanan'=>$request->harga_layanan];
        if($this->db->where('id_layanan',$id)->update($this->table, $updateData)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_layanan'=>$time
        ];
        if($this->db->where('id_layanan',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>