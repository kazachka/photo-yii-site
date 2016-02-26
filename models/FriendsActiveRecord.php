<?php

namespace app\models;

class FriendsActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'friends';
	}
}
