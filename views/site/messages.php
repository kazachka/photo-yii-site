<?php

use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'Сообщения');
?>
<?php 
$provider = new ActiveDataProvider([
	'query' => $messages,
	'pagination' => [
		'pageSize' => 10,
	],
]); ?>
<br/>
<div class="row" style="width: 70%; margin-left: 10%;">
	<div>
		<?php $form = ActiveForm::begin(['id' => 'commentForm']) ?>
		<?= $form->field($model, 'body')->textarea(['id' => 'CommentForm[body]']) ?>
		<?= Html::submitInput(Yii::t('app', 'Отправить'), ['class' => 'btn btn-primary']) ?>
		<?php ActiveForm::end(); ?>
	</div>
</div>
<br/>
<?php foreach($provider->getModels() as $message){ ?>
	<div class="container well" style="width: 70%; margin-left: 10%;">
		<div class="row">
			<a href="<?= Url::to(['site/photo', 'id' => $message['author']]) ?>" class="pull-left">
				<?= Html::encode($message['fio']) ?>
			</a>
			<p class="pull-right">
				<?= $message['created'] ?>
			</p>
		</div>
		<div class="row">
			<?= HtmlPurifier::process($message['body']) ?>
		</div>
	</div>
<?php } ?>
<div class="col-xs-offset-1">
	<?= LinkPager::widget([
		'pagination' => $provider->getPagination(),
	]) ?>
</div>
<script>
	CKEDITOR.replace('CommentForm[body]', {
		language: '<?= Yii::$app->session->has('language')?Yii::$app->session->get('language'):'ru' ?>',
	});
</script>
