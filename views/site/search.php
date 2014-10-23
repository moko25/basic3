
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>
<u><h4>
	Hasil Pencarian : 
	<?php 
		echo $keyword; 
		if(count($model) == 0){
			 echo " , <span style='color:red;'>tidak ditemukan</span>";
	?>
		<p><br>
			<!-- form search -->
			<?php $form = ActiveForm::begin(['action' => 'index.php?r=site/home']); ?>
				<?= $form->field($search, 'search')->textInput(['style' => 'display:inline-block', 'placeholder' => 'search tugas..']) ?>		
			<?php ActiveForm::end(); ?>	
		</p>
	<?php
		}
	?>
</h4></u>
<?php
//handle 2 parameters, $model and $model1
//$code = $model
//$index = indexnya si $model
//$model1[$index] = indexnya si $model dipake sama si $model1
//index $model dan $model1 HARUS SAMA BANYAKNYA!
//$model1 = cari nama tugas + jumlah user + finished working
//$model = cari nama username yg buat tugas project
foreach($model as $data){
	
	//tanggal sekarang
	date_default_timezone_set("Asia/Jakarta");	
	$date_now = date("Y-m-d");

	//tanggal awal mengerjakan tugas
	$date_start = str_replace("-", "/", $data->tgl_mulai);
	$date_start = date('Y-m-d', strtotime($date_start));

	//tanggal akhir mengerjakan tugas
	$date_end = str_replace("-", "/", $data->tgl_akhir);
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
	if(date('Y-m-d') <= $date_end){
		$progress_time = ($waktu_terlewati / $lama_mengerjakan) * 100;
	}else{
		$progress_time = 100;
	}
	
	//buat progress project
	if(	$data->finished_working == 0){
		$progress_project = 0;
	}else{
		$progress_project = ($data->finished_working / $data->total_user) * 100;
	}
	
	$warna_progress = "";

	//project selesai
	if($progress_project == 100 && 
		(date('Y-m-d') <= $date_end || 
		date('Y-m-d') > $date_end) ){
			$warna_progress = "progress-bar-success";
	//project dalam proses
	}else if($progress_project < 100 && date('Y-m-d') <= $date_end){
		$warna_progress = "";
	//project lewat dari tanggal, dan gak selesai
	}else if($progress_project < 100 && date('Y-m-d') > $date_end){
		$warna_progress = "progress-bar-danger";
	}
	
	//kalo wktu berjalan == 0, text "n hari .." ga usah ditampilin, bgtu jg sbaliknya
	if($waktu_terlewati == 0 || date('Y-m-d') > $date_end) $text_wkt_trlewati = ""; 
	else $text_wkt_trlewati = "$waktu_terlewati hari ..";


	//buat loading progress time
	echo "
		<a href='index.php?r=site/lihat&id=".$data->id_tugas."'>
			<h2 style='margin-bottom:1px;display:inline-block'>nama project : ".$data->nama_tugas."</h2>
		</a>
		<br>
		<br>
		<table style='width:100%'>
			<tr>
				<td>".$data->tgl_mulai."</td><td style='text-align:right'>".$data->tgl_akhir."</td>
			</tr>
		</table>
		<div class='progress' style='height:50px;'>
			<div class='progress-bar $warna_progress progress-bar-striped' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: $progress_time%;'>
				<div style='margin-top:15px'>$text_wkt_trlewati</div>			
			</div>
			<div style='float:left;margin-top:17px;font-size:9pt;'>&nbsp; $waktu_tersisa hari lagi ..</div>			
		</div>
		<table style='width:100%'>
		<tr>
			<td><b>user yang mengerjakan</b> : ".$data->total_user."</td>
			<td style='text-align:right'><b>Progress : </b>".$progress_project." % </td>
		</tr>
		</table>
		<hr>";	
}
?>

</body>
</html>
