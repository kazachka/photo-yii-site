<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
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
use app\models\EditingPhotoActiveRecord;
use app\models\PhotoRatesActiveRecord;
use app\models\QueryModel;

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
    
    public function beforeAction($action){
		if(!parent::beforeAction($action)){
			return false;
		}
		if(Yii::$app->session->has('language')){
			Yii::$app->language = Yii::$app->session->get('language');
		}
		return true;
	}

    public function actionIndex()
    {
		$model = new SearchForm();
		if($model->load(Yii::$app->request->post()) && 
			in_array($model->type, [
				'name',
				'tag',
				'users',
			])){
			$query = $model->search()->all();
		}
		else{
			$query = QueryModel::photos()->asArray()->all();
		}
        return $this->render('index',[
					'model' => $model,
					'query' => $query,
		]);
    }

    public function actionLogin()
    {
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Url::to(['site/profile']));
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionRegister(){
		$model = new RegisterForm();
		if(Yii::$app->request->isPost){
			$model->load(Yii::$app->request->post());
			$model->photo = UploadedFile::getInstance($model, 'photo');
			$model->register();
			$this->redirect(Url::to(['site/profile']));
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
			$user = QueryModel::user($id);
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
			$user = QueryModel::user($id);
			$photo = QueryModel::userPhotos($id);
			$isFriend = QueryModel::isFriend($id);
			return $this->render('photo',[
									'user' => $user,
									'photo' => $photo,
									'isFriend' => $isFriend,
								]);
		}
		return $this->goHome();
	}
	
	public function actionImage($id){
		$photo = PhotoActiveRecord::findOne($id);
		$user = User::findOne($photo->userId);
		$photovote = PhotoRatesActiveRecord::getRates($id);
		$comments = QueryModel::comments($id);
		$tags = QueryModel::photoTags($id);
		$search = new SearchForm();
		if(!Yii::$app->user->isGuest){
			$model = new CommentForm();
			if($model->load(Yii::$app->request->post()) && $model->post()){
				return $this->refresh();
			}
			else{
				return $this->render('image',[
									'user' => $user,
									'photo' => $photo,
									'photovote' => $photovote,
									'comments' =>$comments,
									'tags' => $tags,
									'search' => $search,
									'model' => $model,
								]);
			}
		}
		else{
			return $this->render('image',[
									'user' => $user,
									'photo' => $photo,
									'photovote' => $photovote,
									'comments' => $comments,
									'search' => $search,
									'tags' => $tags,
								]);
		}
	}
	
	public function actionLoad(){
		$model = new LoadForm();
		if(Yii::$app->request->isPost){
			$model->load(Yii::$app->request->post());
			$model->file = UploadedFile::getInstance($model, 'file');
			if($model->post()){
				return $this->redirect(Url::to(['site/photo']));
			}
		}
		return $this->render('load',['model' => $model]);
	}
	
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
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
	
	public function actionUpdatecomment($id){
		if(Yii::$app->request->isPost && !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$model = new CommentForm();
			if($model->load(Yii::$app->request->post())){
				if($model->update($id)){
					$this->goBack();
				}
			}
		}
		return false;
	}
    
    public function actionFriends(){
		if(Yii::$app->request->isPost){
			QueryModel::addTo(Yii::$app->request->get('id'));
		}
		$new = QueryModel::newFriends();
		$add = QueryModel::addFriends();
		$friends = QueryModel::friends();
		return $this->render('friends',[
			'friends' => $friends,
			'new' => $new,
			'add' => $add,
		]);	
		
	}

	public function actionConferences(){
		$conferences = QueryModel::conferences();
		return $this->render('conferences', [
			'conferences' => $conferences,
		]);
	}

	public function actionGetconference($id){
		$conference = QueryModel::conference($id);
		if($conference == null){
			$conference = QueryModel::createConference($id);
		}
		$this->redirect(Url::to(['site/messages', 'id' => $conference['id']]));
	}

	public function actionSearch(){
		if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){
			$model = new SearchForm();
			if($model->load(Yii::$app->request->post())){
				$query = $model->search();
			}
			else{
				$query = QueryModel::photos();
			}
			return $this->render('search',[
				'model' => $model,
				'query' => $query,
			]);
		}
		return $this->goBack();
	}
	
	public function actionLang($lang){
		switch($lang){
			case 'en':
				Yii::$app->language = 'en-En';
				Yii::$app->session->set('language', 'en-En');
				break;
			case 'uk':
				Yii::$app->language = 'uk-Ru';
				Yii::$app->session->set('language', 'uk-Ru');
				break;
			default:
				Yii::$app->language = 'ru-Ru';
				Yii::$app->session->set('language', 'ru-Ru');
				break;//?????????????
		}
		return $this->goBack();
	}
	
	public function actionEdit($id){
		$photo = PhotoActiveRecord::findOne($id);
		$editable = EditingPhotoActiveRecord::createEditingPhoto($photo->id);
		return $this->render('edit', [
			'photo' => $editable,
		]);
	}
	
	public function actionMessages($id){
		$model = new CommentForm();
		if($model->load(Yii::$app->request->post())){
			if($model->postMessage()){
				$this->refresh();
			}
		}
		$messages = QueryModel::messages($id);
		return $this->render('messages',[
			'messages' => $messages,
			'model' => $model,
		]);
	}

    public function actionAbout()
    {
        return $this->render('about');
    }
    
}
