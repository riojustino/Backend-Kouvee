<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetilTransaksiLayananModel extends CI_Model{
    private $detil ='DETIL_TRANSAKSI_LAYANAN';
    private $trans ='TRANSAKSI_LAYANAN';
    private $layanan ='LAYANAN';
    //id_transaksi_layanan,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_layanan,tgl_transaksi_layanan,subtotal_transaksi_layanan
    //,total_transaksi_layanan,diskon_layanan
    public $id_transaksi_layanan;
    public $id_layanan;
    public $sub_total_layanan;
    public $jumlah_detil_layanan;

    public $rule=[
        [
            'field'=>'id_transaksi_layanan',
            'label'=>'id_transaksi_layanan',
            'rules'=>'required'
        ],
        [
            'field'=>'id_layanan',
            'label'=>'id_layanan',
            'rules'=>'required|numeric'
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id_trans){
        if($id_trans==null){
            $this->db->select('DETIL_TRANSAKSI_LAYANAN.ID_DETILTRANSAKSI_LAYANAN,DETIL_TRANSAKSI_LAYANAN.ID_TRANSAKSI_LAYANAN,DETIL_TRANSAKSI_LAYANAN.ID_LAYANAN, LAYANAN.NAMA_LAYANAN,DETIL_TRANSAKSI_LAYANAN.SUB_TOTAL_LAYANAN,DETIL_TRANSAKSI_LAYANAN.JUMLAH_DETIL_LAYANAN')
                    ->from('DETIL_TRANSAKSI_LAYANAN')
                    ->join('LAYANAN','DETIL_TRANSAKSI_LAYANAN.ID_LAYANAN = LAYANAN.ID_LAYANAN');
            return $this->db->get()->result();
        }else{
            $this->db->select('DETIL_TRANSAKSI_LAYANAN.ID_DETILTRANSAKSI_LAYANAN,DETIL_TRANSAKSI_LAYANAN.ID_TRANSAKSI_LAYANAN,DETIL_TRANSAKSI_LAYANAN.ID_LAYANAN,CONCAT(LAYANAN.NAMA_LAYANAN," ",UKURAN.UKURAN," ",JENIS_HEWAN.JENISHEWAN) AS NAMA_LAYANAN,DETIL_TRANSAKSI_LAYANAN.SUB_TOTAL_LAYANAN,DETIL_TRANSAKSI_LAYANAN.JUMLAH_DETIL_LAYANAN')
                    ->from('DETIL_TRANSAKSI_LAYANAN')
                    ->join('LAYANAN','DETIL_TRANSAKSI_LAYANAN.ID_LAYANAN = LAYANAN.ID_LAYANAN')
                    ->join('UKURAN','LAYANAN.ID_UKURAN = UKURAN.ID_UKURAN')
                    ->join('JENIS_HEWAN','LAYANAN.ID_JENISHEWAN = JENIS_HEWAN.ID_JENISHEWAN')
                    ->like('ID_TRANSAKSI_LAYANAN',$id_trans);
            return $this->db->get()->result();
            
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        //conn
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $this->id_transaksi_layanan = $request->id_transaksi_layanan;
        $this->id_layanan = $request->id_layanan;
        $this->jumlah_detil_layanan = $request->jumlah_detil_layanan;
        
        
        //ambil harga jual dari tabel layanan
        $result = mysqli_query($conn,"SELECT harga_layanan FROM $this->layanan WHERE id_layanan = '$request->id_layanan' ");
        $harga = mysqli_fetch_row($result);
        
        //hitung subtotal dari jumlah * harga
        $this->sub_total_layanan = $harga[0]*$request->jumlah_detil_layanan;
        
        if($this->db->insert($this->detil, $this)){
             //hitung sub total dari detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_layanan) FROM $this->detil WHERE id_transaksi_layanan = '$request->id_transaksi_layanan' ");
            $sub = mysqli_fetch_row($result);
            // echo $sub[0];
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_layanan = '$sub[0]' WHERE id_transaksi_layanan = '$request->id_transaksi_layanan' ");
            
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_layanan = subtotal_transaksi_layanan - diskon_layanan WHERE id_transaksi_layanan = '$request->id_transaksi_layanan' ");
            return ['msg'=>'Berhasil Menambahkan Data','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function update($request,$id) { 
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        //ambil id trans
        $result = mysqli_query($conn,"SELECT id_transaksi_layanan FROM $this->detil WHERE id_detiltransaksi_layanan = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        
        //ambil harga dari tabel layanan
        $result = mysqli_query($conn,"SELECT harga_layanan FROM $this->layanan WHERE id_layanan = '$request->id_layanan' ");
        $harga = mysqli_fetch_row($result);
        
        //ambil id layanan sebelum
        $result = mysqli_query($conn,"SELECT id_layanan FROM $this->detil WHERE id_detiltransaksi_layanan = '$id' ");
        $idSebelum = mysqli_fetch_row($result);
        
        //ambil jumlah layanan sebelum
        
        $updateData = [
        'id_layanan'=>$request->id_layanan,
        'sub_total_layanan' => $harga[0] * $request->jumlah_detil_layanan];
        if($this->db->where('id_detiltransaksi_layanan',$id)->update($this->detil, $updateData)){
            
            //hitung subtotal detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_layanan) FROM $this->detil WHERE id_transaksi_layanan = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_layanan = '$sub[0]' WHERE id_transaksi_layanan = '$idTrans[0]' ");
        
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_layanan = subtotal_transaksi_layanan - diskon_layanan WHERE id_transaksi_layanan = '$idTrans[0]' ");
            
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        $result = mysqli_query($conn,"SELECT id_transaksi_layanan FROM $this->detil WHERE id_detiltransaksi_layanan = '$id' ");
        $idTrans = mysqli_fetch_row($result);
        if($this->db->where('id_detiltransaksi_layanan',$id)->delete($this->detil)){
            //hitung subtotal detil
            $result = mysqli_query($conn,"SELECT SUM(sub_total_layanan) FROM $this->detil WHERE id_transaksi_layanan = '$idTrans[0]' ");
            $sub = mysqli_fetch_row($result);
            
            //update subtotal di transaksi 
            mysqli_query($conn,"UPDATE $this->trans SET subtotal_transaksi_layanan = '$sub[0]' WHERE id_transaksi_layanan = '$idTrans[0]' ");
        
            //update total di transaksi
            mysqli_query($conn,"UPDATE $this->trans SET total_transaksi_layanan = subtotal_transaksi_layanan - diskon_layanan WHERE id_transaksi_layanan = '$idTrans[0]' ");
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>