<?php

namespace app\models;

use Yii;

class CommentForm extends \yii\base\Model
{
	public $body;
	
	public function rules(){
		return [
			['body', 'required']
		];
	}
	
	public function attributeLabels(){
		return [
			'body' => 'Комментарий',
		];
	}
	
	public function post(){
		$comment = new CommentsActiveRecord();
		$comment->userId = Yii::$app->user->identity->id;
		$comment->photoId = Yii::$app->request->get('id');
		$comment->body = $this->body;
		$comment->created = date('Y-m-d H-i-s');
		return $comment->save();
	}
}
