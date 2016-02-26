<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $fio;
    public $pol;
    public $photo;
    public $birthday;
    public $country;
    public $place;
    public $email;
	public $verifyCode;
	
    public function rules()
    {
        return [
			[['username', 'password', 'password_repeat', 'fio', 'pol', 'email'], 'required'],
			['password', 'compare'],
			['pol', 'boolean'],
			['photo', 'file', 'extensions' => 'jpg, gif, png', 'maxFiles' => 1],
			['birthday', 'date'],
			['email', 'email'],
			[['country', 'place'], 'string'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
			'username' => Yii::t('app', 'Логин'),
			'password' => Yii::t('app', 'Повторите пароль'),
			'password_repeat' => Yii::t('app', 'Пароль'),
			'fio' => Yii::t('app', 'ФИО'),
			'pol' => Yii::t('app', 'Пол'),
			'photo' => Yii::t('app', 'Фото'),
			'country' => Yii::t('app', 'Страна'),
			'place' => Yii::t('app', 'Место проживания'),
            'verifyCode' => Yii::t('app', 'Код проверки'),
        ];
    }

	public function register(){
		if($this->validate()){
			$user = new User();
			$user->username = $this->username;
			$user->password = $this->password;
			$user->fio = $this->fio;
			$user->pol = $this->pol;
			$user->birthday = $this->birthday;
			$user->country = $this->country;
			$user->place = $this->place;
			$user->email = $this->email;
			$user->registerDate = date('Y-m-d H:i:s');
			Yii::trace($this->birthday);
			Yii::trace($user->birthday.' '.$user->registerDate);
			if(!$user->save()){
				return false;
			}
			if(!Yii::$app->user->login(User::findByUsername($this->username), 0)){
				return false;
			}
			$photo = new PhotoActiveRecord();
			$photo->userId = Yii::$app->user->identity->id;
			$photo->photo = file_get_contents($this->photo->tempName);
			$imagick = new \Imagick();
			$imagick->readImageBlob($photo->photo);
			foreach($imagick as $frame){
				$frame->thumbnailImage(800, 0);
			}
			$photo->thumbnail = $imagick->getImagesBlob();
			$photo->posted = date('Y-m-d H:i:s');
			$photo->name = $this->fio;
			if(!$photo->save()){
				return false;
			}
			$user->photo = $photo->id;
			$user->save();
			return true;
		}
		return false;
	}
}
