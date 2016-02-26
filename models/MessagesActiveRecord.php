<?php

namespace app\models;

class MessagesActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'messages';
	}
}
