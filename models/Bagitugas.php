<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bagi_tugas".
 *
 * @property integer $id_bagi_tugas
 * @property string $tgl_mulai
 * @property string $tgl_akhir
 * @property string $pembagian_tugas
 * @property string $tgl_selesai
 * @property integer $id_tugas
 * @property integer $id_user
 */
class Bagitugas extends \yii\db\ActiveRecord
{
	
	public $max_id;
	public $jumlah_user;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bagi_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_bagi_tugas', 'tgl_mulai', 'tgl_akhir', 'pembagian_tugas', 'id_tugas', 'id_user'], 'required'],
            [['id_bagi_tugas', 'id_tugas', 'id_user'], 'integer'],
            [['tgl_mulai', 'tgl_akhir', 'tgl_selesai'], 'safe'],
            [['pembagian_tugas'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_bagi_tugas' => 'id bagi tugas',
            'tgl_mulai' => 'Tgl Mulai (yyyy-mm-dd)',
            'tgl_akhir' => 'Tgl Akhir (yyyy-mm-dd)',
            'pembagian_tugas' => 'Pembagian Tugas',
            'tgl_selesai' => 'Selesai?',
            'id_tugas' => 'id tugas',
            'id_user' => 'id user',
        ];
    }
}
