<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
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
					'name' => 'По названию',
					'tag' => 'По тегу',
					'users' => 'Пользователя',
				]) ?>
	<?= Html::submitButton('Поиск', [
			'class' => 'btn btn-default',
		]) ?>
	<?php ActiveForm::end(); ?>
</div>

<!--form class="navbar-form" role="search">
	<div class="col-md-4">
		<input type="text" style="width: 100%;" class="form-control" placeholder="Поиск"/>
	</div>
	<div class="dropdown form-group">
		<button class="btn btn-default dropdown-toogle" type="button" id="dropdownTypeSelectType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		По названию
		<span class="caret"></span>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdownTypeSelectType">
			<li><a href="#"> По названию </a></li>
			<li><a href="#"> По тегу </a></li>
		</ul>
	</div>
	<input class="btn btn-default" type="submit" value="Поиск"/>
</form-->
<div class="row">
<?php foreach($query as $image){ ?>
	<div class="col-md-4 text-center">
		<a href=<?= isset($image['posted'])?'index.php?r=site/image&id='.$image['id']:'index.php?r=site/photo&id='.$image['id'] ?> class="thumbnail">
			<?= Html::img(
					$image['photo']?'data:image/jpeg;base64,'.base64_encode($image['photo']):'./web/img/user.png',[
					'alt' => $image['name'],
				]); ?>
		</a>
		<p><?= $image['name'] ?></p>
	</div>
<?php } ?>
</div>
