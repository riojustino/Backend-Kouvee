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
	<h1>Nota Lunas</h1>
	<p style='text-align:right'><?php echo $tanggal ?></p>
	<p style='text-align:left'><?php echo $id ?></p>
	<div style='display:flex;'>
		<div class='column' style='text-align:left'>
			<p>Member&ensp;:<?php echo $layanan['NAMA_PELANGGAN'] ?></p>
			<p>Phone&ensp;:<?php echo $layanan['PHONE_PELANGGAN'] ?></p>
		</div>
		<div class='column' style='text-align:right'>
			<p>CS&ensp;:<?php echo $layanan['NAMA_PEGAWAI'] ?></p>
			<p>Kasir&ensp;:<?php echo $layanan['NAMA_KASIR'] ?></p>
		</div>
	</div>
	<hr>
	<h2 style='text-align:center'><?php echo $type ?></h2>
	<hr>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Jasa</th>
				<th>Harga</th>
				<th>Jumlah</th>
				<th>Sub Total</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
	  	<?php foreach($users as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list->NAMA_LAYANAN ?></td>
			<td  style='text-align:center'><?php echo "Rp " . number_format($list->SUB_TOTAL_LAYANAN,2,',','.') ?></td>
			<td  style='text-align:center'><?php echo $list->JUMLAH_DETIL_LAYANAN ?></td>
			<td  style='text-align:center'><?php echo "Rp " . number_format($list->SUB_TOTAL_LAYANAN,2,',','.') ?></td>
    	</tr>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
			<p>Sub Total&ensp;:<?php echo "Rp " . number_format($layanan['SUBTOTAL_TRANSAKSI_LAYANAN'],2,',','.') ?></p>
			<p>Diskon&ensp;:<?php echo "Rp " . number_format($layanan['DISKON_LAYANAN'],2,',','.') ?></p>
			<p>TOTAL&ensp;:<?php echo "Rp " . number_format($layanan['TOTAL_TRANSAKSI_LAYANAN'],2,',','.') ?></p>
		</div>
	</div>
	
</div>
<!-- 

<div class='center'>
	<hr>
	<h2 style='text-align:center'><?php echo $type ?></h2>
	<hr>
</div>
<div class='center'>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Jasa</th>
				<th>Harga</th>
				<th>Jumlah</th>
				<th>Sub Total</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
	  	<?php foreach($users as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list->NAMA_LAYANAN ?></td>
			<td  style='text-align:center'><?php echo $list->SUB_TOTAL_LAYANAN ?></td>
			<td  style='text-align:center'><?php echo $list->JUMLAH_DETIL_LAYANAN ?></td>
			<td  style='text-align:center'><?php echo $list->SUB_TOTAL_LAYANAN ?></td>
    	</tr>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
</div>
<div class="center">
	<br>
	<hr>
	<div class='column' style='text-align:right'>
		<p>Sub Total&ensp;:<?php echo $layanan['SUBTOTAL_TRANSAKSI_LAYANAN'] ?></p>
		<p>Diskon&ensp;:<?php echo $layanan['DISKON_LAYANAN'] ?></p>
		<p>TOTAL&ensp;:<?php echo $layanan['TOTAL_TRANSAKSI_LAYANAN'] ?></p>
	</div>
</div> -->
</body>
</html>