<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransaksiLayananModel extends CI_Model{
    private $table='TRANSAKSI_LAYANAN';
    private $table2='DETIL_TRANSAKSI_LAYANAN';
    private $table3='PELANGGAN';
    private $table4='HEWAN';
    
    //id_transaksi_layanan,id_pegawai= cs, peg_id_pegawai=kasir, id_hewan,status_transaksi_layanan,tgl_transaksi_layanan,subtotal_transaksi_layanan
    //,total_transaksi_layanan,diskon_layanan
    public $indeks;
    public $id_transaksi_layanan;
    public $id_pegawai;
    public $peg_id_pegawai;
    public $id_hewan;
    public $status_layanan;
    public $progres_layanan;
    public $tgl_transaksi_layanan;
    public $subtotal_transaksi_layanan;
    public $total_transaksi_layanan;
    public $diskon_layanan;

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
        ],
    ];
    public function Rules(){return $this->rule;}
    public function getall($id){
        if($id==null){
            $this->db->select('TP.SUBTOTAL_TRANSAKSI_LAYANAN,TP.TOTAL_TRANSAKSI_LAYANAN,TP.DISKON_LAYANAN,TP.ID_PEGAWAI,TP.PEG_ID_PEGAWAI,TP.ID_HEWAN,
            TP.PROGRES_LAYANAN,TP.STATUS_LAYANAN,P.NAMA_PELANGGAN,P.PHONE_PELANGGAN,PCS.NAMA_PEGAWAI,PK.NAMA_PEGAWAI AS NAMA_KASIR,TP.ID_TRANSAKSI_LAYANAN,TP.TGL_TRANSAKSI_LAYANAN')
                ->from('TRANSAKSI_LAYANAN TP')
                ->join('PEGAWAI PCS','TP.ID_PEGAWAI = PCS.ID_PEGAWAI')
                ->join('PEGAWAI PK','TP.PEG_ID_PEGAWAI = PK.ID_PEGAWAI')
                ->join('HEWAN H','TP.ID_HEWAN = H.ID_HEWAN')
                ->join('PELANGGAN P','H.ID_PELANGGAN = P.ID_PELANGGAN')
                ->join('JENIS_HEWAN JH','H.ID_JENISHEWAN = JH.ID_JENISHEWAN')
                ->where("status_layanan='0' OR progres_layanan='0'");
            return $this->db->get()->result();
        }else{
            $this->db->select('TP.SUBTOTAL_TRANSAKSI_LAYANAN,TP.TOTAL_TRANSAKSI_LAYANAN,TP.DISKON_LAYANAN,TP.ID_PEGAWAI,TP.PEG_ID_PEGAWAI,TP.ID_HEWAN,
            TP.PROGRES_LAYANAN,TP.STATUS_LAYANAN,CONCAT(P.NAMA_PELANGGAN,"(",H.NAMA_HEWAN,"-",JH.JENISHEWAN,")") AS NAMA_PELANGGAN,P.PHONE_PELANGGAN,PCS.NAMA_PEGAWAI,PK.NAMA_PEGAWAI AS NAMA_KASIR,TP.ID_TRANSAKSI_LAYANAN,TP.TGL_TRANSAKSI_LAYANAN')
                ->from('TRANSAKSI_LAYANAN TP')
                ->join('PEGAWAI PCS','TP.ID_PEGAWAI = PCS.ID_PEGAWAI')
                ->join('PEGAWAI PK','TP.PEG_ID_PEGAWAI = PK.ID_PEGAWAI')
                ->join('HEWAN H','TP.ID_HEWAN = H.ID_HEWAN')
                ->join('PELANGGAN P','H.ID_PELANGGAN = P.ID_PELANGGAN')
                ->join('JENIS_HEWAN JH','H.ID_JENISHEWAN = JH.ID_JENISHEWAN')
                ->like('TP.ID_TRANSAKSI_LAYANAN',$id);
            return $this->db->get()->row_array();
        }
    }
    public function store($request) { 
        date_default_timezone_set('Asia/Jakarta');
        $now = date('dmy');
        // echo $now;
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        
        $result = mysqli_query($conn,"SELECT COUNT(DISTINCT id_transaksi_layanan) as cnt FROM $this->table WHERE id_transaksi_layanan LIKE '%$now%' ");
        $num_rows = mysqli_fetch_row($result);
        // echo $num_rows[0];
        
        $result = mysqli_query($conn,"SELECT MAX(indeks) FROM $this->table");
        $MaxID = mysqli_fetch_row($result);
        // echo $MaxID[0];
        
        if($num_rows[0] == 0){
            $this->id_transaksi_layanan = 'LY-'.$now.'-01';
        }
        else if($num_rows[0] > 0){
            
            $result = mysqli_query($conn,"SELECT id_transaksi_layanan FROM $this->table WHERE indeks = $MaxID[0]");
            $idTrans = mysqli_fetch_row($result);
            //echo ' ',$idTrans[0];
            
            $str = substr($idTrans[0],10,2);
            $no = intval($str) + 1;
            
            if($no < 10)
            {
                $this->id_transaksi_layanan = 'LY-'.$now.'-0'.$no;
                
            }else if($no>=10)
            {
                $this->id_transaksi_layanan = 'LY-'.$now.'-'.$no;
               
            }
        }
        $this->indeks = $MaxID[0] + 1;
        $this->id_pegawai = $request->id_pegawai;
        $this->peg_id_pegawai = $request->peg_id_pegawai;
        $this->id_hewan = $request->id_hewan;
        $this->status_layanan = 0;
        $this->progres_layanan = 0;
        $now = date('Y-m-d H:i:s');
        $this->tgl_transaksi_layanan = $now;
        $this->subtotal_transaksi_layanan = 0;
        $this->total_transaksi_layanan = $this->subtotal_transaksi_layanan - $this->diskon_layanan;
        $this->diskon_layanan = $request->diskon_layanan;
        if($this->db->insert($this->table, $this)){
            
            return ['data'=>$this->id_transaksi_layanan,'msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    
    public function update($request,$id) {
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
        // $this->db->select('SUBTOTAL_TRANSAKSI_LAYANAN'); 
        // $result = mysqli_query($conn,"SELECT SUBTOTAL_TRANSAKSI_LAYANAN FROM $this->table WHERE id_transaksi_layanan = $id");
        // $sub = mysqli_fetch_row($result);
        
        $updateData = [
        'id_pegawai'=>$request->id_pegawai,
        'peg_id_pegawai' => $request->peg_id_pegawai,
        'id_hewan' => $request->id_hewan,
        'status_layanan' => $request->status_layanan,
        'progres_layanan' => $request->progres_layanan,
        'total_transaksi_layanan' => $request->subtotal_transaksi_layanan - $request->diskon_layanan,
        'diskon_layanan' => $request->diskon_layanan];
        if($this->db->where('id_transaksi_layanan',$id)->update($this->table, $updateData)){
            if($request->progres_layanan == 1){
                
                $result = mysqli_query($conn,"SELECT id_pelanggan  FROM $this->table4 WHERE id_hewan  = '$request->id_hewan' ");
                $pelanggan = mysqli_fetch_row($result);
                
                $result = mysqli_query($conn,"SELECT nama_pelanggan  FROM $this->table3 WHERE id_pelanggan  = '$pelanggan[0]' ");
                $nama = mysqli_fetch_row($result);
                
                $result = mysqli_query($conn,"SELECT phone_pelanggan  FROM $this->table3 WHERE id_pelanggan  = '$pelanggan[0]' ");
                $phone = mysqli_fetch_row($result);
                echo $nama[0].$phone[0];
                
                $resp = $this->SendAPI_SMS($nama[0],$phone[0]);
                
            }
            return ['msg'=>'Data Berhasil Di Ubah','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function delete($id){
        if($this->db->where('id_transaksi_layanan',$id)->delete($this->table)){
            return ['msg'=>'Data Berhasil Di Hapus','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    function SendAPI_SMS($nama,$phone){
        $email_api    = urlencode("kasih69ibu@gmail.com");
        $passkey_api  = urlencode("Hm123123");
        $no_hp_tujuan = urlencode("$phone");
        $isi_pesan    = urlencode("Hai, ".$nama.". Layanan yang telah kamu beli di Kouvee Pet Shop telah selesai!");
        
        $url          = "https://reguler.medansms.co.id/sms_api.php?action=kirim_sms&email=".$email_api."&passkey=".$passkey_api."&no_tujuan=".$no_hp_tujuan."&pesan=".$isi_pesan."&json=1";
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response  = curl_exec($ch);
    	curl_close($ch);
    	return $response;
    }
}
?>
