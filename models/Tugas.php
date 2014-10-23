<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tugas".
 *
 * @property integer $id_tugas
 * @property string $nama_tugas
 * @property string $tgl_mulai
 * @property string $tgl_akhir
 * @property string $tgl_selesai
 * @property integer $id_user
 */
class Tugas extends \yii\db\ActiveRecord
{
	//count(bagi_tugas.id_tugas) as total_user
	public $total_user;
	
	//count(bagi_tugas.tgl_selesai) as finished_working
	public $finished_working;
	
	public $max_id;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tugas', 'nama_tugas', 'tgl_mulai', 'tgl_akhir', 'id_user'], 'required'],
            [['id_tugas', 'id_user'], 'integer'],
            [['tgl_mulai', 'tgl_akhir', 'tgl_selesai'], 'safe'],
            [['nama_tugas'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tugas' => 'Id Tugas',
            'nama_tugas' => 'Nama Tugas',
            'tgl_mulai' => 'Tgl Mulai (yyyy-mm-dd)',
            'tgl_akhir' => 'Tgl Akhir (yyyy-mm-dd)',
            'tgl_selesai' => 'Tgl Selesai',
            'id_user' => 'Id User',
        ];
    }
}
