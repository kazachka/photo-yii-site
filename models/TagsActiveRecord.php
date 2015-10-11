<?php

namespace app\models;

class TagsActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'tags';
	}
}
