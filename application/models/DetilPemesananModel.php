<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetilPemesananModel extends CI_Model{
    private $detil ='DETIL_PEMESANAN';
    private $trans ='PEMESANAN';
    private $produk ='PRODUK';
    //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
    //,total_transaksi_produk,diskon_produk
    public $id_pemesanan;
    public $id_produk;
    public $sub_total_pemesanan;
    public $jumlah_pesanan;

    public $rule=[
        [
            'field'=>'id_pemesanan',
            'label'=>'id_pemesanan',
            'rules'=>'required'
        ],
        [
            'field'=>'id_produk',
            'label'=>'id_produk',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'jumlah_pesanan',
            'label'=>'jumlah_pesanan',
            'rules'=>'required|numeric'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id_trans){
        if($id_trans==null){
            $this->db->select('DETIL_PEMESANAN.ID_DETIL_PEMESANAN,DETIL_PEMESANAN.ID_PEMESANAN,DETIL_PEMESANAN.ID_PRODUK, PRODUK.NAMA_PRODUK,DETIL_PEMESANAN.SUB_TOTAL_PEMESANAN,DETIL_PEMESANAN.JUMLAH_PESANAN')
                    ->from('DETIL_PEMESANAN')
                    ->join('PRODUK','DETIL_PEMESANAN.ID_PRODUK = PRODUK.ID_PRODUK');
            return $this->db->get()->result();
        }else{
            $this->db->select('DETIL_PEMESANAN.ID_DETIL_PEMESANAN,DETIL_PEMESANAN.ID_PEMESANAN,DETIL_PEMESANAN.ID_PRODUK, PRODUK.NAMA_PRODUK,PRODUK.SATUAN_PRODUK,DETIL_PEMESANAN.SUB_TOTAL_PEMESANAN,DETIL_PEMESANAN.JUMLAH_PESANAN')
                    ->from('DETIL_PEMESANAN')
                    ->join('PRODUK','DETIL_PEMESANAN.ID_PRODUK = PRODUK.ID_PRODUK')
                    ->like('ID_PEMESANAN',$id_trans);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        //conn
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $this->id_pemesanan = $request->id_pemesanan;
        $this->id_produk = $request->id_produk;
        $this->jumlah_pesanan = $request->jumlah_pesanan;
        
        
        //ambil harga jual dari tabel produk
        $result = mysqli_query($conn,"SELECT harga_beli FROM $this->produk WHERE id_produk = '$request->id_produk' ");
        $harga = mysqli_fetch_row($result);
        
        //hitung subtotal dari jumlah * harga
        $this->sub_total_pemesanan = $harga[0]*$request->jumlah_pesanan;
        
        //update stock berdasarkan jumlah beli
        // $result = mysqli_query($conn,"UPDATE $this->produk SET stock = stock - $request->jumlah_produk WHERE id_produk = '$this->id_produk' ");

        
        if($this->db->insert($this->detil, $this)){
             //hitung sub total dari detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_pemesanan) FROM $this->detil WHERE id_pemesanan = '$request->id_pemesanan' ");
            $sub = mysqli_fetch_row($result);
            echo $sub[0];
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET total = '$sub[0]' WHERE id_pemesanan = '$request->id_pemesanan' ");
            
            //update total di transaksi
            // mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
            return ['msg'=>'Berhasil Menambahkan Data','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function update($request,$id) { 
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        //ambil id trans
        $result = mysqli_query($conn,"SELECT id_pemesanan FROM $this->detil WHERE id_detil_pemesanan = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        // echo $idTrans[0];
        
        //ambil harga dari tabel produk
        $result = mysqli_query($conn,"SELECT harga_beli FROM $this->produk WHERE id_produk = '$request->id_produk' ");
        $harga = mysqli_fetch_row($result);
        
        // //ambil id produk sebelum
        // $result = mysqli_query($conn,"SELECT id_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        // $idSebelum = mysqli_fetch_row($result);
        
        // //ambil jumlah produk sebelum
        // $result = mysqli_query($conn,"SELECT jumlah_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        // $jmlSebelum = mysqli_fetch_row($result);
        
        // //restore stock produk sebelum
        // mysqli_query($conn,"UPDATE $this->produk SET stock = stock + $jmlSebelum[0] WHERE id_produk = '$idSebelum[0]' ");
        
        // //update stock produk baru
        // mysqli_query($conn,"UPDATE $this->produk SET stock = stock - $request->jumlah_produk WHERE id_produk = '$request->id_produk' ");
        
        $updateData = [
        'id_produk'=>$request->id_produk,
        'jumlah_pesanan' => $request->jumlah_pesanan,
        'sub_total_pemesanan' => $harga[0] * $request->jumlah_pesanan];
        if($this->db->where('id_detil_pemesanan',$id)->update($this->detil, $updateData)){
            
            //hitung subtotal detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_pemesanan) FROM $this->detil WHERE id_pemesanan = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET total = '$sub[0]' WHERE id_pemesanan = '$idTrans[0]' ");
        
            // //update total di transaksi
            // mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$idTrans[0]' ");
            
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        $result = mysqli_query($conn,"SELECT id_pemesanan FROM $this->detil WHERE id_detil_pemesanan = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        if($this->db->where('id_detil_pemesanan',$id)->delete($this->detil)){
            $result = mysqli_query($conn,"SELECT SUM(sub_total_pemesanan) FROM $this->detil WHERE id_pemesanan = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET total = '$sub[0]' WHERE id_pemesanan = '$idTrans[0]' ");
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>