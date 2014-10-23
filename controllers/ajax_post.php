<?php
namespace app\controllers;
use app\models\Bagitugas;

if(isset($_POST['id'])){
	
	$sql5 = "SELECT * from bagi_tugas where id_bagi_tugas = 5";
	$model5 = Bagitugas::findBySql($sql5)->one();
		
	echo "haha";
	//echo $_POST['id'];
	//echo count($model);
}
?>
