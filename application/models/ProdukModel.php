<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukModel extends CI_Model{
    private $table='produk';
    
    public $id_produk;
    public $id_pegawai;
    public $nama_produk;
    public $stock;
    public $min_stock;
    public $satuan_produk;
    public $harga_beli;
    public $harga_jual;
    public $gambar;

    public $rule=[
        [
            'field'=>'id_pegawai',
            'label'=>'id_pegawai',
            'rules'=>'required'
        ],
        [
            'field'=>'nama_produk',
            'label'=>'nama_produk',
            'rules'=>'required|is_unique[produk.nama_produk]|alpha'
        ],
        [
            'field'=>'stock',
            'label'=>'stock',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'min_stock',
            'label'=>'min_stock',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'satuan_produk',
            'label'=>'satuan_produk',
            'rules'=>'required'
        ],
        [
            'field'=>'harga_beli',
            'label'=>'harga_beli',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'harga_jual',
            'label'=>'harga_jual',
            'rules'=>'required|numeric'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('PRODUK.ID_PRODUK,PRODUK.NAMA_PRODUK,PRODUK.STOCK,PRODUK.MIN_STOCK,PRODUK.SATUAN_PRODUK,
            PRODUK.HARGA_BELI,PRODUK.HARGA_JUAL,PRODUK.GAMBAR,PEGAWAI.NAMA_PEGAWAI,PRODUK.CREATE_AT_PRODUK,PRODUK.UPDATE_AT_PRODUK,PRODUK.DELETE_AT_PRODUK')
                    ->from('PRODUK')
                    ->join('PEGAWAI','PRODUK.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI');
            return $this->db->get()->result();
        }else{
            $this->db->select('PRODUK.ID_PRODUK,PRODUK.NAMA_PRODUK,PRODUK.STOCK,PRODUK.MIN_STOCK,PRODUK.SATUAN_PRODUK,
            PRODUK.HARGA_BELI,PRODUK.HARGA_JUAL,PRODUK.GAMBAR,PEGAWAI.NAMA_PEGAWAI,PRODUK.CREATE_AT_PRODUK,PRODUK.UPDATE_AT_PRODUK,PRODUK.DELETE_AT_PRODUK')
                    ->from('PRODUK')
                    ->join('PEGAWAI','PRODUK.ID_PEGAWAI = PEGAWAI.ID_PEGAWAI')
                    ->like('ID_PRODUK',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        $this->id_pegawai = 1;
        $this->nama_produk = $request->nama_produk;
        $this->stock = $request->stock;
        $this->min_stock = $request->min_stock;
        $this->satuan_produk = $request->satuan_produk;
        $this->harga_beli = $request->harga_beli;
        $this->harga_jual = $request->harga_jual;
        $this->gambar = $request->gambar;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil Menbahkan Data','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = [
        'id_pegawai'=>$request->id_pegawai,
        'nama_produk' => $request->nama_produk,
        'stock' => $request->stock,
        'min_stock' => $request->min_stock,
        'satuan_produk' => $request->satuan_produk,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
        'gambar'=>$request->gambar];
        if($this->db->where('id_produk',$id)->update($this->table, $updateData)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($time,$id){
        $delet=[
            'delete_at_produk'=>$time
        ];
        if($this->db->where('id_produk',$id)->update($this->table, $delet)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>