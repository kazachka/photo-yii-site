<?php

namespace data;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class SiteController
{
    public function actionDeleteuser($id){
		//if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			User::findOne($id)->delete();
		//}
		//return $this->goBack();
	}
	
	public function actionDeleteimage($id){
		//if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			PhotoActiveRecord::findOne($id)->delete();
		//}
		//return $this->goBack();
	}
	
	public function actionUpdatecomment($id){
		//if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$model = new CommentForm();
			if($model->load(Yii::$app->request->post())){
				$comment = CommentsActiveRecord::findOne($id);
				$comment->body = $model->body;
				$comment->save();
			}
		//}
		//return $this->goBack();
	}
	
	public function actionDeletecomment($id){
		//if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			CommentsActiveRecord::findOne($id)->delete();
		//}
		//return $this->goBack();
	}

	public function actionDeletetag($tag){
		//if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$tag = TagsActiveRecord::findAll(['tag' => $tag]);
			foreach($tag as $item){
				$item->delete();
			}
		//}
		//return $this->goBack();
	}
}
