<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetilTransaksiProdukModel extends CI_Model{
    private $detil ='DETIL_TRANSAKSI_PRODUK';
    private $trans ='TRANSAKSI_PRODUK';
    private $produk ='PRODUK';
    //id_transaksi_produk,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_produk,tgl_transaksi_produk,subtotal_transaksi_produk
    //,total_transaksi_produk,diskon_produk
    public $id_transaksi_produk;
    public $id_produk;
    public $sub_total_produk;
    public $jumlah_produk;

    public $rule=[
        [
            'field'=>'id_transaksi_produk',
            'label'=>'id_transaksi_produk',
            'rules'=>'required'
        ],
        [
            'field'=>'id_produk',
            'label'=>'id_produk',
            'rules'=>'required|numeric'
        ],
        [
            'field'=>'jumlah_produk',
            'label'=>'jumlah_produk',
            'rules'=>'required|numeric'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id_trans){
        if($id_trans==null){
            $this->db->select('DETIL_TRANSAKSI_PRODUK.ID_DETIL_TRANSAKSI,DETIL_TRANSAKSI_PRODUK.ID_TRANSAKSI_PRODUK,DETIL_TRANSAKSI_PRODUK.ID_PRODUK, PRODUK.NAMA_PRODUK,PRODUK.HARGA_JUAL,DETIL_TRANSAKSI_PRODUK.SUB_TOTAL_PRODUK,DETIL_TRANSAKSI_PRODUK.JUMLAH_PRODUK')
                    ->from('DETIL_TRANSAKSI_PRODUK')
                    ->join('PRODUK','DETIL_TRANSAKSI_PRODUK.ID_PRODUK = PRODUK.ID_PRODUK');
            return $this->db->get()->result();
        }else{
            $this->db->select('DETIL_TRANSAKSI_PRODUK.ID_DETIL_TRANSAKSI,DETIL_TRANSAKSI_PRODUK.ID_TRANSAKSI_PRODUK,DETIL_TRANSAKSI_PRODUK.ID_PRODUK,PRODUK.NAMA_PRODUK, PRODUK.HARGA_JUAL,DETIL_TRANSAKSI_PRODUK.SUB_TOTAL_PRODUK,DETIL_TRANSAKSI_PRODUK.JUMLAH_PRODUK')
                    ->from('DETIL_TRANSAKSI_PRODUK')
                    ->join('PRODUK','DETIL_TRANSAKSI_PRODUK.ID_PRODUK = PRODUK.ID_PRODUK')
                    ->like('ID_TRANSAKSI_PRODUK',$id_trans);
            return $this->db->get()->result();
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        //conn
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $this->id_transaksi_produk = $request->id_transaksi_produk;
        $this->id_produk = $request->id_produk;
        $this->jumlah_produk = $request->jumlah_produk;
        
         //cek apakah udah ada
        $result =  mysqli_query($conn,"SELECT COUNT(DISTINCT id_produk) as cnt FROM $this->detil WHERE id_produk = '$request->id_produk' AND id_transaksi_produk = '$request->id_transaksi_produk'");
        $dup = mysqli_fetch_row($result);
        
        //ambil harga jual dari tabel produk
        $result = mysqli_query($conn,"SELECT harga_jual FROM $this->produk WHERE id_produk = '$request->id_produk' ");
        $harga = mysqli_fetch_row($result);
        
        //hitung subtotal dari jumlah * harga
        $this->sub_total_produk = $harga[0]*$request->jumlah_produk;
        
        //update stock berdasarkan jumlah beli
        $result = mysqli_query($conn,"UPDATE $this->produk SET stock = stock - $request->jumlah_produk WHERE id_produk = '$this->id_produk' ");
        
        if ( $dup[0] == 0){
               if($this->db->insert($this->detil, $this)){
             //hitung sub total dari detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_produk) FROM $this->detil WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
            $sub = mysqli_fetch_row($result);
           
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_produk = '$sub[0]' WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
            
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
            return ['msg'=>'Berhasil Menambahkan Data','error'=>false];
            }
        }else if ( $dup[0] != 0){
                mysqli_query($conn,"UPDATE $this->detil SET jumlah_produk = '$request->jumlah_produk' WHERE id_transaksi_produk = '$request->id_transaksi_produk' AND id_produk = '$request->id_produk' ");
                
                mysqli_query($conn,"UPDATE $this->detil SET sub_total_produk = '$this->sub_total_produk' WHERE id_transaksi_produk = '$request->id_transaksi_produk' AND id_produk = '$request->id_produk' ");
                
                $result = mysqli_query($conn,"SELECT SUM(sub_total_produk) FROM $this->detil WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
                $sub = mysqli_fetch_row($result);
                
                
                //update subtotal di transaksi 
                mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_produk = '$sub[0]' WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
                
                //update total di transaksi
                mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$request->id_transaksi_produk' ");
                return ['msg'=>'Berhasil Menambahkan Data','error'=>false];
           }
           
        
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function update($request,$id) { 
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        //ambil id trans
        $result = mysqli_query($conn,"SELECT id_transaksi_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        
        //ambil harga dari tabel produk
        $result = mysqli_query($conn,"SELECT harga_jual FROM $this->produk WHERE id_produk = '$request->id_produk' ");
        $harga = mysqli_fetch_row($result);
        
        //ambil id produk sebelum
        $result = mysqli_query($conn,"SELECT id_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $idSebelum = mysqli_fetch_row($result);
        
        //ambil jumlah produk sebelum
        $result = mysqli_query($conn,"SELECT jumlah_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $jmlSebelum = mysqli_fetch_row($result);
        
        //restore stock produk sebelum
        mysqli_query($conn,"UPDATE $this->produk SET stock = stock + $jmlSebelum[0] WHERE id_produk = '$idSebelum[0]' ");
        
        //update stock produk baru
        mysqli_query($conn,"UPDATE $this->produk SET stock = stock - $request->jumlah_produk WHERE id_produk = '$request->id_produk' ");
        
        $updateData = [
        'id_produk'=>$request->id_produk,
        'jumlah_produk' => $request->jumlah_produk,
        'sub_total_produk' => $harga[0] * $request->jumlah_produk];
        if($this->db->where('id_detil_transaksi',$id)->update($this->detil, $updateData)){
            
            //hitung subtotal detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_produk) FROM $this->detil WHERE id_transaksi_produk = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_produk = '$sub[0]' WHERE id_transaksi_produk = '$idTrans[0]' ");
        
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$idTrans[0]' ");
            
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $result = mysqli_query($conn,"SELECT id_transaksi_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        
        //ambil id produk sebelum
        $result = mysqli_query($conn,"SELECT id_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $idSebelum = mysqli_fetch_row($result);
        
        //ambil jumlah produk sebelum
        $result = mysqli_query($conn,"SELECT jumlah_produk FROM $this->detil WHERE id_detil_transaksi = '$id' ");
        $jmlSebelum = mysqli_fetch_row($result);
        
        //restore stock produk sebelum
        mysqli_query($conn,"UPDATE $this->produk SET stock = stock + $jmlSebelum[0] WHERE id_produk = '$idSebelum[0]' ");
        
        
        if($this->db->where('id_detil_transaksi',$id)->delete($this->detil)){
            $result = mysqli_query($conn,"SELECT SUM(sub_total_produk) FROM $this->detil WHERE id_transaksi_produk = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            if ($sub[0] == null){
                $sub[0] = 0;
            }
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_produk = '$sub[0]' WHERE id_transaksi_produk = '$idTrans[0]' ");
        
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_produk = subtotal_transaksi_produk - diskon_produk WHERE id_transaksi_produk = '$idTrans[0]' ");
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>