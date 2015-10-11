<?php

namespace data;

class CommentsActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'comments';
	}
}
