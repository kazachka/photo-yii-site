<?php

namespace app\models;

use Yii;

class PhotoRatesActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'photoRates';
	}
	
	public static function getRates($photoId){
		$rate = static::find()
					->where(['photoId' => $photoId])
					->sum('rate');
		return $rate;
	}
}
