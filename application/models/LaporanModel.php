<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanModel extends CI_Model{
    public function getDataLayananTahun($year){
        
        date_default_timezone_set('Asia/Jakarta');
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT m.Nama AS 'BULAN', 
                COALESCE(l.nama,'-') AS 'NAMA LAYANAN',
                COALESCE(max(l.total),0) 'JUMLAH PENJUALAN'
                FROM (
                    SELECT * FROM BULAN
                )AS m 
                LEFT JOIN (
                    SELECT t.TGL_TRANSAKSI_LAYANAN,
                    CONCAT(l.NAMA_LAYANAN,' ',j.JENISHEWAN,' ',u.UKURAN) AS nama, 
                    SUM(d.JUMLAH_DETIL_LAYANAN) AS total
                    FROM TRANSAKSI_LAYANAN t
                    JOIN DETIL_TRANSAKSI_LAYANAN d ON t.ID_TRANSAKSI_LAYANAN = d.ID_TRANSAKSI_LAYANAN
                    JOIN LAYANAN l on d.ID_LAYANAN = l.ID_LAYANAN
                    JOIN UKURAN u on l.ID_UKURAN = u.ID_UKURAN
                    JOIN JENIS_HEWAN j on j.ID_JENISHEWAN = l.ID_JENISHEWAN
                    WHERE YEAR(t.TGL_TRANSAKSI_LAYANAN) = '$year'
                    GROUP BY l.ID_LAYANAN
                )AS l ON MONTHNAME(l.TGL_TRANSAKSI_LAYANAN) = m.Nama
                GROUP BY m.Nama
                ORDER BY m.Nomor ASC";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
        
    }
    
    public function getDataProdukTahun($year){
        date_default_timezone_set('Asia/Jakarta');
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT m.Nama AS 'BULAN', 
                COALESCE(p.nama,'-') AS 'NAMA PRODUK',
                COALESCE(max(p.total),0) 'JUMLAH PENJUALAN'
                FROM (
                    SELECT * FROM BULAN
                )AS m 
                LEFT JOIN (
                    SELECT t.TGL_TRANSAKSI, p.NAMA_PRODUK AS nama, SUM(d.JUMLAH_PRODUK) AS total
                    FROM TRANSAKSI_PRODUK t
                    JOIN DETIL_TRANSAKSI_PRODUK d ON t.ID_TRANSAKSI_PRODUK = d.ID_TRANSAKSI_PRODUK
                    join PRODUK p on p.ID_PRODUK = d.ID_PRODUK
                    GROUP BY p.ID_PRODUK
                )AS p ON MONTHNAME(p.TGL_TRANSAKSI) = m.Nama
                GROUP BY m.Nama
                ORDER BY m.Nomor ASC";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPendapatanTahun($year){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT m.Nama AS 'BULAN', 
                COALESCE(sum(layanan),0) AS 'JASA LAYANAN',
                COALESCE(sum(produk),0) AS 'PRODUK',
                COALESCE(sum(layanan),0) + COALESCE(sum(produk),0) AS 'TOTAL'
                FROM (
                    SELECT * FROM BULAN
                )AS m 
                LEFT JOIN (
                    SELECT
                    		ID_TRANSAKSI_PRODUK AS ID,
                            TGL_TRANSAKSI AS TGL,
                            TOTAL_TRANSAKSI_PRODUK AS produk,
                    		0 AS layanan
                        FROM
                            TRANSAKSI_PRODUK
                        WHERE STATUS_TRANSAKSI_PRODUK = '1' AND
                        YEAR(TGL_TRANSAKSI) = '$year'
                        UNION ALL
                        SELECT
                    		ID_TRANSAKSI_LAYANAN AS ID,
                            TGL_TRANSAKSI_LAYANAN AS TGL,
                            0  AS produk,
                            TOTAL_TRANSAKSI_LAYANAN AS layanan
                        FROM
                            TRANSAKSI_LAYANAN
                        WHERE STATUS_LAYANAN = '1' AND
                        YEAR(TGL_TRANSAKSI_LAYANAN) = '$year'   
                ) p ON MONTHNAME(p.TGL) = m.Nama 
                GROUP BY m.Nama
                ORDER by m.Nomor";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPengadaanTahun($year){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT m.Nama AS 'BULAN', 
                COALESCE(SUM(p.total),0) AS 'JUMLAH PENGELUARAN'
                FROM (
                    SELECT * FROM BULAN
                )AS m 
                LEFT JOIN (
                    SELECT TANGGAL_PEMESANAN,
                    TOTAL AS total
                    FROM PEMESANAN 
                    WHERE YEAR(TANGGAL_PEMESANAN) = '$year'
                    GROUP BY ID_PEMESANAN
                )AS p ON MONTHNAME(p.TANGGAL_PEMESANAN) = m.Nama
                GROUP BY m.Nama
                ORDER BY m.Nomor ASC";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    public function getDataPengadaanBulan($year,$month){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT
                PR.NAMA_PRODUK AS 'NAMA PRODUK', 
                SUM(D.SUB_TOTAL_PEMESANAN) AS 'JUMLAH PENGELUARAN'
                FROM DETIL_PEMESANAN D
                JOIN PEMESANAN P 
                ON D.ID_PEMESANAN = P.ID_PEMESANAN
                JOIN PRODUK PR
                ON D.ID_PRODUK = PR.ID_PRODUK
                WHERE MONTHNAME(P.TANGGAL_PEMESANAN) = '$month'
                AND YEAR(P.TANGGAL_PEMESANAN) = '$year'
                GROUP BY PR.ID_PRODUK";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPendapatanProdukBulan($year,$month){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT
                P.NAMA_PRODUK AS 'NAMA PRODUK',
                SUM(D.SUB_TOTAL_PRODUK) AS 'HARGA'
                FROM TRANSAKSI_PRODUK T 
                JOIN DETIL_TRANSAKSI_PRODUK D 
                ON T.ID_TRANSAKSI_PRODUK = D.ID_TRANSAKSI_PRODUK
                JOIN PRODUK P
                ON D.ID_PRODUK = P.ID_PRODUK
                WHERE YEAR(T.TGL_TRANSAKSI) = '$year'
                AND MONTHNAME(T.TGL_TRANSAKSI) = '$month'
                AND T.STATUS_TRANSAKSI_PRODUK = '1'
                GROUP BY P.ID_PRODUK
                ";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    public function getDataPendapatanLayananBulan($year,$month){
        $conn = mysqli_connect('localhost', $this->db->username, $this->db->password,$this->db->database);
    
        $sql = "SELECT 
                CONCAT(L.NAMA_LAYANAN,' ',J.JENISHEWAN,' ',U.UKURAN) AS 'NAMA JASA LAYANAN',
                SUM(D.SUB_TOTAL_LAYANAN) AS 'HARGA'
                FROM TRANSAKSI_LAYANAN T
                JOIN DETIL_TRANSAKSI_LAYANAN D 
                ON T.ID_TRANSAKSI_LAYANAN = D.ID_TRANSAKSI_LAYANAN
                JOIN LAYANAN L 
                ON D.ID_LAYANAN = L.ID_LAYANAN
                JOIN UKURAN U
                ON L.ID_UKURAN = U.ID_UKURAN
                JOIN JENIS_HEWAN J 
                ON L.ID_JENISHEWAN = J.ID_JENISHEWAN
                WHERE YEAR(T.TGL_TRANSAKSI_LAYANAN) = '$year'
                AND MONTHNAME(T.TGL_TRANSAKSI_LAYANAN) = '$month'
                AND T.STATUS_LAYANAN = '1'
                GROUP BY L.ID_LAYANAN";
        
        $result = mysqli_query($conn,$sql);
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $rows[] = $row;
        }
        
        return $rows;
    }
}
?>