<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="row">
	<div class="row">
		<div class="thumbnail">
			<?= Html::img('data:image/jpeg;base64,'.base64_encode($photo['photo']),[
					'alt' => $photo['name'],
				]) ?>
		</div>
		<p><?= $photo['name'] ?></p>
		<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
		<p><a href="index.php?r=site/deleteimage&id=<?= $photo['id'] ?>">Удалить фото</a></p>
		<?php } ?>
	</div>
	<?php if(!Yii::$app->user->isGuest){ ?>
	<div class="row">
		<div class="col-md-6">
			<?php $form = ActiveForm::begin(['id' => 'commentForm']) ?>
			<?= $form->field($model, 'body')->textarea() ?>
			<?= Html::submitInput('Отправить', ['class' => 'btn btn-primary']) ?>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
	<?php } ?>
	<?php if($comments != null){ ?>
	<div id="comments" class="">
		<?php foreach($comments as $comment){ ?>
			<p class="col-md-6"><?= Html::encode($comment['fio']) ?></p>
			<p class="col-md-6"><?= Html::encode($comment['created']) ?></p>
			<p id="comment<?= $comment['id'] ?>"><?= $comment['body'] ?></p>
			<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
			<p>
				<a id="<?= $comment['id'] ?>" class="update" href="#">Изменить комментарий</a>
				<a href="index.php?r=site/deletecomment&id=<?= $comment['id'] ?>">Удалить комментарий</a>
			</p>
			<?php } ?>
		<?php } ?>
	</div>
	<?php } 
	if(Yii::$app->user->identity->id){ ?>
		<script>
			$('.update').click(function (event) {
				$('#commentForm').prop('action','index.php?r=site/updatecomment&id='+$(this).prop('id'));
				$('textarea').val($('#comment'+$(this).prop('id')).html());
				$('input[type=submit]').val('Изменить');
				event.preventDefault();
			});
		</script>
	<?php } ?>
</div>
