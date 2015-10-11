<?php

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<nav class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item">
				<a href="#">
					Новое
				</a>
			</li>
			<li class="list-group-item">
				<a href="#">
					Сообщения
				</a>
			</li>
			<li class="list-group-item">
				<a href="#">
					Друзья
				</a>
			</li>
			<li class="list-group-item">
				<a href="index.php?r=site/photo">
					Мои фотографии
				</a>
			</li>
			<li class="list-group-item">
				<a href="#">
					Загрузить
				</a>
			</li>
			<li class="list-group-item">
				<a href="#">
					Настройки
				</a>
			</li>
		</ul>
	</nav>
	<div class="col-md-4 text-center">
		<p>
			<a href="#" class="thumbnail">
				<?= Html::img(
					$user['photo']?'data:image/jpeg;base64,'.base64_encode($user['photo']):'./web/img/user.png',[
					'id' => 'photo',
					'alt' => 'Картинка',
					'style' => 'height: 160px',
				]); ?>
			</a>
		</p>
		<label for="photo"><?= $user['fio'] ?></label>
	</div>
	<div class="col-md-5">
		<div class="row">
			<p class="col-xs-4">Страна:</p>
			<b class="col-xs-8"><?= $user['country'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4">Место проживания:</p>
			<b class="col-xs-8"><?= $user['place'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4">Дата рождения:</p>
			<b class="col-xs-8"><?= $user['birthday'] ?></b>
		</div>
		<div class="row">
			<p class="col-xs-4">Пол:</p>
			<b class="col-xs-8"><?= $user['pol'] ?></b>
		</div>
	</div>
</div>
