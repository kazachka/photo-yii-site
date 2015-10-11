<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $fio;
    public $pol;
    public $birthday;
    public $country;
    public $place;
    public $email;
	
    public function rules()
    {
        return [
			['pol', 'boolean'],
			['birthday', 'date'],
			['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
			'fio' => 'ФИО',
			'pol' => 'Пол',
			'country' => 'Страна',
			'place' => 'Место проживания',
        ];
    }

	public function change(){
		
	}
}
