<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

//load model
use app\models\LoginForm;
use app\models\Tugas;
use app\models\Username;
use app\models\Bagitugas;
use app\models\Search;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
	//cek udh login atau belum dimari
	public function behaviors()
	{
		return [
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'only' => ['home', 'lihat', 'update', 'delete', 'logout'],
				'rules' => [
					[
						'actions' => ['home', 'lihat', 'update', 'delete', 'logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			]
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			]
		];
	}
	
	//khusus dari form login langsung ke sini
	public function actionLogin()
	{

		if (!\Yii::$app->user->isGuest) {			
			$this->goHome();
		}

		$model = new LoginForm();
		if ($model->load($_POST) && $model->login()) {
			//khusus dari form login, jika berhasil login maka ke if ini
			return $this->redirect('index.php?r=site/home');
		} else {
			//echo "disini";
			
			return $this->render('login', [
				'model' => $model,
			]);			
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}

	/**
	 * Allows us to view records
	 */
	public function actionIndex($id=NULL)
	{
		//redirect dari index ke login
		 return $this->redirect('index.php?r=site/login');
	}

	public function actionHome($id=null){

//======= download PDF
		if($id != NULL){
			
			$sql = "SELECT * from tugas where id_tugas = $id";
			$model = Tugas::findBySql($sql)->one();
			
			//cari username yg buat tugas
			$sql1 = "SELECT username.username from username 
				inner join tugas on (username.id_user = tugas.id_user) where id_tugas = $id";
			$model1 = Username::findBySql($sql1)->one();			
			
			
			//cari nama jumlah user yg ikut ngrjain tugas
			$sql2 = "SELECT tugas.id_tugas, tugas.nama_tugas, tugas.tgl_mulai, tugas.tgl_akhir, count(bagi_tugas.id_tugas) as total_user, 
				count(bagi_tugas.tgl_selesai) as finished_working
				from tugas left outer join bagi_tugas on (tugas.id_tugas = bagi_tugas.id_tugas) where tugas.id_tugas = $id
				group by tugas.id_tugas order by tugas.id_tugas DESC";					
			$model2 = Tugas::findBySql($sql2)->one();
		
			//buat progress project
			if(	$model2->finished_working == 0){
				$progress_project = 0;
			}else{
				$progress_project = ($model2->finished_working / $model2->total_user) * 100;
			}

			//dapetin nama username yg ikut ngrjain tugas
			$usernamenya = "";
			if($model2->total_user != 0){
				//dapetin id_user di tabel bagi_tugas
				$sql3 = "SELECT * from bagi_tugas where id_tugas = $id order by id_user ASC";
				$model3 = Bagitugas::findBySql($sql3)->all();
				$list_user = array();
				$i = 0;
				foreach($model3 as $data){
					$list_user[$i] = $data->id_user;
					$i++;
				}

				//dapetin username di tabel username
				if(count($list_user) != 0){
					$join = join(',', $list_user);
					$sql4 = "SELECT * from username where id_user in ($join) order by id_user ASC";
					$model4 = Username::findBySql($sql4)->all();
				}else{
					$join = null;
					$model4 = 0; //belum ada username di bagi_tugas
				}
				
				foreach($model4 as $index => $code){
					$usernamenya .= "<li>".$code->username.
									"<br>          pembagian tugas : ".$model3[$index]->pembagian_tugas
									."</li>";
				}
			}//user != 0
			
			//buat PDFnya
			$html =
			'<html><head>
				<style>
					h1 { margin-bottom:8px }
			
				</style>
			</head>
			<body>
				<h1>'.$model2->nama_tugas.'</h1>
				<b>dibuat oleh : </b>'.$model1->username.'<br>
				<b>tanggal project : </b>'.$model2->tgl_mulai.' / '.$model2->tgl_akhir.'<br>
				<b>progress project : </b>'.$progress_project.'%
				<br><br><hr>user yang mengerjakan : '.$model2->total_user.'<br><br>'.
				$usernamenya.'
			</body></html>';
			
			$arr = array('file' => $html);
			$pdf_json = json_encode($arr);

			//cek akses untuk download PDF
			$id_user = Yii::$app->user->identity->id_user;
			$sql_pdf = "SELECT tipe_akses from username where id_user = $id_user";
			$model_pdf = Username::findBySql($sql_pdf)->one();
			
			if($model_pdf->tipe_akses == "admin"){
				echo "<script>window.location.href = 'http://localhost/basic3/index.php?pdf=$pdf_json';
				alert('di download!');
				</script>";
			}else{
				echo "<script>alert('akses hanya untuk admin');</script>";
			}

			//return $this->redirect('index.php?r=site/home');
		}
		
		
//============ UNTUK HALAMAN HOME				
		//cari nama username yg buat tugas project
		$sql = 'SELECT username.username from username inner join tugas on (username.id_user = tugas.id_user)
				order by tugas.id_tugas DESC';
				
		$model = Username::findBySql($sql)->all();
		
		//cari nama tugas + jumlah user + finished working
		$sql1 = 'SELECT tugas.id_tugas, tugas.nama_tugas, tugas.tgl_mulai, tugas.tgl_akhir, count(bagi_tugas.id_tugas) as total_user, 
				count(bagi_tugas.tgl_selesai) as finished_working
				from tugas left outer join bagi_tugas on (tugas.id_tugas = bagi_tugas.id_tugas) 
				group by tugas.id_tugas order by tugas.id_tugas DESC';
				
		$model1 = Tugas::findBySql($sql1)->all();
		
		//search tugas
		$search = new Search();		
		if ($search->load(Yii::$app->request->post()) ) {
			$keyword = $search['search'];

			$sql = "SELECT tugas.id_tugas, tugas.nama_tugas, tugas.tgl_mulai, tugas.tgl_akhir, 
			count(bagi_tugas.id_tugas) as total_user, count(bagi_tugas.tgl_selesai) as finished_working 
			from tugas left outer join bagi_tugas on (tugas.id_tugas = bagi_tugas.id_tugas) 
			where tugas.nama_tugas like '%$keyword%' group by tugas.id_tugas order by tugas.id_tugas DESC";
			$model = Tugas::findBySql($sql)->all();
			
			return $this->render('search', ['model' => $model, 'keyword' => $keyword, 'search' => $search]);			
		}
		
		$model2 = new Tugas();
		//ada input data Tugas baru
		if ($model2->load(Yii::$app->request->post()) ) {
			$model2['tgl_selesai'] = null;	
			if($model2->save()){
					return $this->redirect('index.php?r=site/home');
			}
			
            //return $this->redirect(['view', 'id' => $model->id_user]);
        } else {
			//cari maksimal id_tugas+1
			$sql3 = "SELECT max(id_tugas)+1 as max_id from tugas";
			$model3 = Tugas::findBySql($sql3)->one();
			
            return $this->render('home', ['model' => $model, 'model1' => $model1, 'model2' => $model2,
            'model3' => $model3, 'search' => $search] );
        }			
	}
	
	public function actionLihat($id){
        $model = $this->findModel($id);

		//cari nama username yg buat tugas project
		$sql = "SELECT id_user from tugas where id_tugas = $id";
		$model = Tugas::findBySql($sql)->one();
		$idUser = $model->id_user;
		$sql1 = "SELECT username, id_user from username where id_user = $idUser";
		$model1 = Username::findBySql($sql1)->one();
		//echo $model1->username;
		
		//cari nama tugas + jumlah user + finished working
		$sql2 = "SELECT tugas.id_tugas, tugas.nama_tugas, tugas.tgl_mulai, tugas.tgl_akhir, count(bagi_tugas.id_tugas) as total_user, 
				count(bagi_tugas.tgl_selesai) as finished_working
				from tugas left outer join bagi_tugas on (tugas.id_tugas = bagi_tugas.id_tugas) where tugas.id_tugas = $id
				group by tugas.id_tugas order by tugas.id_tugas DESC";

		$model2 = Tugas::findBySql($sql2)->one();
		
		//dapetin id_user di tabel bagi_tugas
		$sql3 = "SELECT * from bagi_tugas where id_tugas = $id order by id_user ASC";
		$model3 = Bagitugas::findBySql($sql3)->all();
		$list_user = array();
		$i = 0;
		foreach($model3 as $data){
			$list_user[$i] = $data->id_user;
			$i++;
		}

		//dapetin username di tabel username
		if(count($list_user) != 0){
			$join = join(',', $list_user);
			$sql4 = "SELECT * from username where id_user in ($join) order by id_user ASC";
			$model4 = Username::findBySql($sql4)->all();
		}else{
			$join = null;
			$model4 = 0; //belum ada username di bagi_tugas
		}
		
		//maksimal id_bagi_tugas
		$sql5 = "SELECT max(id_bagi_tugas)+1 as max_id from bagi_tugas";
		$model5 = Bagitugas::findBySql($sql5)->one();
		
		$model5->max_id;
		$model = new Bagitugas();

		//save user ke bagi_tugas
		if($model->load(Yii::$app->request->post())){
			$model['tgl_selesai'] = null;			
			if($model->save()){
				echo "
				<script>alert('berhasil ditambah');
				window.location.href = 'index.php?r=site/lihat&id=$model[id_tugas]';
				</script>";
				//return $this->redirect("index.php?r=site/lihat&id=$model[id_tugas]");
			}
		}else{
		//default loading
			return $this->render('lihat', ['model1' => $model1, 'model2' => $model2, 'model3' => $model3, 
					'model4' => $model4, 'model5' => $model5, 'join' => $join, 'model' => $model] );
        }
	}
	
	public function actionUpdate($id){
        $model = $this->findModel1($id);

        if ($model->load(Yii::$app->request->post())) {
			date_default_timezone_set("Asia/Jakarta");	
			$date_now = date("Y-m-d");
			
			if($model['tgl_selesai'] == 0){ //user belum selesai ngrjain
				$model['tgl_selesai'] = null;
			}else{ //user selesai ngrjain
				$model['tgl_selesai'] = $date_now; //update tanggal selesai ngerjain
			}
			
			if($model->save())
			echo "<script>window.location.href = 'index.php?r=site/lihat&id=$model[id_tugas]'</script>";
            //return $this->redirect(['view', 'id' => $model->id_user]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
	}

	/**
	 * Handles deletion of our models
	 * @param int $id 	The $id of the model we want to delete
	 */
	public function actionDelete($id=NULL, $url=NULL)
	{
		if($id != NULL && $url!= NULL){
			$this->findModel1($id)->delete();
			echo "<script>window.location.href = 'index.php?r=site/lihat&id=$url';</script>";
		}		
        		
		/*
		$model = $this->loadModel($id);

		if (!$model->delete())
			Yii::$app->session->setFlash('error', 'Unable to delete model');

		$this->redirect($this->createUrl('site/index'));
		*/
	}

	/**
	 * Loads our model and throws an exception if we encounter an error
	 * @param int $id 	The $id of the model we want to delete
	 */
	private function loadModel($id)
	{
		$model = Posts::find($id);

		if ($model == NULL)
			throw new HttpException(404, 'Model not found.');

		return $model;
	}
	//untuk cek id Tugas
    protected function findModel($id)
    {
        if (($model = Tugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }	
    
    //untuk cek id Bagitugas
    protected function findModel1($id)
    {
        if (($model = Bagitugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
}
