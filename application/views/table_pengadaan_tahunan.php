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
	<h1>LAPORAN PENGADAAN TAHUNAN</h1>
	<div>Tahun : <?php echo $year ?></div>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Bulan</th>
				<th>Jumlah Pengeluaran</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
    	<?php $total=0; ?>
	  	<?php foreach($pengadaan as $list): ?>
	  	<?php setlocale(LC_MONETARY, 'id_ID'); ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list['BULAN'] ?></td>
			<?php $hasil = "Rp " . number_format($list['JUMLAH PENGELUARAN'],2,',','.');?>
			<td  style='text-align:center'> <?php echo $hasil ?></td>
    	</tr>
    	<?php $total = $total + intval($list['JUMLAH PENGELUARAN']) ?>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	
	<br>
	<hr>
	<div style='display:flex;'>
		<div class='column'>
		    <?$hasil = "Rp " . number_format($total,2,',','.');?>
			<h3 style='text-align:center'>Total <?php echo $hasil ?></h3>
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