<?php

namespace app\models;

use Yii;
use yii\db\Query;

class QueryModel{
	public static function photos(){
		return PhotoActiveRecord::find()
						->orderBy(['posted' => SORT_DESC]);
	}
	
	public static function photoTags($photoId){
		return TagsActiveRecord::find()
					->select(['tag'])
					->where(['photo' => $photoId])
					->asArray()
					->all();
	}
	
	public static function user($id){
		return (new Query())
					->from('user')
					->where(['user.id' => $id])
					->join('left join', 'photo', 'user.photo = photo.id')
						->select([
							'id' => 'user.id', 
							'photo' => 'photo.photo',
							'fio',
							'country',
							'place',
							'birthday',
							'pol',
						])
					->one();
	}
	
	public static function userPhotos($userId){
		return PhotoActiveRecord::find()
						->where(['userId' => $userId])
						->orderBy(['posted' => SORT_DESC])
						->asArray()
						->all();
	}
	
	public static function comments($photoId){
		$commentsvote = (new Query)
							->from('commentsRates')
							->select([
								'commentId', 
								'rate' => 'sum(rate)',
							])
							->groupBy('commentId');
		return (new Query())
						->from('comments')
						->where(['photoId' => $photoId])
						->join('join','user','comments.userId = user.id')
						->leftJoin(['a' => $commentsvote], 'a.commentId = comments.id')
						->orderBy(['created' => SORT_DESC])
						->select([
							'fio',
							'created',
							'body',
							'id' => 'comments.id',
							'rate',
						]);
	}
	
	private static function friendsQuery(){
		return (new Query())
					->from('friends a')
					->join('join', 'friends b', 'a.id1 = b.id2')
					->where(['and', 'a.id2=b.id1', 'a.id1='.Yii::$app->user->identity->id])
					->select(['id' => 'a.id2']);
	}
	
	public static function addFriends(){
		return (new Query())
					->from('friends')
					->where(['and', ['not in', 'id2', static::friendsQuery()], 'id1='.Yii::$app->user->identity->id])
					->join('join', 'user', 'id2 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'friends.id2',
						'fio' => 'user.fio',
						'photo' => 'photo.photo',
					])
					->all();
	}
	
	public static function newFriends(){
		return (new Query())
					->from('friends')
					->where(['and', ['not in', 'id1', static::friendsQuery()], 'id2='.Yii::$app->user->identity->id])
					->join('join', 'user', 'id1 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'friends.id1',
						'fio' => 'user.fio',
						'photo' => 'photo.photo',
					])
					->all();
	}
	
	public static function friends(){
		return static::friendsQuery()
					->join('join', 'user', 'a.id2 = user.id')
					->join('left join', 'photo', 'user.photo = photo.id')
					->select([
						'id' => 'a.id2',
						'fio' => 'user.fio',
						'photo' => 'photo.thumbnail',
					])
					->all();
	}
	
	public static function conferences(){
		$userConferences = (new Query())
						->from('userConference')
						->select('conferenceId')
						->where(['userId' => Yii::$app->user->identity->id])
						->distinct();
		return (new Query())
						->from('conference')
						->where(['in', 'id', $userConferences])
						->all();
	}
	
	public static function messages($conferenceId){
		return (new Query())
					->from('messages')
					->where(['conferenceId' => $conferenceId])
					->join('join', 'user', 'messages.author = user.id')
					->orderBy(['created' => SORT_DESC])
					->select([
						'created',
						'body',
						'author' => 'messages.author',
						'fio',
					]);
	}
	
	public static function isFriend($id){
		if(!Yii::$app->user->isGuest){
			$friend = (new Query())
						->from('friends a')
						->join('left join', 'friends b', '(a.id2 = b.id1 and a.id1 = b.id2)')
						->where([
							'a.id1' => Yii::$app->user->identity->id,
							'a.id2' => $id,
						])
						->select([
							'to' => 'a.id2',
							'from' => 'b.id2',
						])
						->one();
			return $friend;
		}
		return null;
	}
	
	public static function conference($userId){
		if(Yii::$app->user->identity->id){
			$conferences = UserConferenceActiveRecord::find()
							->select(['id' => 'conferenceId'])
							->where(['or', 'userId = '.Yii::$app->user->identity->id, 'userId = '.$userId])
							->groupBy(['conferenceId'])
							->having(['count(*)' => 2]);
			$conference = UserConferenceActiveRecord::find()
							->select(['id' => 'conferenceId'])
							->where(['in', 'conferenceId', $conferences])
							->groupBy(['conferenceId'])
							->having(['count(*)' => 2])
							->asArray()
							->one();
			Yii::trace($conference);
			return $conference;
		}
		return null;
	}
	
	public static function addTo($id){
		$friend = new FriendsActiveRecord();
		$friend->id1 = Yii::$app->user->identity->id;
		$friend->id2 = $id;
		return $friend->save();
	}
	
	public static function createConference($id){
		$conference = new ConferenceActiveRecord();
		$user1 = User::findOne(['id' => Yii::$app->user->identity->id]);
		$user2 = User::findOne(['id' => $id]);
		$conference->name = $user1['fio'].' и '.$user2['fio'];
		$conference->save();
		$userConference1 = new UserConferenceActiveRecord();
		$userConference1->conferenceId = $conference->id;
		$userConference1->userId = $user1['id'];
		$userConference1->save();
		$userConference2 = new UserConferenceActiveRecord();
		$userConference2->conferenceId = $conference->id;
		$userConference2->userId = $user2['id'];
		$userConference2->save();
		return $conference;
	}
	
	public static function deleteFrom($id){
		FriendsActiveRecord::find()
						->where([
							'id1' => Yii::$app->user->identity->id,
							'id2' => $id,
						])
						->one()
						->delete();
	}
	
	public static function photoVote($id){
		$rate = new PhotoRatesActiveRecord();
		$rate->userId = Yii::$app->user->identity->id;
		$rate->photoId = $id;
		$rate->rate = Yii::$app->request->post('vote');
		return $rate->save();
	}
	
	public static function commentVote($id){
		$rate = new CommentRatesActiveRecord();
		$rate->userId = Yii::$app->user->identity->id;
		$rate->commentId = $id;
		$rate->rate = Yii::$app->request->post('vote');
		return $rate->save();
	}
	
}
