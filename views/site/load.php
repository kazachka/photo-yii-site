<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
	<div class="col-md-6">
		<?php $form = ActiveForm::begin([
						'class' => 'form-horisontal',
						'options' => ['enctype' => 'multipart/form-data'],
					]); ?>
		
		<?= $form->field($model, 'name') ?>
		
		<?= $form->field($model, 'file')->fileInput() ?>
		
		<div class="form-group">
			<div class="col-lg-offset-1 col-lg-11">
				<?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
			</div>
		</div>

		<?php ActiveForm::end(); ?>
    </div>
</div>
