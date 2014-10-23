<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//hanya bisa diakses sesuai id pembuatnya
if(Yii::$app->user->identity->id_user == $_GET['iu']){
?>

<ol class="breadcrumb" style="background:#eeeeee;margin-top:30px">
	<li>
		<a href="index.php?r=site/home"> 
			Home 
		</a>
	</li>
	<li class="active">
	update pembagian tugas
	</li>
</ol>
<hr style='margin-top:-10px'>	

<div class="username-form">

    <?php $form = ActiveForm::begin(); ?>
	
    <div style='display:none'><?= $form->field($model, 'id_bagi_tugas')->textInput() ?></div>

	<?= $form->field($model, 'tgl_mulai')->textInput() ?>
	
	<?= $form->field($model, 'tgl_akhir')->textInput() ?>
	
	<?= $form->field($model, 'pembagian_tugas')->textInput() ?>

	<?php
	$arr = array('tidak', 'ya');
	echo $form->field($model, 'tgl_selesai')
        ->dropDownList(
            $arr,           // Flat array ('id'=>'label')
            ['prompt'=>'']    // options
        );
	?>
	
	 <div style='display:none'><?= $form->field($model, 'id_tugas')->textInput() ?></div>
	
     <div style='display:none'><?= $form->field($model, 'id_user')->textInput(['maxlength' => 100]) ?></div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php } ?>
