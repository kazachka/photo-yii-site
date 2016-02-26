<?php

use Yii;
use yii\codeception\TestCase;
use yii\base\Module;
use data\User;
use data\PhotoActiveRecord;
use data\CommentsActiveRecord;
use data\SiteController;
use data\TagsActiveRecord;

class AdminTest extends TestCase{
	public $appConfig = 'tests/unit/_config.php';
	public $module;
	
	public function setUp(){
		parent::setUp();
		$module = new Module(1);
	}
	
	public function testUpdateComment(){
		//
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
		//$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		if($count1 != $count2){
			$this->fire('Test deleting error', new TestEvent($this));
		}
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
		//$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		if($count1 != $count2){
			$this->fire('UserDeletingError', new TestEvent($this));
		}
	}
	
	public function testDeletePhoto(){
		$count1 = PhotoActiveRecord::find()->count();
		$photo = new PhotoActiveRecord();
		$photo->userId = 1;
		$photo->photo = file_get_contents("./web/img/user.png");
		$photo->posted = date("Y-m-d H-i-s");
		$photo->save();
		$controller = new SiteController();
		$controller->actionDeleteimage($photo->id);
		$count2 = PhotoActiveRecord::find()->count();
		//$this->assert($count1, $count2, 'count before inserting and after deleting are not equals');
		if($count1 != $count2){
			$this->fire('Photo deleting error', new TestEvent($this));
		}
	}
	
	public function testDeleteTag(){
		$count1 = TagsActiveRecord::find()->count();
		$tag1 = new TagsActiveRecord();
		$tag1->photo = 1;
		$tag1->tag = 'tagtagtagtagtagtagtagtagtagtagtagtag';
		$tag1->save();
		$tag2 = new TagsActiveRecord();
		$tag2->photo = 2;
		$tag2->tag = 'tagtagtagtagtagtagtagtagtagtagtagtag';
		$tag2->save();
		$tag3 = new TagsActiveRecord();
		$tag3->photo = 3;
		$tag3->tag = 'tagtagtagtagtagtagtagtagtagtagtagtag';
		$tag3->save();
		$controller = new SiteController();
		$controller->actionDeletetag($tag3->tag);
		$count2 = TagsActiveRecord::find()->count();
		if($count1 != $count2){
			$this->fire('Tag deleting error', new TestEvent($this));
		}
	}
}
