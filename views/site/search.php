<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = 'Авторизация';
$this->params
?>
<div class="row">
	<?php $form = ActiveForm::begin([
					'options' => [
						'class' => 'navbar-form',
						'role' => 'search',
						//'style' => 'width: 100px',
					],
	]); ?>
	<?= $form->field($model, 'text')
				->textInput([
					'style' => 'width: 100px',
					'placeholder' => 'Поиск',
				]) ?>
	<?= $form->field($model, 'type')
				->dropdownlist([
					'name' => 'Фото',
					'tags' => 'Теги',
					'users' => 'Пользователи',
					'comments' => 'Комментарии',
				]) ?>
	<?= Html::submitButton('Поиск', [
			'class' => 'btn btn-default',
		]) ?>
	<?php ActiveForm::end(); ?>
</div>
<?php $provider = new ActiveDataProvider([
	'query' 	 => $query,
	'pagination' => [
		'pageSize' => 50,
	]
]);
$items = $provider->getModels();
switch($model->type){
	case 'tags':
	foreach($items as $item){ ?>
		<div class="row">
			<p class="col-xs-6"><?= $item['tag'] ?></p>
			<p class="col-xs-2"><a href="index.php?r=site/deletetag&tag=<?= $item['tag'] ?>">Удалить</a></p>
		</div>
	<?php }
	break;
	case 'users':
	foreach($items as $item){ ?>
		<div class="row">
			<a href="index.php?r=site/photo&id<?= $item['id'] ?>">
			<p class="col-xs-2"><?= $item['name'] ?></p>
			<p class="col-xs-2"><?= $item['username'] ?></p>
			<p class="col-xs-2"><?= $item['email'] ?></p>
			<p class="col-xs-2"><?= ($item['pol'] == 'male')?'М':'Ж' ?></p>
			</a>
			<p class="col-xs-2"><a href="index.php?r=site/deleteuser&id=<?= $item['id'] ?>">Удалить</a></p>
		</div>
	<?php }
	break;
	case 'comments':
		foreach($items as $item){ ?>
			<div class="row">
				<a href="index.php?r=site/image&id=<?= $item['photoId'] ?>">
				<p class="col-xs-2"><?= $item['fio'] ?></p>
				<p class="col-xs-5"><?= $item['body'] ?></p>
				<p class="col-xs-2"><?= $item['created'] ?></p>
				</a>
				<p class="col-xs-3"><a href="index.php?r=site/deletecomment&id=<?= $item['id'] ?>">Удалить комментарий</a></p>
			</div>
		<?php }
	break;
	case 'name': default:
	foreach($items as $item){ ?>
		<div class="row">
			<a href="index.php?r=site/image&id=<?= $item['id'] ?>">
			<p class="col-xs-3"><?= $item['name'] ?></p>
			<p class="col-xs-3"><?= $item['posted'] ?></p>
			</a>
			<p class="col-xs-3"><a href="index.php?r=site/deleteimage&id=<?= $item['id'] ?>">Удалить</a></p>
		</div>
	<?php }
	break;
}
