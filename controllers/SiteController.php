<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\LoadForm;
use app\models\ContactForm;
use app\models\CommentForm;
use app\models\RegisterForm;
use app\models\SearchForm;
use app\models\User;
use app\models\PhotoActiveRecord;
use app\models\CommentsActiveRecord;
use app\models\TagsActiveRecord;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
		$model = new SearchForm();
		if($model->load(Yii::$app->request->post()) && 
			in_array($model->type, [
				'name',
				'tag',
				'user',
			])){
			$query = $model->search()->all();
		}
		else{
			$query = PhotoActiveRecord::find()
						->orderBy(['posted' => SORT_DESC])
						->all();
		}
        return $this->render('index',[
					'model' => $model,
					'query' => $query,
		]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('index.php?r=site/profile');
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionRegister(){
		$model = new RegisterForm();
		if($model->load(Yii::$app->request->post()) && $model->register()){
			$this->redirect('index.php?r=site/profile');
		}
		return $this->render('register', [
			'model' => $model,
		]);
	}
	
	public function actionProfile(){
		$id = Yii::$app->request->get('id');
		if($id == null && !Yii::$app->user->isGuest){
			$id = Yii::$app->user->identity->id;
		}
		if($id != null){
			$user = (new Query())
						->from('user')
						->where(['user.id' => $id])
						->join('left join', 'photo', 'user.photo = photo.id')
						->one();
			return $this->render('profile',['user' => $user]);
		}
		return $this->goHome();
	}
	
	public function actionPhoto(){
		$id = Yii::$app->request->get('id');
		if($id == null && !Yii::$app->user->isGuest){
			$id = Yii::$app->user->identity->id;
		}
		if($id != null){
			$user = (new Query())
						->from('user')
						->where(['user.id' => $id])
						->join('left join', 'photo', 'user.photo = photo.id')
						->select([
							'id' => 'user.id', 
							'photo' => 'photo.photo',
							'fio',
						])
						->one();
			$photo = PhotoActiveRecord::find()
						->where(['userId' => $id])
						->orderBy(['posted' => SORT_DESC])
						->all();
			return $this->render('photo',[
									'user' => $user,
									'photo' => $photo,
								]);
		}
		return $this->goHome();
	}
	
	public function actionImage($id){
		$photo = PhotoActiveRecord::findOne($id);
		$comments = (new Query())
						->from('comments')
						->where(['photoId' => $id])
						->join('join','user','comments.userId = user.id')
						->orderBy(['created' => SORT_DESC])
						->select([
							'fio',
							'created',
							'body',
							'id' => 'comments.id',
						])
						->all();
		if(!Yii::$app->user->isGuest){
			$model = new CommentForm();
			if($model->load(Yii::$app->request->post()) && $model->post()){
				return $this->refresh();
			}
			else{
				return $this->render('image',[
									'photo' => $photo,
									'comments' =>$comments,
									'model' => $model,
								]);
			}
		}
		else{
			return $this->render('image',[
									'photo' => $photo,
									'comments' => $comments,
								]);
		}
	}
	
	public function actionLoad(){
		$model = new LoadForm();
		if(Yii::$app->request->isPost){
			$model->name = Yii::$app->request->post('name');
			$model->file = UploadedFile::getInstance($model, 'file');
			if($model->post()){
				return $this->redirect('index.php?r=site/photo');
			}
		}
		return $this->render('load',['model' => $model]);
	}
	
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionDeleteuser($id){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			User::findOne($id)->delete();
		}
		return $this->goBack();
	}
	
	public function actionDeleteimage($id){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			PhotoActiveRecord::findOne($id)->delete();
		}
		return $this->goBack();
	}
	
	public function actionUpdatecomment($id){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$model = new CommentForm();
			if($model->load(Yii::$app->request->post())){
				$comment = CommentsActiveRecord::findOne($id);
				$comment->body = $model->body;
				$comment->save();
			}
		}
		return $this->goBack();
	}
	
	public function actionDeletecomment($id){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			CommentsActiveRecord::findOne($id)->delete();
		}
		return $this->goBack();
	}

	public function actionDeletetag($tag){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$tag = TagsActiveRecord::findAll(['tag' => $tag]);
			foreach($tag as $item){
				$item->delete();
			}
		}
		return $this->goBack();
	}

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    
    public function actionFriends(){
		$friendsQuery = (new Query())
					->from('friends a')
					->join('join', 'friends b', 'a.id1 = b.id2')
					->where(['and', 'a.id2=b.id1', 'a.id1='.Yii::$app->user->identity->id])
					->select(['id' => 'a.id2']);
		$new = (new Query())
					->from('friends')
					->where(['and', ['not in', 'id1', $friendsQuery], 'id2='.Yii::$app->user->identity->id])
					->join('join', 'user', 'id1 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'friends.id1',
						'fio' => 'user.fio',
						'photo' => 'photo.photo',
					])
					->all();
		$add = (new Query())
					->from('friends')
					->where(['and', ['not in', 'id2', $friendsQuery], 'id1='.Yii::$app->user->identity->id])
					->join('join', 'user', 'id2 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'friends.id1',
						'fio' => 'user.fio',
						'photo' => 'photo.photo',
					])
					->all();
		$friends = $friendsQuery
					->join('join', 'user', 'a.id2 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'a.id2',
						'fio' => 'user.fio',
						'photo' => 'photo.photo',
					])
					->all();
		return $this->render('friends',[
			'friends' => $friends,
			'new' => $new,
			'add' => $add,
		]);	
		
	}

	public function actionMessages(){
		$conferences = (new Query())
						->from('conference')
						->select('conferenceId')
						->where(['userId' => Yii::$app->user->identity->id]);
		$messages = (new Query())
						->from('messages')
						->where(['in', 'conferenceId', $conferences])
						->having('max(created)')
						->all();
		$this->render('messages', [
			'messages' => $messages,
		]);
	}

	public function actionSearch(){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$model = new SearchForm();
			if($model->load(Yii::$app->request->post())){
				$query = $model->search();
			}
			else{
				$query = PhotoActiveRecord::find()
							->orderBy(['posted' => SORT_DESC]);
			}
			return $this->render('search',[
				'model' => $model,
				'query' => $query,
			]);
		}
		return $this->goBack();
	}

    public function actionAbout()
    {
        return $this->render('profile');
    }
}
