<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

	<script type="text/css">
	td{
		padding-bottom:100px;
		color:red;
	}
	</script>
</head>
<body>

<?php $this->beginBody() ?>
<div style="background:#1F1E1E;height:100%;width:190px;position:fixed; border:1px solid #000000">
<center style='margin-top:10px'>
	<img src='/basic3/web/bagi_tugas.png' style='width:160px'/>
	<hr style='width:70%'>
</center>

<table id='menunya' style='margin-left:20px;color:white'>
<tr>
	<td style='padding-bottom:10px;'>
		<a style='color:white' href='index.php?r=site/home'>HOME</a>
	</td>
</tr>
<tr>
	<td style='padding-bottom:10px;'>USERNAME : 
	<?php
	echo Yii::$app->user->isGuest ? " ? " : Yii::$app->user->identity->username;
	?>
	</td>
</tr>
<tr>
	<td style='padding-bottom:10px';>
	<?php
	echo Yii::$app->user->isGuest ? "<a style='color:white' href='index.php?r=site/login'>LOGIN</a>" : 
	"<a style='color:white' href='index.php?r=site/logout'>LOGOUT</a>";
	?>
	</td>
</tr>
</table>

</div>
    <div class="wrap" >
        <div class="container" style='width:85%;padding-top:0px;float:right'>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
