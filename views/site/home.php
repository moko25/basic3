
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<!-- popup -->
	<link rel="stylesheet" type="text/css" href="/basic3/web/popup/jquery.modal.css">
	<script src="/basic3/web/popup/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="/basic3/web/popup/jquery.modal.js" type="text/javascript" charset="utf-8"></script>
					
<!-- tombol buat tugas dan search -->
<table style='width:100%;margin-top:20px;'>
	<tr>
		<td>
			<a href='#ex1' rel='modal:open'><button type='button' class='btn btn-warning btn-lg'>Tambah Tugas</button></a>
		</td>
		<td>
			
	<!-- form search -->
	<?php $form = ActiveForm::begin(['action' => 'index.php?r=site/home']); ?>
		<?= $form->field($search, 'search')->textInput(['style' => 'display:inline-block', 'placeholder' => 'search tugas..']) ?>		
	<?php ActiveForm::end(); ?>	
			
			<!--
			<form action="index.php?r=site/lihat">
				<div class="input-group" style='width:60%;float:right'>
					   <input type="search" placeholder="Search Tugas ..." class="form-control" id="input_search_atas"/>
						   <div class="input-group-btn">
						   <button class="btn btn-default" id="search_article_atas" type="submit">
								<span class="glyphicon glyphicon-search"></span>
						   </button>
							</div>
			-->
				</div>
			</form>
		</td>
	</tr>
</table>

<!--directory -->
<ol class="breadcrumb" style="background:#eeeeee;margin-top:0px">
	<li>
		<a href="index.php?r=site/home"> 
			Home 
		</a>
	</li>
	<li class="active">..</li>
</ol>

<?php
//handle 2 parameters, $model and $model1
//$code = $model
//$index = indexnya si $model
//$model1[$index] = indexnya si $model dipake sama si $model1
//index $model dan $model1 HARUS SAMA BANYAKNYA!
//$model1 = cari nama tugas + jumlah user + finished working
//$model = cari nama username yg buat tugas project
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
	if(date('Y-m-d') <= $date_end){
		$progress_time = ($waktu_terlewati / $lama_mengerjakan) * 100;
	}else{
		$progress_time = 100;
	}
	
	//buat progress project
	if(	$model1[$index]->finished_working == 0){
		$progress_project = 0;
	}else{
		$progress_project = ($model1[$index]->finished_working / $model1[$index]->total_user) * 100;
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
		<a href='index.php?r=site/lihat&id=".$model1[$index]->id_tugas."'>
			<h2 style='margin-bottom:1px;display:inline-block'>nama project : ".$model1[$index]->nama_tugas."</h2>
		</a>
		&nbsp;
		<a style='cursor:pointer' href='index.php?r=site/home&id=".$model1[$index]->id_tugas."'>
			<img src='/basic3/web/pdf.png' style='width:30px'/>
		</a>
		<br>
		<i>dibuat oleh : ".$code->username."</i>
		<br><br>
		<table style='width:100%'>
			<tr>
				<td>".$model1[$index]->tgl_mulai."</td><td style='text-align:right'>".$model1[$index]->tgl_akhir."</td>
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
			<td><b>user yang mengerjakan</b> : ".$model1[$index]->total_user."</td>
			<td style='text-align:right'><b>Progress : </b>".$progress_project." % </td>
		</tr>
		</table>
		<hr>";
}
?>

<!-- POPUP tambah user; Modal HTML embedded directly into document -->
<div id="ex1" style="display:none;overflow-y: scroll;-webkit-border-radius: 0px; 
	height:380px;-moz-border-radius: 0px; border-radius: 0px;">
	<div class="username-form">
		<p style='margin-bottom:-5px'>&nbsp;</p>
		<?php $form = ActiveForm::begin(); ?>

		<p style='margin-bottom:-25px'>&nbsp;</p>
		
		<div style='display:none'>
			<?= $form->field($model2, 'id_tugas')->textInput(['value' => $model3->max_id] ) ?>
		</div>
		<?= $form->field($model2, 'nama_tugas')->textInput() ?>	
		<?= $form->field($model2, 'tgl_mulai')->textInput() ?>		
		<?= $form->field($model2, 'tgl_akhir')->textInput() ?>					
		<div style='display:none'><?= $form->field($model2, 'tgl_selesai')->textInput() ?></div>
		<div style='display:none'>
			<?= $form->field($model2, 'id_user')->textInput(['value' => Yii::$app->user->identity->id_user] ) ?>
		</div>	

	<p>&nbsp;</p><p>&nbsp;</p>
	<div class="form-group">
		<?= Html::submitButton($model2->isNewRecord ? 'Create' : 'Update', ['class' => $model2->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>    
  <!--  <a href="#" rel="modal:close">Close</a> or press ESC</p> -->
</div>
</body>
</html>
