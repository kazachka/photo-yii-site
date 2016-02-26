<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Поиск');
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
								'class' => 'col-md-8',
							],
						])->render(
							'<input id="searchform-text" class="form-control" name="SearchForm[text]" type="text" style="width: 100%;" placeholder="'
							.Yii::t('app', 'Поиск').'"/>') ?>
	
	<?= $form->field($model, 'type', [
					'template' => '<div class="form-group">
										{input}
									</div>',
					'options' => [
						'class' => 'form-group',
					],
				])
				->dropdownList([
					'name' => \Yii::t('app', 'По названию'),
					'tags' => \Yii::t('app', 'По тегу'),
					'users' => \Yii::t('app', 'Пользователя'),
					'comments' => \Yii::t('app', 'Комментарии'),
				],[
					'class' => 'form-control',
				]) ?>
	
	<?= Html::submitButton(\Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
	
	<?php ActiveForm::end(); ?>
</div>
<?php $provider = new ActiveDataProvider([
	'query' 	 => $query,
	'pagination' => [
		'pageSize' => 50,
	]
]);
$items = $provider->getModels(); ?>
<div class="container" style="text-align: center; vertical-align: middle;">
<?php switch($model->type){
	case 'tags':
	foreach($items as $item){ ?>
		<div class="row">
			<div class="col-xs-6"><?= $item['tag'] ?></div>
			<div class="col-xs-2"><a id=<?= $item['tag'] ?> class="ajax" href=""><?= \Yii::t('app', 'Удалить') ?></a></div>
		</div>
	<?php }
	break;
	case 'users':
	foreach($items as $item){ ?>
		<div class="row">
			<a href="<?= Url::to(['site/photo', 'id' => $item['id']]) ?>">
				<div class="col-xs-2 thumbnail">
					<?= Html::img(
						$item['thumbnail']?'data:image/jpeg;base64,'.base64_encode($item['thumbnail']):'./web/img/user.png',[
						'alt' => $item['name'],
					]); ?>
				</div>
				<div class="col-xs-2"><?= $item['name'] ?></div>
				<div class="col-xs-2"><?= $item['username'] ?></div>
				<div class="col-xs-2"><?= $item['email'] ?></div>
				<div class="col-xs-2"><?= ($item['pol'] == 'male')?'М':'Ж' ?></div>
			</a>
			<div class="col-xs-2"><a id=<?= $item['id'] ?> class="ajax" href=""><?= \Yii::t('app', 'Удалить пользователя') ?></a></div>
		</div>
	<?php }
	break;
	case 'comments':
		foreach($items as $item){ ?>
			<div class="row">
				<a href="<?= Url::to(['site/image', 'id' => $item['photoId']]) ?>">
					<div class="col-xs-2"><?= $item['fio'] ?></div>
					<div class="col-xs-5"><?= $item['body'] ?></div>
					<div class="col-xs-2"><?= $item['created'] ?></div>
				</a>
				<div class="col-xs-3"><a id=<?= $item['id'] ?> class="ajax" href=""><?= \Yii::t('app', 'Удалить комментарий') ?></a></div>
			</div>
		<?php }
	break;
	case 'name': default:
	foreach($items as $item){ ?>
		<div class="row" >
			<a href="<?= Url::to(['site/image', 'id' => $item['id']]) ?>">
				<div class="col-xs-2 thumbnail">
					<?= Html::img('data:image/jpeg;base64,'.base64_encode($item['photo']),[
						'alt' => $item['name'],
					]) ?>
				</div>
				<div class="col-xs-4"><p><?= $item['name'] ?></p></div>
				<div class="col-xs-3"><?= $item['posted'] ?></div>
			</a>
			<div class="col-xs-3"><a id=<?= $item['id'] ?> class="ajax" href=""><?= \Yii::t('app', 'Удалить фото') ?></a></div>
		</div>
	<?php }
	break;
}
?>
</div>
<script>
	$('.ajax').click(function () {
		$.ajax({
			url: '<?php switch($model->type){
						case 'tags':
							echo Url::to(['ajax/deletetag']);
							break;
						case 'users':
							echo Url::to(['ajax/deleteuser']);
							break;
						case 'comments':
							echo Url::to(['ajax/deletecomment']);
							break;
						case 'name': default:
							echo Url::to(['ajax/deleteimage']);
							break;
						} ?>',
			method: 'POST',
			data: {
				<?php if($model->type == 'tags'){?>
					'tag': $(this).attr('id'),
				<?php }
				else{ ?>
					'id': $(this).attr('id'),
				<?php } ?>
			}
		})
		.done(function (msg) {
			console.log(msg);
		})
		.fail(function (msg) {
			console.log(msg);
		});
	});
</script>
