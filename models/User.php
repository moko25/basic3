<?php
namespace app\models;


class User extends \yii\base\Object implements \yii\web\IdentityInterface {

//harus sesuai dengan field di tabel username
public $id_user;
public $nama;
public $username;
public $password;
public $tipe_akses;

public $authKey;
public $accessToken;

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id) {
		
		$dbUser = Username::find()
				->where([
					"id_user" => $id
				])
				->one();
				
		if (!count($dbUser)) {
			return null;
		}
		return new static($dbUser);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $userType = null) {
				
		if ($token != "100-token") {
			return null;
		}
		return new static($dbUser);
	}

	/**
	 * Finds user by username
	 *
	 * @param  string      $username
	 * @return static|null
	 */
	public static function findByUsername($username) {	
		$dbUser = Username::find()
				->where([
					"username" => $username
				])
				->one();
				
		if (!count($dbUser)) {
			return null;
		}
		return new static($dbUser);
	}

	/**
	 * @inheritdoc
	 */
	public function getId() {
		return $this->id_user;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return $this->authKey === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param  string  $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password) {
		return $this->password === $password;
	}

}
