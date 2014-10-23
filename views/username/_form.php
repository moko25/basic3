<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Username */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="username-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_user')->textInput() ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'tipe_akses')->textInput(['maxlength' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
