<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Главная');
?>
<div class="row container" style="padding: 2%; margin-left: 0.5%">
	<?php $form = ActiveForm::begin([
					'enableClientValidation' => false,
					'enableClientScript' => false,
					'validateOnBlur' => false,
					'validateOnChange' => false,
					'validateOnType' => false,
					'options' => [
						'class' => 'form-inline',
						'role' => 'search',
					],
	]); ?>
	<?= $form->field($model, 'text', [
							'options' => [
								'class' => 'col-xs-6 col-md-8',
							],
						])->render(
							'<input id="searchform-text" class="form-control" name="SearchForm[text]" type="text" style="width: 100%;" placeholder="'
							.Yii::t('app', 'Поиск').'"/>') ?>
	
	<?= $form->field($model, 'type', [
					'template' => '<div class="form-group">
										{input}
									</div>',
					'options' => [ 	
						'class' => 'form-group col-xs-4 col-md-2',
					],
				])
				->dropdownList([
					'name' => \Yii::t('app', 'По названию'),
					'tag' => \Yii::t('app', 'По тегу'),
					'users' => \Yii::t('app', 'Пользователя'),
				],[
					'class' => 'form-control',
				]) ?>
	
	<?= Html::submitButton(\Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary col-xs-2 col-md-1', 'name' => 'login-button']) ?>
	
	<?php ActiveForm::end(); ?>
</div>

<div class="container">
	<div class="row">
	<?php for($i = 0 ; $i < count($query) ; $i++){
		if($i % 3 == 0 && $i != 0){ ?>
			</div>
			<div class="row">
		<?php } ?>
		<div class="col-md-4 text-center">
			<a href="<?= isset($query[$i]['posted'])?Url::to(['site/image', 'id' => $query[$i]['id']]):Url::to(['site/photo', 'id' => $query[$i]['id']]) ?>" class="thumbnail">
				<?= Html::img(
						$query[$i]['thumbnail']?'data:image/jpeg;base64,'.base64_encode($query[$i]['thumbnail']):'./web/img/user.png',[
						'alt' => $query[$i]['name'],
					]) ?>
			</a>
			<p><?= $query[$i]['name'] ?></p>
		</div>
	<?php } ?>
	</div>
</div>
