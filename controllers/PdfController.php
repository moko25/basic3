<?php
 
namespace app\controllers;
 
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
 
#We will include the pdf library installed by composer
use mPDF;
 
 
class PdfController extends Controller {
	
	public function actionPdf() {
		$mpdf = new mPDF;
		$mpdf->WriteHTML('<p>Hallo World</p>');
		$mpdf->Output();
		exit;
	}
}
