<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
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
			[['username', 'password', 'fio', 'pol', 'email'], 'required'],
			['password', 'compare'],
			['pol', 'boolean'],
			['photo', 'file'],
			['birthday', 'date'],
			['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
			'username' => 'Логин',
			'password' => 'Повторите пароль',
			'password_repeat' => 'Пароль',
			'fio' => 'ФИО',
			'pol' => 'Пол',
			'photo' => 'Фото',
			'country' => 'Страна',
			'place' => 'Место проживания',
            'verifyCode' => 'Код проверки',
        ];
    }

	public function register(){}
}
