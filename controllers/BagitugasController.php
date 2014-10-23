<?php
namespace app\controllers;
use yii\web\Controller;

//load model
use app\models\Tugas;
use app\models\Username;

class BagitugasController extends Controller{
	
	function actionIndex(){
		
		//cari nama username yg buat tugas project
		$sql = 'SELECT username.username from username inner join tugas on (username.id_user = tugas.id_user)
				order by tugas.id_user DESC';
				
		$model = Username::findBySql($sql)->all();
		
		//cari nama tugas + jumlah user + finished working
		$sql1 = 'SELECT tugas.nama_tugas, tugas.tgl_mulai, tugas.tgl_akhir, count(bagi_tugas.id_tugas) as total_user, 
				count(bagi_tugas.tgl_selesai) as finished_working
				from tugas left outer join bagi_tugas on (tugas.id_tugas = bagi_tugas.id_tugas) 
				group by tugas.id_tugas order by tugas.id_tugas DESC';
				
		$model1 = Tugas::findBySql($sql1)->all();
		
		return $this->render('index', ['model' => $model, 'model1' => $model1]);
	}
}
