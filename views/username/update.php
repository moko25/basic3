<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Username */

$this->title = 'Update Username: ' . ' ' . $model->id_user;
$this->params['breadcrumbs'][] = ['label' => 'Usernames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_user, 'url' => ['view', 'id' => $model->id_user]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="username-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
