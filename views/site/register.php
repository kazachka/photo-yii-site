<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="site-login col-md-6">
	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
	<?= $form->field($model, 'username') ?>
	<?= $form->field($model, 'password_repeat')->passwordInput() ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'fio') ?>
	<?= $form->field($model, 'pol')->radioList([
										'true' => 'Мужской',
										'false' => 'Женский',
									]) ?>
	<?= $form->field($model, 'photo')->fileInput() ?>
	<?= $form->field($model, 'birthday')//->widget(\yii\jui\DatePicker::classname(), []) ?>
	<?= $form->field($model, 'country') ?>
	<?= $form->field($model, 'place') ?>
	<?= $form->field($model, 'email') ?>
	<?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::classname(), []) ?>
	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
</div>

