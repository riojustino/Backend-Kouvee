<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiProdukModel extends CI_Model{
    private $table='TRANSAKSI_PRODUK';
    private $table2='DETIL_TRANSAKSI_PRODUK';
    
    //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
    //,total_transaksi_produk,diskon_produk
    public $indeks;
    public $id_transaksi_produk;
    public $id_pegawai;
    public $peg_id_pegawai;
    public $id_hewan;
    public $status_transaksi_produk;
    public $tgl_transaksi;
    public $subtotal_transaksi_produk;
    public $total_transaksi_produk;
    public $diskon_produk;

    public $rule=[
        [
            'field'=>'id_pegawai',
            'label'=>'id_pegawai',
            'rules'=>'required'
        ],
        [
            'field'=>'id_hewan',
            'label'=>'id_hewan',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'peg_id_pegawai',
            'label'=>'peg_id_pegawai',
            'rules'=>'required|numeric'
        ]
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
             $this->db->select('tp.INDEKS, 
           tp.ID_TRANSAKSI_PRODUK, 
           tp.ID_PEGAWAI, 
           cs.NAMA_PEGAWAI as NAMA_CS, 
           tp.ID_HEWAN, 
           h.NAMA_HEWAN, 
           j.JENISHEWAN,
           p.NAMA_PELANGGAN,
           p.PHONE_PELANGGAN,
           tp.PEG_ID_PEGAWAI , 
           kasir.NAMA_PEGAWAI as NAMA_KASIR, 
           tp.STATUS_TRANSAKSI_PRODUK, 
           tp.TGL_TRANSAKSI, 
           tp.SUBTOTAL_TRANSAKSI_PRODUK, tp.TOTAL_TRANSAKSI_PRODUK, 
           tp.DISKON_PRODUK')
                    ->from('TRANSAKSI_PRODUK tp')
                    ->join('PEGAWAI cs','tp.ID_PEGAWAI = cs.ID_PEGAWAI')
                    ->join('PEGAWAI kasir','tp.PEG_ID_PEGAWAI = kasir.ID_PEGAWAI')
                    ->join('HEWAN h','tp.ID_HEWAN = h.ID_HEWAN')
                    ->join('JENIS_HEWAN j','j.ID_JENISHEWAN = h.ID_JENISHEWAN')
                    ->join('PELANGGAN p','h.ID_PELANGGAN = p.ID_PELANGGAN');
            return $this->db->get()->result();
        }else{
           $this->db->select('tp.INDEKS, 
           tp.ID_TRANSAKSI_PRODUK, 
           tp.ID_PEGAWAI, 
           cs.NAMA_PEGAWAI as NAMA_CS, 
           tp.ID_HEWAN, 
           h.NAMA_HEWAN, 
           j.JENISHEWAN,
           p.NAMA_PELANGGAN,
           p.PHONE_PELANGGAN,
           tp.PEG_ID_PEGAWAI , 
           kasir.NAMA_PEGAWAI as NAMA_KASIR, 
           tp.STATUS_TRANSAKSI_PRODUK, 
           tp.TGL_TRANSAKSI, 
           tp.SUBTOTAL_TRANSAKSI_PRODUK, tp.TOTAL_TRANSAKSI_PRODUK, 
           tp.DISKON_PRODUK')
                    ->from('TRANSAKSI_PRODUK tp')
                    ->join('PEGAWAI cs','tp.ID_PEGAWAI = cs.ID_PEGAWAI')
                    ->join('PEGAWAI kasir','tp.PEG_ID_PEGAWAI = kasir.ID_PEGAWAI')
                    ->join('HEWAN h','tp.ID_HEWAN = h.ID_HEWAN')
                    ->join('JENIS_HEWAN j','j.ID_JENISHEWAN = h.ID_JENISHEWAN')
                    ->join('PELANGGAN p','h.ID_PELANGGAN = p.ID_PELANGGAN')
                    ->like('tp.ID_TRANSAKSI_PRODUK',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date('dmy');
       
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $result = mysqli_query($conn,"SELECT COUNT(DISTINCT id_transaksi_produk) as cnt FROM $this->table WHERE id_transaksi_produk LIKE '%$now%' ");
        $num_rows = mysqli_fetch_row($result);

        
        $result = mysqli_query($conn,"SELECT MAX(indeks) FROM $this->table");
        $MaxID = mysqli_fetch_row($result);
       
        
        if($num_rows[0] == 0){
            $this->id_transaksi_produk = 'PR-'.$now.'-01';
            
        }
        else if($num_rows[0] > 0){
            
            $result = mysqli_query($conn,"SELECT id_transaksi_produk FROM $this->table WHERE indeks = $MaxID[0]");
            $idTrans = mysqli_fetch_row($result);
            
            $str = substr($idTrans[0],10,2);
            $no = intval($str) + 1;
            
            if($no < 10)
            {
                $this->id_transaksi_produk = 'PR-'.$now.'-0'.$no;
                
            }else if($no>=10)
            {
                $this->id_transaksi_produk = 'PR-'.$now.'-'.$no;
               
            }
        }
        $this->indeks = $MaxID[0] + 1;
        $this->id_pegawai = $request->id_pegawai;
        $this->peg_id_pegawai = $request->peg_id_pegawai;
        $this->id_hewan = $request->id_hewan;
        $this->status_transaksi_produk = 0;
        $now = date('Y-m-d H:i:s');
        $this->tgl_transaksi = $now;
        $this->subtotal_transaksi_produk = 0;
        $this->total_transaksi_produk = $this->subtotal_transaksi_produk - $this->diskon_produk;
        $this->diskon_produk = 0;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil Menambahkan Data','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function update($request,$id) {
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        $result = mysqli_query($conn,"SELECT subtotal_transaksi_produk FROM $this->table WHERE id_transaksi_produk = '$id' ");
        $sub = mysqli_fetch_row($result);
        
        $updateData = [
        'id_pegawai'=>$request->id_pegawai,
        'peg_id_pegawai' => $request->peg_id_pegawai,
        'id_hewan' => $request->id_hewan,
        'status_transaksi_produk' => $request->status_transaksi_produk,
        'total_transaksi_produk' => $sub[0] - $request->diskon_produk,
        'diskon_produk' => $request->diskon_produk];
        if($this->db->where('id_transaksi_produk',$id)->update($this->table, $updateData)){
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        if($this->db->where('id_transaksi_produk',$id)->delete($this->table)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>