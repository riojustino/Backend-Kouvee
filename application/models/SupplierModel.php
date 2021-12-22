<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierModel extends CI_Model{
    private $table = 'supplier';

    public $id_supplier;
    public $id_pegawai;
    public $nama_supplier;
    public $phone_supplier;
    public $alamat_supplier;
    public $rule =[
        [
            'field' => 'id_pegawai',
            'label' => 'id_pegawai',
            'rules' => 'required'
        ],
        [
            'field' => 'nama_supplier',
            'label' => 'nama_supplier',
            'rules' => 'required|is_unique[supplier.nama_supplier]|alpha'
        ],
        [
            'field' => 'phone_supplier',
            'label' => 'phone_supplier',
            'rules' => 'required|numeric|exact_length[12]'
        ],
        [
            'field' => 'alamat_supplier',
            'label' => 'alamat_supplier',
            'rules' => 'required'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('SUPPLIER.ID_SUPPLIER,SUPPLIER.NAMA_SUPPLIER,SUPPLIER.ALAMAT_SUPPLIER,SUPPLIER.PHONE_SUPPLIER,PEGAWAI.NAMA_PEGAWAI,SUPPLIER.CREATE_AT_SUPPLIER,SUPPLIER.UPDATE_AT_SUPPLIER,SUPPLIER.DELETE_AT_SUPPLIER')
                    ->from('SUPPLIER')
                    ->join('PEGAWAI','SUPPLIER.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI');
            return $this->db->get()->result();
        }else{
            $this->db->select('SUPPLIER.ID_SUPPLIER,SUPPLIER.NAMA_SUPPLIER,SUPPLIER.ALAMAT_SUPPLIER,SUPPLIER.PHONE_SUPPLIER,PEGAWAI.NAMA_PEGAWAI,SUPPLIER.CREATE_AT_SUPPLIER,SUPPLIER.UPDATE_AT_SUPPLIER,SUPPLIER.DELETE_AT_SUPPLIER')
                    ->from('SUPPLIER')
                    ->join('PEGAWAI','SUPPLIER.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI')
                    ->like('ID_SUPPLIER',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_pegawai = $request->id_pegawai;
        $this->nama_supplier = $request->nama_supplier;
		$this->phone_supplier = $request->phone_supplier;
        $this->alamat_supplier = $request->alamat_supplier; 
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = [
        'nama_supplier' => $request->nama_supplier,
        'id_pegawai'=>$request->id_pegawai,
        'phone_supplier'=>$request->phone_supplier,
        'alamat_supplier'=>$request->alamat_supplier,];
        if($this->db->where('id_supplier',$id)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_supplier'=>$time
        ];
        if($this->db->where('id_supplier',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

}
?>