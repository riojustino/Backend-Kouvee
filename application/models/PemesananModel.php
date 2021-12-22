<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemesananModel extends CI_Model{
    private $table='PEMESANAN';
    private $table2='DETIL_PEMESANAN';
    private $table3='PRODUK';
    
    //id_pemesanan,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
    //,total_transaksi_produk,diskon_produk
    public $indeks;
    public $id_pemesanan;
    public $id_supplier;
    public $status_pemesanan;
    public $tanggal_pemesanan;
    public $total;

    public $rule=[
        [
            'field'=>'id_supplier',
            'label'=>'id_supplier',
            'rules'=>'required'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('PEMESANAN.ID_PEMESANAN,PEMESANAN.ID_SUPPLIER,PEMESANAN.TANGGAL_PEMESANAN,PEMESANAN.TANGGAL_PEMESANAN,PEMESANAN.STATUS_PEMESANAN,
                                PEMESANAN.TOTAL,SUPPLIER.NAMA_SUPPLIER')
                    ->from('PEMESANAN')
                    ->join('SUPPLIER','PEMESANAN.ID_SUPPLIER = SUPPLIER.ID_SUPPLIER');
            return $this->db->get()->result();
        }else{
            $this->db->select('PEMESANAN.ID_PEMESANAN,PEMESANAN.ID_SUPPLIER,PEMESANAN.TANGGAL_PEMESANAN,PEMESANAN.TANGGAL_PEMESANAN,PEMESANAN.STATUS_PEMESANAN,
                                PEMESANAN.TOTAL,SUPPLIER.NAMA_SUPPLIER,SUPPLIER.ALAMAT_SUPPLIER,SUPPLIER.PHONE_SUPPLIER')
                    ->from('PEMESANAN')
                    ->join('SUPPLIER','PEMESANAN.ID_SUPPLIER = SUPPLIER.ID_SUPPLIER')
                    ->like('ID_PEMESANAN',$id);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date('yy-m-d');
        // echo $now;
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $result = mysqli_query($conn,"SELECT COUNT(DISTINCT id_pemesanan) as cnt FROM $this->table WHERE id_pemesanan LIKE '%$now%' ");
        $num_rows = mysqli_fetch_row($result);
        // echo $num_rows[0];
        
        $result = mysqli_query($conn,"SELECT MAX(indeks) FROM $this->table");
        $MaxID = mysqli_fetch_row($result);
        // echo $MaxID[0];
        
        if($num_rows[0] == 0){
            $this->id_pemesanan = 'PO-'.$now.'-01';
        }
        else if($num_rows[0] > 0){
            
            $result = mysqli_query($conn,"SELECT id_pemesanan FROM $this->table WHERE indeks = $MaxID[0]");
            $idTrans = mysqli_fetch_row($result);
            // echo ' ',$idTrans[0];
            
            $str = substr($idTrans[0],14,2);
            $no = intval($str) + 1;
            
            if($no < 10)
            {
                $this->id_pemesanan = 'PO-'.$now.'-0'.$no;
                
            }else if($no>=10)
            {
                $this->id_pemesanan = 'PO-'.$now.'-'.$no;
               
            }
        }
        $this->indeks = $MaxID[0] + 1;
        $this->id_supplier = $request->id_supplier;
        $this->status_pemesanan = 0;
        $now = date('Y-m-d');
        $this->tanggal_pemesanan = $now;
        $this->total = 0;;
        if($this->db->insert($this->table, $this)){
            
            return ['data'=>$this->id_pemesanan,'msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) {
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        // $sub = mysqli_fetch_row($result);
        
        $updateData = [
        'status_pemesanan'=>$request->status_pemesanan,
        'id_supplier' => $request->id_supplier];
        if($this->db->where('id_pemesanan',$id)->update($this->table, $updateData)){
            if($request->status_pemesanan==1){
                //ambil id dan jumlah produk
                $produkque = mysqli_query($conn,"SELECT ID_PRODUK, JUMLAH_PESANAN FROM DETIL_PEMESANAN WHERE ID_PEMESANAN = '$id'");
                
                while($row = mysqli_fetch_array($produkque, MYSQLI_ASSOC))
                {
                   $rows[] = $row;
                }

                foreach($rows as $row){
                    $id = $row['ID_PRODUK'];
                    $pesan = intval($row['JUMLAH_PESANAN']);
                    mysqli_query($conn,"UPDATE $this->table3 SET stock = stock + '$pesan' WHERE ID_PRODUK = '$id' ");
                }
           }
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        if($this->db->where('id_pemesanan',$id)->delete($this->table)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>