<?php
namespace app\controllers;
use yii\web\Controller;
use Yii;

class MyController extends Controller {
    public function actionPdf(){
        Yii::$app->response->format = 'pdf';

        //Can you it if needed to rotate the page
        Yii::$container->set(Yii::$app->response->formatters['pdf']['class'], [
            'orientation' => 'Landscape', // This value will be ignored if format is a string value.
            'beforeRender' => function($mpdf, $data) {},
            ]);

        $this->layout = '//print';
        return $this->render('myview', []);
    }
}
?>
