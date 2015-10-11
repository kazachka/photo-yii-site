<?php

namespace app\models;

class CommentsActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'comments';
	}
}
