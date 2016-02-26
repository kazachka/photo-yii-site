<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PhotoActiveRecord;
use app\models\EditingPhotoActiveRecord;
use app\models\CommentsActiveRecord;
use app\models\TagsActiveRecord;
use app\models\PhotoRatesActiveRecord;
use app\models\CommentRatesActiveRecord;
use app\models\User;
use app\models\QueryModel;

class AjaxController extends Controller{
	public function actionGet(){
		$id = Yii::$app->request->post('id');
		$do = Yii::$app->request->post('do');
		$kind = Yii::$app->request->post('kind');
		return 'data:image/jpeg;base64,'.base64_encode(EditingPhotoActiveRecord::photoEdit($id, $do, $kind));
	}
	
	public function actionSave(){
		$id = Yii::$app->request->post('id');
		if(Yii::$app->request->isPost && $id!=null){
			return EditingPhotoActiveRecord::saveEditingPhoto($id);
		}
		return false;
	}
	
	public function actionCancel(){
		$id = Yii::$app->request->post('id');
		if(Yii::$app->request->isPost && $id!=null){
			return EditingPhotoActiveRecord::deleteEditingPhoto($id);
		}
		return false;
	}
	
	public function actionFriends(){
		$id = Yii::$app->request->post('id');
		$action = Yii::$app->request->post('action');
		Yii::trace($id.' '.$action);
		if($id != null && $action != null){
			if($action == 'addto'){
				QueryModel::addTo($id);
			}
			else{
				QueryModel::deleteFrom($id);
			}
			$isFriend = QueryModel::isFriend($id);
			if($isFriend == null){ 
				return '<a>'.Yii::t('app', 'Добавить в друзья').'</a>';
			}
			elseif($isFriend['to'] != null && $isFriend['from'] == null){ 
				return '<a>'.Yii::t('app', 'Отозвать заявку').'</a>';
			} 
			else{ 
				return '<a>'.Yii::t('app', 'Удалить из друзей').'</a>';
			}
		}
		return '123';
	}
	
	public function actionPhotovote(){
		if(!Yii::$app->user->isGuest 
			&& Yii::$app->request->post('photo') != null 
			&& Yii::$app->request->post('vote') != null){
				QueryModel::photoVote(Yii::$app->request->post('photo'));
		}
		return PhotoRatesActiveRecord::getRates(Yii::$app->request->post('photo'));
	}
	
	public function actionCommentvote(){
		if(!Yii::$app->user->isGuest 
			&& Yii::$app->request->post('comment') != null 
			&& Yii::$app->request->post('vote') != null){
				QueryModel::commentVote(Yii::$app->request->post('comment'));
		}
		return CommentRatesActiveRecord::getRates(Yii::$app->request->post('comment'));
	}
    
    public function actionDeleteuser(){
		if(Yii::$app->request->isPost && !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$id = Yii::$app->request->post('id');
			if($id != null){
				return User::findOne($id)->delete();
			}
		}
		return false;
	}
	
	public function actionDeleteimage(){
		if(Yii::$app->request->isPost && !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$id = Yii::$app->request->post('id');
			if($id != null){
				return PhotoActiveRecord::findOne($id)->delete();
			}
		}
		return false;
	}
	
	public function actionDeletecomment(){
		if(Yii::$app->request->isPost && !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$id = Yii::$app->request->post('id');
			if($id != null){
				return CommentsActiveRecord::findOne($id)->delete();
			}
		}
		return false;
	}

	public function actionDeletetag(){
		if(Yii::$app->request->isPost && !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$tag = Yii::$app->request->post('tag');
			if($tag != null){
				$tag = TagsActiveRecord::findAll(['tag' => $tag]);
				foreach($tag as $item){
					if(!$item->delete()){
						return false;
					}
				}
				return true;
			}
		}
		return false;
	}

}
