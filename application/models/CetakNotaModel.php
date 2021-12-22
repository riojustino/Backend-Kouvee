<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CetakNotaModel extends CI_Model{
    public function cetak(){
        $a = "http://localhost/apikouvee/upload/kop.PNG";
        date_default_timezone_set('Asia/Jakarta');
        $id_detil = LY-200101-01;
        $sql = $this->DetilTransaksiLayananModel->getall($id_detil);
        echo "<html>
            <style>
            .html{
                width:auto;
            }
            .center {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
              }
              h1 {text-align: center;}
              .column {
                flex: 50%;
              }
            </style>
            <div>
                <img src=".$a." class='center'>
            </div>
            <div class='center'>
                <hr>
                <h1>Nota Lunas</h1>
            </div>
            <div class='center'>
                <p style='text-align:right'>".date('d F Y H:i')."</p>
            </div>
            <div class='center'>
                <p style='text-align:left'>PR-XXXX-XXX</p>
            </div>
            <div class='center' style='display:flex;'>
                <div class='column' style='text-align:left'>
                    <p>Member&ensp;:</p>
                    <p>Phone&ensp;:</p>
                </div>
                <div class='column' style='text-align:right'>
                    <p>CS&ensp;:</p>
                    <p>Kasir&ensp;:</p>
                </div>
            </div>
            <div class='center'>
                <hr>
                    <h2 style='text-align:center'>Produk</h2>
                <hr>
            </div>
            <div class='center'>
                <table border='1' style='width: 100%'>
                    <tr>
                        <th width='1%'>No</th>
                        <th>Nama Jasa</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Sub Total</th>
                    </tr>";
            $no = 1;
            while($data = mysqli_fetch_assoc($sql)){
            echo"
            <tr>
                <td>". $no++ ." </td>
                <td>".$data['NAMA_LAYANAN'] ."</td>
                <td>".$data['SUB_TOTAL_LAYANAN'] ."</td>
                <td>".$data['JUMLAH_DETIL_LAYANAN'] ."</td>
                <td>".$data['SUB_TOTAL_LAYANAN'] ."</td>
            </tr>
            ";
            };
            echo"
                </table>
            </div>
        </html>";
     
    }
}
?>