<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;

$this->title = Yii::t('app', 'Регистрация');
?>
<div class="site-login col-md-6">
	<?php $form = ActiveForm::begin([
						'class' => 'form-horisontal',
						'options' => ['enctype' => 'multipart/form-data'],
					]); ?>
	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'password_repeat')->passwordInput() ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'fio') ?>
	<?= $form->field($model, 'pol')->radioList([
										'1' => \Yii::t('app', 'Мужской'),
										'0' => \Yii::t('app', 'Женский'),
									]) ?>
	<?= $form->field($model, 'photo')
					->widget(FileInput::classname(), [
						'options' => [
							'accept' => 'image/*',
						],
					]) ?>
	<?= $form->field($model, 'birthday')
					->widget(DatePicker::classname(), [
						'readonly' => true,
					]) ?>
	<?= $form->field($model, 'country') ?>
	<?= $form->field($model, 'place') ?>
	<?= $form->field($model, 'email') ?>
	<?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), []) ?>
	<div class="form-group">
		<div class="col-lg-11">
			<?= Html::submitButton(\Yii::t('app', 'Зарегистрироваться'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
</div>

