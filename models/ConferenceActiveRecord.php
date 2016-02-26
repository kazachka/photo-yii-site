<?php

namespace app\models;

class ConferenceActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'conference';
	}
}
