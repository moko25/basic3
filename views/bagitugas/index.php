<?php
//handle 2 parameters, $model and $model1
//$code = $model
//$index = indexnya si $model
//$model1[$index] = indexnya si $model dipake sama si $model1
//index $model dan $model1 HARUS SAMA BANYAKNYA!
foreach($model as $index => $code){
	
	//tanggal sekarang
	date_default_timezone_set("Asia/Jakarta");	
	$date_now = date("Y-m-d");

	//tanggal awal mengerjakan tugas
	$date_start = str_replace("-", "/", $model1[$index]->tgl_mulai);
	$date_start = date('Y-m-d', strtotime($date_start));

	//tanggal akhir mengerjakan tugas
	$date_end = str_replace("-", "/", $model1[$index]->tgl_akhir);
	$date_end = date('Y-m-d', strtotime($date_end));
	
	//selisih waktu akhir dengan wktu awal krjain tugas
	$date1=date_create($date_end);
	$date2=date_create($date_start);
	$diff=date_diff($date1,$date2);

	$lama_mengerjakan = $diff->format("%a");

	//selisih waktu akhir dengan wktu sekarang
	$date1=date_create($date_end);
	$date2=date_create($date_now);
	$diff=date_diff($date1,$date2);
	
	$waktu_tersisa = $diff->format("%a");
	$waktu_terlewati = $lama_mengerjakan-$waktu_tersisa;
	//buat progress loading waktu berjalan
	$progress_time = ($waktu_terlewati/$lama_mengerjakan)*100;
	
	//kalo wktu berjalan == 0, text "n hari .." ga usah ditampilin, bgtu jg sbaliknya
	if($waktu_terlewati == 0) $text_wkt_trlewati = ""; 
	else $text_wkt_trlewati = "$waktu_terlewati hari ..";
	
	echo "<h2 style='margin-bottom:1px'>nama project : ".$model1[$index]->nama_tugas."</h2>
		<i>dibuat oleh : ".$code->username."</i>
		<br><br>
		<table style='width:100%'>
			<tr>
				<td>".$model1[$index]->tgl_mulai."</td><td style='text-align:right'>".$model1[$index]->tgl_akhir."</td>
			</tr>
		</table>
		<div class='progress' style='height:50px;'>
			<div class='progress-bar progress-bar-striped' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: $progress_time%;'>
				<div style='margin-top:15px'>$text_wkt_trlewati</div>			
			</div>
			<div style='float:left;margin-top:17px;font-size:9pt;'>&nbsp; $waktu_tersisa hari lagi ..</div>			
		</div>
		<b>jumlah user</b> : ".$model1[$index]->total_user.
	" | <b>Finished Working : </b>".$model1[$index]->finished_working."<hr>";
}
?>
