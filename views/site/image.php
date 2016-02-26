<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'Фотография');
Yii::trace($user);
?>
<div class="container">
	<div class="row">
		<div class="thumbnail">
			<?= Html::img('data:image/jpeg;base64,'.base64_encode($photo['photo']),[
					'alt' => $photo['name'],
				]) ?>
		</div>
		<div class="container">
			<p class="pull-left">
				<a href="<?= Url::to(['site/photo', 'id' => $user['id']]) ?>">
					<?= Html::encode($user['fio']) ?>
				</a>
			</p>
			<p style="text-align: center;"><?= $photo['name'] ?></p>
			<p class="pull-right" style="font-size: 18px;">
				<a class="vote" style="color: red">-</a>
				<span id="rate"><?= $photovote?$photovote:0 ?></span>
				<a class="vote" style="color: green">+</a>
			</p>
		</div>
		<div>
			<p>
			<?php if(!empty($tags)){
				echo '<a class="tag">'.$tags[0]['tag'].'</a>';
				for($i=1;$i<count($tags);$i++){ 
					echo ', <a class="tag">'.$tags[$i]['tag'].'</a>';
				}
				echo '.';
			} ?>
			</p>
		</div>
		<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
		<p><a id="deletePhoto" href="<?= Url::to(['site/index']) ?>"><?= \Yii::t('app', 'Удалить фото') ?></a></p>
		<?php } ?>
	</div>
	<div class="hidden">
		<?php $form = ActiveForm::begin([
			'id' => 'searchForm',
			'action' => Url::to(['site/index']),
		]) ?>
		<?= $form->field($search, 'text')->textarea(['id' => 'tag']) ?>
		<?= $form->field($search, 'type')->textInput(['value' => 'tag']) ?>
		<?php ActiveForm::end(); ?>
	</div>
	<?php if(!Yii::$app->user->isGuest){ ?>
	<div class="row">
		<div class="col-md-6">
			<?php $form = ActiveForm::begin(['id' => 'commentForm']) ?>
			<?= $form->field($model, 'body', ['options' => ['id' => 'commentBody']])
						->textarea(['id' => 'CommentForm[body]']) ?>
			<?= Html::submitInput(\Yii::t('app', 'Отправить'), ['class' => 'btn btn-primary']) ?>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
	<?php } ?>
	<?php 
		$provider = new ActiveDataProvider([
			'query' => $comments,
			'pagination' => [
				'pageSize' => 10,
			],
		]);
	?>
	<div id="comments" style="padding: 2%;"	>
		<?php foreach($provider->getModels() as $comment){ ?>
			<div class="container well" style="padding: 2%;">
				<div>
					<p class="col-md-6"><?= Html::encode($comment['fio']) ?></p>
					<p class="col-md-6"><?= Html::encode($comment['created']) ?></p>
				</div>
				<div id="comment<?= $comment['id'] ?>" class="container">
					<?= HtmlPurifier::process($comment['body']) ?>
				</div>
				<p class="col-md-offset-6 col-md-6">
					<a commentId=<?= $comment['id'] ?> class="commentvote" style="color: red">-</a>
					<span id="commentrate<?= $comment['id'] ?>"><?= $comment['rate']?$comment['rate']:0 ?></span>
					<a commentId=<?= $comment['id'] ?> class="commentvote" style="color: green">+</a>
				</p>
				<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
				<p>
					<a id="<?= $comment['id'] ?>" class="update" href="#commentBody"><?= \Yii::t('app', 'Изменить комментарий') ?></a>
					<a href="<?= Url::to(['ajax/deletecomment', 'id' => $comment['id']]) ?>"><?= \Yii::t('app', 'Удалить комментарий') ?></a>
				</p>
				<?php } ?>
			</div>
			<?php } ?>
		<?= LinkPager::widget([
			'pagination' => $provider->getPagination(),
		]) ?>
	</div>
	<?php
	if(!Yii::$app->user->isGuest){ ?>
		<script>
			var editor = CKEDITOR.replace('CommentForm[body]', {
				language: '<?= Yii::$app->session->has('language')?Yii::$app->session->get('language'):'ru' ?>',
			});
			$('#deletePhoto').click(function () {
				$.ajax({
					url: '<?= Url::to(['ajax/deleteimage']) ?>',
					method: 'POST',
					data: {
						'id': <?= $photo['id'] ?>,
					},
				})
				.done(function () {
					document.location('<?= Url::to(['site/index']) ?>');
				})
				.fail(function (msg) {
					console.log(msg);
				});
			});
			$('.update').click(function (event) {
				$('#commentForm').prop('action', '<?= Url::to(['site/updatecomment']) ?>?id='+$(this).prop('id'));
				editor.setData($('#comment'+$(this).prop('id')).html());
				$('input[type=submit]').val('<?= \Yii::t('app', 'Изменить') ?>');
			});
			$('.vote').click(function () {
				$.ajax({
					url: '<?= Url::to(['ajax/photovote']) ?>',
					method: 'POST',
					data: {
						vote: $(this).html()+"1",
						photo: <?= $photo['id'] ?>
					}
				})
				.done(function (msg) {
					$('#rate').html(msg);
				})
				.fail(function (msg){
					console.log(msg);
				});
			});
			$('.commentvote').click(function () {
				var commentId = $(this).attr('commentId');
				$.ajax({
					url: '<?= Url::to(['ajax/commentvote']) ?>',
					method: 'POST',
					data: {
						vote: $(this).html()+"1",
						comment: commentId
					}
				})
				.done(function (msg) {
					$('#commentrate'+commentId).html(msg);
				})
				.fail(function (msg){
					console.log(msg);
				});
			});
			$('.tag').click(function () {
				$('#tag').val($(this).html());
				$('#searchForm').submit();
			});
		</script>
	<?php } ?>
</div>
