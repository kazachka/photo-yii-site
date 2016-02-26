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
			'body' => Yii::t('app', 'Сообщение'),
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
	
	public function update($id){
		$comment = CommentsActiveRecord::findOne($id);
		$comment->body = $this->body;
		return $comment->save();
	}
	
	public function postMessage(){
		$message = new MessagesActiveRecord();
		$message->conferenceId = Yii::$app->request->get('id');
		$message->author = Yii::$app->user->identity->id;
		$message->created = date('Y-m-d H-i-s');
		$message->body = $this->body;
		return $message->save();
	}
}
