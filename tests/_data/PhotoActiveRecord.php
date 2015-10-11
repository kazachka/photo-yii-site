<?php

namespace data;

class PhotoActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'photo';
	}
}
