<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $user['fio'];
?>
<div class="row">
	<nav class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item">
				<a href="<?= Url::to(['site/conferences']) ?>">
					<?= \Yii::t('app', 'Сообщения') ?>
				</a>
			</li>
			<li class="list-group-item">
				<a href="<?= Url::to(['site/friends']) ?>">
					<?= \Yii::t('app', 'Друзья') ?>
				</a>
			</li>
			<li class="list-group-item">
				<a href="<?= Url::to(['site/photo']) ?>">
					<?= \Yii::t('app', 'Мои фотографии') ?>
				</a>
			</li>
			<li class="list-group-item">
				<a href="<?= Url::to(['site/load']) ?>">
					<?= \Yii::t('app', 'Загрузить') ?>
				</a>
			</li>
	</nav>
	<div class="col-md-4 text-center">
		<div class="text-center thumbnail">
			<?= Html::img(
				$user['photo']?'data:image/jpeg;base64,'.base64_encode($user['photo']):'./web/img/user.png',[
				'id' => 'photo',
				'alt' => \Yii::t('app', 'Картинка'),
				'class' => 'thumbnail',
				'style' => 'height: 160px',
			]); ?>
			<label for="photo"><?= $user['fio'] ?></label>
		</div>
	</div>
	<div class="col-md-5">
		<div class="row">
			<p class="col-xs-4"><?= \Yii::t('app', 'Страна') ?>:</p>
			<b class="col-xs-8"><?= $user['country'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4"><?= \Yii::t('app', 'Место проживания') ?>:</p>
			<b class="col-xs-8"><?= $user['place'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4"><?= \Yii::t('app', 'Дата рождения') ?>:</p>
			<b class="col-xs-8"><?= $user['birthday'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4"><?= \Yii::t('app', 'Пол') ?>:</p>
			<b class="col-xs-8">
				<?php if($user['pol'] == 'male'){
					echo \Yii::t('app', 'Мужской');
				}
				else{
					echo \Yii::t('app', 'Женский');
				}
				?>
			</b>
		</div>
	</div>
</div>
