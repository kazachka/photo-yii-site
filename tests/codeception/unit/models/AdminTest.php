<?php

use Yii;
use \yii\codeception\TestCase;

class AdminTest extends TestCase{
	public $appConfig = '@tests/unit/_config.php';

	public function testUpdateComment(){
		//$comment = 
	}
	
	public function testDeleteComment(){
		$count1 = CommentsActiveRecord::find()->count();
		$comment = new CommentsActiveRecord();
		$comment->userId = 1;
		$comment->photoId = 0;
		$comment->body = 'comment';
		$comment->created = date("Y-m-d H-i-s");
		$comment->save();
		$controller = new SiteController();
		$controller->actionDeletecomment($comment->id);
		$count2 = CommentsActiveRecord::find()->count();
		$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		/*if($count1 != $count2){
			$this->fire('myevent', new TestEvent($this));
		}*/
	}
	
	public function testDeleteUser(){
		$count1 = User::find()->count();
		$user = new User();
		$user->username = "username";
		$user->password = "password";
		$user->fio = "fio";
		$user->pol = "male";
		$user->email = "email@email.ru";
		$user->save();
		$controller = new SiteController();
		$controller->actionDeleteuser($user->id);
		$count2 = User::find()->count();
		$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		/*if($count1 != $count2){
			$this->fire('myevent', new TestEvent($this));
		}*/
	}
	
	public function testDeletePhoto(){
		$count1 = PhotoActiveRecord::find()->count();
		$photo->userId = 1;
		$photo->photo = file_get_contents("./web/img/user.png");
		$photo->posted = date("Y-m-d H-i-s");
		$photo->save();
		$controller = new SiteController();
		$controller->actionDeletephoto($photo->id);
		$count2 = PhotoActiveRecord::find()->count();
		$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		/*if($count1 != $count2){
			$this->fire('myevent', new TestEvent($this));
		}*/
	}
}
