<html>
<style>
	html {
		width: 100%;
	}
	.center {
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 100%;
	}

	h1 {
		text-align: center;
	}

	.column {
		flex: 50%;
	}
	table, th, td {
  		border: 1px solid black;
	}
</style>
<body>
<div class='center'> 
	<img src="http://localhost/apikouvee/upload/kop.PNG"  style="width:100%">
	<hr>
	<h1>LAPORAN PENDAPATAN TAHUNAN</h1>
	<div>Tahun : <?php echo $year ?></div>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Bulan</th>
				<th>Jasa Layanan</th>
				<th>Produk</th>
				<th>Total</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
    	<?php $totalProduk=0; ?>
    	<?php $totalLayanan=0; ?>
	  	<?php foreach($pendapatan as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list['BULAN'] ?></td>
			<?php $layanan = "Rp " . number_format($list['JASA LAYANAN'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $layanan ?></td>
			<?php $produk = "Rp " . number_format($list['PRODUK'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $produk ?></td>
			<?php $total = "Rp " . number_format($list['TOTAL'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $total ?></td>
    	</tr>
    	<?php $totalProduk = $totalProduk + intval($list['PRODUK']) ?>
    	<?php $totalLayanan = $totalLayanan + intval($list['JASA LAYANAN']) ?>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
		    <?php $hasil = "Rp " . number_format($totalProduk + $totalLayanan,2,',','.');?>
			<h3 style='text-align:right'>Total <?php echo $hasil ?></h3>
		</div>
	</div>
	<div style='display:flex;'>
		<div class='column'>
			<p style='text-align:right'>Dicetak tanggal <?php echo $tanggal ?></p>
		</div>
	</div>
	
</div>
</body>
</html>