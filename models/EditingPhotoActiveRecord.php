<?php

namespace app\models;

use Imagick;

class EditingPhotoActiveRecord extends \yii\db\ActiveRecord
{
	public static function tableName(){
		return 'editingPhoto';
	}
	
	public static function createEditingPhoto($id){
		$editable = new EditingPhotoActiveRecord();
		$photo = PhotoActiveRecord::findOne($id);
		if($photo == null){
			return null;
		}
		$editable->photoId = $photo->id;
		$editable->photo = $photo->photo;
		$image = new Imagick();
		$image->readImageBlob($photo->photo);
		$size = $image->getImageGeometry();
		if($size['width']>800){
			foreach($image as $frame){
				$frame->thumbnailImage(800,0);
			}
		}
		$editable->thumbnail = $image->getImagesBlob();
		if($editable->save()){
			return $editable;
		}
		else{
			return null;
		}
	}
	
	public static function saveEditingPhoto($id){
		$editable = EditingPhotoActiveRecord::findOne($id);
		if($editable == null){
			return null;
		}
		$photo = PhotoActiveRecord::findOne($editable->photoId);
		if($photo == null){
			return null;
		}
		$image = new Imagick();
		$image->readImageBlob($editable->photo);
		$image = EditingPhotoActiveRecord::filter($editable->filter, $image);
		if($editable->reduceNoise != 0){
			for($i=0;$i<$editable->reduceNoise;$i++){
				foreach($image as $frame){
					$frame->enhanceImage();
				}
			}
		}
		if($editable->blur != 0){
			foreach($image as $frame){
				$frame->blurImage($editable->blur, 5);
			}
		}
		if($editable->brightness != 0){
			foreach($image as $frame){
				$frame->modulateImage(100 + $editable->brightness, 100, 100);
			}
		}
		$photo->photo = $image->getImagesBlob();
		$photo->thumbnail = $editable->thumbnail;
		if($photo->save()){
			return $editable->delete();
		}
		else{
			return false;
		}
	}
	
	public static function deleteEditingPhoto($id){
		$editable = EditingPhotoActiveRecord::findOne($id);
		if($editable != null){
			return $editable->delete();
		}
		else{
			return false;
		}
	}
	
	public static function polaroid($image){
		$size = $image->getImageGeometry();
		$gradient = new \Imagick();
		$gradient->newPseudoImage($size['width'],$size['height'],'radial-gradient:white-black');
		foreach($image as $frame){
			$frame->modulateImage(120,180,100);
			$frame->setImageGamma(1.2);
			$frame->contrastImage(true);
			$frame->contrastImage(true);
			$frame->compositeImage($gradient, \Imagick::COMPOSITE_OVERLAY, 0, 0);
		}
		return $image;
	}
	
	public static function hipster($image){
		foreach($image as $frame){
			$frame->contrastImage(true);
			$frame->modulateImage(100,150,100);
			$frame->setImageGamma(1.2);
			$frame->colorizeImage('#222b6d',100);
		}
		return $image;
	}
	
	public static function latte($image){
		foreach($image as $frame){
			$frame->setImageGamma(1.2);
			$frame->colorizeImage('rgba(200,153,0,0.5)',100);
		}
		return $image;
	}
	
	public static function vintage($image){
		foreach($image as $frame){
			$frame->modulateImage(120,90,90);
		}
		return $image;
	}
	
	public static function filter($filter, $image){
		switch($filter){
			case 'wb':
			foreach($image as $frame){
				$frame->modulateImage(100,0,50);
			}
			break;
		case 'sepia':
			foreach($image as $frame){
				$frame->sepiaToneImage(80);
			}
			break;
		case 'polaroid':
			foreach($image as $frame){
				$frame = EditingPhotoActiveRecord::polaroid($image);
			}
			break;
		case 'hipster':
			foreach($image as $frame){
				$frame = EditingPhotoActiveRecord::hipster($image);
			}
			break;
		case 'latte':
			foreach($image as $frame){
				$frame = EditingPhotoActiveRecord::latte($image);
			}
			break;
		case 'vintage':
			foreach($image as $frame){
				$frame = EditingPhotoActiveRecord::vintage($image);
			}
			break;
		case 'posterize':
			foreach($image as $frame){
				$frame->posterizeImage(4,false);
			}
			break;
		}
		return $image;
	}
	
	public static function changeEditingImage($type, $photo, $kind){
		$image = new Imagick();
		$image->readImageBlob($photo->photo);
		switch($type){
		case 'rotateleft':
			foreach($image as $frame){
				$frame->rotateImage('',270);
			}
			break;
		case 'rotateright':
			foreach($image as $frame){
				$frame->rotateImage('',90);
			}
			break;
		case 'flip':
			//вертикальное отражение
			foreach($image as $frame){
				$frame->flipImage();
			}
			break;
		case 'flop':
			//горизонтальное отражение
			foreach($image as $frame){
				$frame->flopImage();
			}
			break;
		case 'negative':
			foreach($image as $frame){
				$frame->negateImage(false);
			}
			break;
		case 'crop':
			$thumbnail = new Imagick();
			$thumbnail->readImageBlob($photo->thumbnail);
			$realSize = $image->getImageGeometry();
			$size = $thumbnail->getImageGeometry();
			if($realSize['width']>800){
				$kind['width'] = $kind['width'] * $realSize['width'] / $size['width'];
				$kind['height'] = $kind['height'] * $realSize['height'] / $size['height'];
				$kind['x1'] = $kind['x1'] * $realSize['width'] / $size['width'];
				$kind['y1'] = $kind['y1'] * $realSize['height'] / $size['height'];
			}
			\Yii::trace('width = '.$kind['width'].'; height = '.$kind['height'].'; x = '.$kind['x1'].'; y = '.$kind['x2']);
			foreach($image as $frame){
				$frame->cropImage($kind['width'], $kind['height'], $kind['x1'], $kind['y1']);
			}
			break;
		}
		$photo->photo = $image->getImagesBlob();
		$size = $image->getImageGeometry();
		if($size['width']>800){
			foreach($image as $frame){
				$frame->thumbnailImage(800,0);
			}
		}
		$photo->thumbnail = $image->getImagesBlob();
		$photo->save();
		return $image;
	}
	
	public static function photoEdit($id, $do, $kind){
		$photo = EditingPhotoActiveRecord::find()
							->where(['id' => $id])
							->one();
		if($kind == 'change' || $do == 'crop'){
			$image = EditingPhotoActiveRecord::changeEditingImage($do, $photo, $kind);
		}
		else{
			$image = new Imagick();
			$image->readImageBlob($photo['photo']);
		}
		if($kind == 'filter'){
			$photo->filter = $do;
		}
		if($do == 'reducenoise'){
			$photo->reduceNoise = $kind;
		}
		if($do == 'blur'){
			$photo->blur = $kind;
		}
		if($do == 'brightness'){
			$photo->brightness = $kind;
		}
		$photo->save();
		$image = EditingPhotoActiveRecord::filter($photo->filter, $image);
		if($photo->reduceNoise != 0){
			for($i=0;$i<$photo->reduceNoise;$i++){
				foreach($image as $frame){
					$frame->enhanceImage();
				}
			}
		}
		if($photo->blur != 0){
			foreach($image as $frame){
				$frame->blurImage($photo->blur,5);
			}
		}
		if($photo->brightness != 0){
			foreach($image as $frame){
				$frame->modulateImage(100+$photo->brightness,100,100);
			}
		}
		return $image->getImagesBlob();
	}
}
