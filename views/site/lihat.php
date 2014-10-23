<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Username;

/* @var $this yii\web\View */
/* @var $model app\models\Username */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- popup -->
	<link rel="stylesheet" type="text/css" href="/basic3/web/popup/jquery.modal.css">
	<script src="/basic3/web/popup/jquery-1.11.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="/basic3/web/popup/jquery.modal.js" type="text/javascript" charset="utf-8"></script>
	
	<script type='text/javascript'>	
	//hapus user di bagi_tugas
	function confirmDelete(id, url){
		//tombol OK diklik
		if(confirm("Yakin dihapus?") == true){
			window.location.href = 'index.php?r=site/delete&id='+id+'&url='+url;
		}else{//tombol Cancel diklik
			
		}			
	}	
	</script>
	
	<style type="text/css">
		select{
			width:300px;
			height:30px;
		}
	</style>

<!--directory -->
<ol class="breadcrumb" style="background:#eeeeee;margin-top:30px">
	<li>
		<a href="index.php?r=site/home"> 
			Home 
		</a>
	</li>
	<li class="active">
	lihat tugas
	</li>
</ol>
<hr style='margin-top:-10px'>	
<?php
	//tanggal sekarang
	date_default_timezone_set("Asia/Jakarta");	
	$date_now = date("Y-m-d");

	//tanggal awal mengerjakan tugas
	$date_start = str_replace("-", "/", $model2->tgl_mulai);
	$date_start = date('Y-m-d', strtotime($date_start));

	//tanggal akhir mengerjakan tugas
	$date_end = str_replace("-", "/", $model2->tgl_akhir);
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

	//ada username di bagi_tugas
	if($model2->total_user != 0){
		//buat progress project
		$progress_project = ($model2->finished_working / $model2->total_user) * 100;
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
	}else { //setting belum username di bagi_tugas
		if(date('Y-m-d') > $date_end){
			$progress_project = 0;
			$warna_progress = "progress-bar-danger"; 
		}else{
			$progress_project = 0; //lewat dari tanggal selesai project
			$warna_progress = ""; 			
		}
	}

	//kalo wktu berjalan == 0, text "n hari .." ga usah ditampilin, bgtu jg sbaliknya
	if($waktu_terlewati == 0 || date('Y-m-d') > $date_end) $text_wkt_trlewati = ""; 
	else $text_wkt_trlewati = "$waktu_terlewati hari ..";
	
	//nama project
	echo "
		<h2 style='margin-bottom:1px'>nama project : ".$model2->nama_tugas."</h2>
		<i>dibuat oleh : ".$model1->username."</i>
		<br><br>
		<table style='width:100%'>
			<tr>
				<td>".$model2->tgl_mulai."</td><td style='text-align:right'>".$model2->tgl_akhir."</td>
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
			<td><b>user yang mengerjakan</b> : ".$model2->total_user."</td>
			<td style='text-align:right'><b>Progress : </b>".$progress_project." % </td>
		</tr>
		</table>
		<hr>
		<p>&nbsp;</p>
	";
	
	echo '<table class="table">
		<thead>
			<tr><th>No</th><th>Nama User</th><th>Pembagian Tugas</th><th>Progress (hari)</th><th style="text-align:center">Aksi</th></tr>
		</thead>
	';
	$no = 1;
	
	//ada user di bagi_tugas
	if($model2->total_user != 0){
		
		//tabel user di bagi_tugas
		foreach($model4 as $index => $code){
			
			//tanggal sekarang
			date_default_timezone_set("Asia/Jakarta");	
			$date_now = date("Y-m-d");

			//tanggal awal mengerjakan tugas
			$date_start = str_replace("-", "/", $model3[$index]->tgl_mulai);
			$date_start = date('Y-m-d', strtotime($date_start));

			//tanggal akhir mengerjakan tugas
			$date_end = str_replace("-", "/", $model3[$index]->tgl_akhir);
			$date_end = date('Y-m-d', strtotime($date_end));
			
			//selisih waktu akhir dengan wktu awal krjain tugas
			$date1=date_create($date_end);
			$date2=date_create($date_start);
			$diff=date_diff($date1,$date2);
			$model3[$index]->id_bagi_tugas;
			$lama_mengerjakan = $diff->format("%a");
			
			//selisih waktu akhir dengan wktu sekarang
			$date1=date_create($date_end);
			$date2=date_create($date_now);
			$diff=date_diff($date1,$date2);
			
			$waktu_tersisa = $diff->format("%a");
			$waktu_terlewati = $lama_mengerjakan-$waktu_tersisa;
			
			//buat progress loading waktu berjalan
			if($lama_mengerjakan != 0){
				$progress_time = ($waktu_terlewati / $lama_mengerjakan) * 100;	
			}else{ //lama pengerjaan 0 hari, (dari 2014-10-20 sampai 2014-10-20)
				$progress_time = 100;
			}
			
			//warna progress user di bagi_tugas
			if($model3[$index]->tgl_selesai != null){
				$warna_progress = "progress-bar-success";
			}else if(date('Y-m-d') <= $model3[$index]->tgl_akhir){ //user masih punya waktu ngerjain
				$warna_progress = "";
			}else if(date('Y-m-d') > $model3[$index]->tgl_akhir){ //user tidak selesai ngerjain di bagi_tugas
				$warna_progress = "progress-bar-danger";
			}
				
				echo "<tr>
					<td>$no</td>
					<td>
						".$code->username."
					</td>
					<td style='width:500px;'>".$model3[$index]->pembagian_tugas."</td>
					<td>
						<div class='progress' style='height:18px;margin-bottom:1px'>
						  <div class='progress-bar $warna_progress' role='progressbar' aria-valuenow='60' aria-valuemin='0' aria-valuemax='100' style='width: $progress_time%;'>
							<span class='sr-only'>60% Complete</span>
						  </div>
						  <span style='font-size:8pt'>&nbsp; $waktu_tersisa hari lagi ..</span>
						</div>
						<table style='width:100%;font-size:9pt'><tr>
						<td>".$model3[$index]->tgl_mulai."</td>
						<td style='text-align:right;'>".$model3[$index]->tgl_akhir."</td>
						</tr></table>
					</td>
					";
					//yg bisa edit cuma pembuatanya
					if(Yii::$app->user->identity->username == $model1->username){
						echo"
							<td style='width:190px;text-align:center'>
								<a href='index.php?r=site/update&id=".$model3[$index]->id_bagi_tugas."&iu=".$model1->id_user."'>Edit</a>
								|
								<a style='cursor:pointer' onclick='confirmDelete(".$model3[$index]->id_bagi_tugas.",".$_GET['id'].")'>Hapus</a>
							</td>
						</tr>";
					}else{
						echo "
							<td style='width:190px;text-align:center'>
								Edit
								|
								Hapus
							</td>
						</tr>";						
					}
				$no++;
				
		}
	}
	echo '</table>';
	
	//tombol bagi tugas
	if(Yii::$app->user->identity->username == $model1->username){
		echo "
			<p>&nbsp;</p>
			<a href='#ex1' rel='modal:open'><button type='button' class='btn btn-warning btn-lg'>Bagi Tugas</button></a>
		";
	}
?>

<!-- POPUP tambah user; Modal HTML embedded directly into document -->
<div id="ex1" style="display:none;overflow-y: scroll;-webkit-border-radius: 0px; 
	height:490px;-moz-border-radius: 0px; border-radius: 0px;">
	<div class="username-form">
		<p style='margin-bottom:-5px'>&nbsp;</p>
		<?php $form = ActiveForm::begin(); ?>
		
		<b>Nama User : </b>
		<?php if($model2->total_user != 0){
			$sql = "SELECT * from username where id_user not in ($join) order by username ASC"; 
		?>
			<?=
			Html::activeDropDownList($model, 'id_user',
				ArrayHelper::map(Username::findBySql($sql)->all(), 'id_user', 'username')) 
			?>
		<?php }else{ ?>
				<?=
				Html::activeDropDownList($model, 'id_user',
					ArrayHelper::map(Username::find()->all(), 'id_user', 'username')) 
				?>
		<?php } ?>
		
		<p style='margin-bottom:5px'>&nbsp;</p>
		
		<div style='display:none'><?= $form->field($model, 'id_bagi_tugas')->textInput(['value' => $model5->max_id ]) ?></div>
		<?= $form->field($model, 'pembagian_tugas')->textInput() ?>	
		<?= $form->field($model, 'tgl_mulai')->textInput() ?>		
		<?= $form->field($model, 'tgl_akhir')->textInput() ?>					
		<div style='display:none'><?= $form->field($model, 'tgl_selesai')->textInput() ?></div>
		<div style='display:none'><?= $form->field($model, 'id_tugas')->textInput(['value' => $_GET['id'] ]) ?></div>		

<?php
/*
$form->field($model, 'date')->widget(
    DatePicker::className(), [
        // inline too, not bad
        'inline' => true, 
        // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd-M-yyyy'
        ]
]);
*/
?>
	<p>&nbsp;</p><p>&nbsp;</p>
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>    
  <!--  <a href="#" rel="modal:close">Close</a> or press ESC</p> -->
</div>
