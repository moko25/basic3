<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "username".
 *
 * @property integer $id_user
 * @property string $nama
 * @property string $username
 * @property string $password
 * @property string $tipe_akses
 */
class Username extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'username';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'nama', 'username', 'password', 'tipe_akses'], 'required'],
            [['id_user'], 'integer'],
            [['nama'], 'string', 'max' => 100],
            [['username', 'password'], 'string', 'max' => 50],
            [['tipe_akses'], 'string', 'max' => 6],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'nama' => 'Nama',
            'username' => 'Username',
            'password' => 'Password',
            'tipe_akses' => 'Tipe Akses',
        ];
    }
}
