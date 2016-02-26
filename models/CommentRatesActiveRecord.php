<?php

namespace app\models;

use Yii;

class CommentRatesActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'commentsRates';
	}
	
	public static function getRates($commentId){
		$rate = static::find()
					->where(['commentId' => $commentId])
					->sum('rate');
		return $rate;
	}
}
