<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

$this->title = Yii::t('app', 'Загрузка');
?>
<div class="container">
	<div class="col-md-6">
		<?php $form = ActiveForm::begin([
						'class' => 'form-horisontal',
						'options' => ['enctype' => 'multipart/form-data'],
					]); ?>
		
		<?= $form->field($model, 'file')
					->widget(FileInput::classname(), [
						'options' => [
							'multiple' => false,
							'accept' => 'image/*',
						],
						'pluginOptions' => [
							'showPreview' => true,
							'showCaption' => true,
							'showRemove' => false,
							'showUpload' => false
						]
					]);
		?>
		
		<?= $form->field($model, 'name') ?>
		
		<?= $form->field($model, 'tags') ?>
		
		<div class="form-group">
			<div class="col-lg-offset-1 col-lg-11">
				<?= Html::submitButton(\Yii::t('app', 'Загрузить'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
			</div>
		</div>

		<?php ActiveForm::end(); ?>
    </div>
</div>
