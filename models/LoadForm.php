<?php
namespace app\models;

use Yii;
use yii\base\Model;
class LoadForm extends Model
{
	public $name;
	public $tags;
	public $file;
	
	public function rules(){
		return [
			['file', 'required'],
            ['file', 'file', 'extensions' => 'jpg, gif, png', 'maxFiles' => 1],
		];
	}
	
	public function attributeLabels(){
		return [
			'name' => 'Название',
			'tags' => 'Теги',
			'file' => 'Изображение',
		];
	}
	
	public function post(){
		if($this->validate()){
			$image = new PhotoActiveRecord();
			$image->userId = Yii::$app->user->identity->id;
			$image->name = $this->name;
			$image->photo = file_get_contents($this->file->tempName);
			$image->posted = date('Y-m-d H-i-s');
			return $image->save();
		}
		return false;
	}
}
