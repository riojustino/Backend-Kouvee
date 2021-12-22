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
	<h1>LAPORAN PENDAPATAN BULANAN</h1>
	<div>Bulan : <?php echo $month ?></div>
	<div>Tahun : <?php echo $year ?></div>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Jasa Layanan</th>
				<th>Harga</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
    	<?php $totalL=0; ?>
	  	<?php foreach($layanan as $list): ?>
	  	<?php setlocale(LC_MONETARY, 'id_ID'); ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list['NAMA JASA LAYANAN'] ?></td>
			<?php $hasil = "Rp " . number_format($list['HARGA'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $hasil ?></td>
    	</tr>
    	<?php $totalL = $totalL + intval($list['HARGA']) ?>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
		    <?$hasilL = "Rp " . number_format($totalL,2,',','.');?>
			<h3 style='text-align:right'>Total <?php echo $hasilL ?></h3>
		</div>
	</div>
	
	<br>
	<hr>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Produk</th>
				<th>Harga</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
    	<?php $totalP=0; ?>
	  	<?php foreach($produk as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list['NAMA PRODUK'] ?></td>
			<?php $hasil = "Rp " . number_format($list['HARGA'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $hasil ?></td>
    	</tr>
    	<?php $totalP = $totalP + intval($list['HARGA']) ?>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
		    <?$hasilP = "Rp " . number_format($totalP,2,',','.');?>
			<h3 style='text-align:right'>Total <?php echo $hasilP ?></h3>
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