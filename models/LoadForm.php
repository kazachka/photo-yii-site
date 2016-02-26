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
            ['name', 'string'],
            ['tags', 'string'],
		];
	}
	
	public function attributeLabels(){
		return [
			'name' => Yii::t('app', 'Название'),
			'tags' => Yii::t('app', 'Теги'),
			'file' => Yii::t('app', 'Изображение'),
		];
	}
	
	public function attributeHints(){
		return [
			'tags' => Yii::t('app', 'Теги через запятую'),
		];
	}
	
	public function post(){
		if($this->validate()){
			$image = new PhotoActiveRecord();
			$image->userId = Yii::$app->user->identity->id;
			$image->name = $this->name.'';
			$image->photo = file_get_contents($this->file->tempName);
			$image->posted = date('Y-m-d H-i-s');
			$imagick = new \Imagick();
			$imagick->readImageBlob($image->photo);
			$size = $imagick->getImageGeometry();
			if($size['width']>800){
				foreach($imagick as $frame){
					$frame->thumbnailImage(800,0);
				}
			}
			$image->thumbnail = $imagick->getImagesBlob();
			if(!$image->save()){
				return false;
			}
			$tags = split(',',$this->tags);
			foreach($tags as $item){
				if($item != ''){
					$tag = new TagsActiveRecord();
					$tag->photo = $image->id;
					$tag->tag = trim($item);
					if(!$tag->save()){
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}
