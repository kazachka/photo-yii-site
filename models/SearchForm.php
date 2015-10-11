<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

class SearchForm extends Model{
	public $text;
	public $type;
	
	public function attributeLabels(){
		return [
			'text' => '',
			'type' => '',
		];
	}
	
	public function rules(){
		return [
			[['text', 'type'], 'trim'],
		];
	}
	
	public function search(){
		switch($this->type){
			case 'name':
			return PhotoActiveRecord::find()
						->where(['like', 'name', $this->text])
						->orderBy(['posted' => SORT_DESC]);
			case 'tag':
			$tags = TagsActiveRecord::find()
						->where(['tag' => $this->text]);
			return PhotoActiveRecord::find()
						->where(['in', 'id', $tags])
						->orderBy(['posted' => SORT_DESC]);
			case 'users':
			return (new Query())
						->from('user')
						->where(['like', 'fio', $this->text])
						->join('left join', 'photo', 'user.photo = photo.id')
						->select([
							'id' => 'user.id',
							'photo' => 'photo.photo',
							'name' => 'fio',
							'username',
							'pol',
							'email',
							'registerDate'
						])
						->orderBy(['registerDate' => SORT_DESC]);
			case 'tags':
			return TagsActiveRecord::find()
						->where(['like', 'tag', $this->text])
						->select('tag')
						->distinct();
			case 'comments':
			return (new Query())
						->from('comments')
						->join('join', 'user', 'comments.userId = user.id')
						->select([
							'id' => 'comments.id',
							'photoId',
							'body',
							'created',
							'fio',
						])
						->orderBy(['created' => SORT_DESC]);
		}
		return null;
	}
}
