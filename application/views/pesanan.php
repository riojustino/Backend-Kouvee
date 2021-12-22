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
        border-collapse: collapse;
	}
    td.tableyth{
		border-collapse: collapse;
        border: dashed 0px #000;
        padding: 4px;
	}
    
</style>
<body>
<div class='center'> 
	<img src="http://localhost/apikouvee/upload/kop.PNG"  style="width:100%">
	<hr>
	<h1>Surat Pemesanan</h1>
    <p style='text-align:right'><?php echo 'No : '. $id ?></p>
	<p style='text-align:right'><?php echo 'Tanggal : '.$tanggal ?></p>
	<table class='tableyth'>
        <tr>
            <td class='tableyth'>Kepada Yth :</td>
        </tr>
        <?php foreach($pemesanan as $list): ?>
		<tr>
			<td  class='tableyth' ><?php echo $list->NAMA_SUPPLIER ?></td>
    	</tr>
        <tr>
            <td  class='tableyth'><?php echo $list->ALAMAT_SUPPLIER ?></td>
        </tr>
        <tr>
            <td  class='tableyth'><?php echo '(027) '. $list->PHONE_SUPPLIER ?></td>
        </tr>
	  	<?php endforeach; ?>
    </table>
	<p style='text-align:start'>Mohon untuk disediakan produk-produk berikut ini:</p>
	<table  style='width: 100%'>
		<thead>
			<tr>
				<th >No</th>
				<th>Nama Produk</th>
				<th>Satuan</th>
				<th>Jumlah</th>
			</tr>
		</thead>
    	<?php $no=1; ?>
	  	<?php foreach($detil as $list): ?>
		<tr>
			<td  style='text-align:center'><?php echo $no ?></td>
			<td  style='text-align:center' ><?php echo $list->NAMA_PRODUK ?></td>
			<td  style='text-align:center'><?php echo $list->SATUAN_PRODUK ?></td>
            <td  style='text-align:center'><?php echo $list->JUMLAH_PESANAN ?></td>
    	</tr>
    	<?php $no++; ?>
	  	<?php endforeach; ?>
	</table>
	<br>
    <p style='text-align:right'><?php echo 'Dicetak Tanggal '.$tanggal ?></p>
</div>
</body>
</html>