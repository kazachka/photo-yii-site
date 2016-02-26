<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Редактор');
?>
<div class="row">
	<div class="col-sm-8 thumbnail text-center" id="testWrap">
		<?= Html::img('data:image/jpeg;base64,'.base64_encode($photo['thumbnail']),[
				'id' => 'photo',
				'alt' => Yii::t('app', 'Фотография'),
			]) ?>
	</div>
	<div class="col-sm-4 form-group" style="padding: 1%;">
		<a href="#spoiler-filters" data-toggle="collapse" class="btn col-xs-12">
			<?= Yii::t('app', 'Фильтры') ?>
			<span class="caret"></span>
		</a>
		<div class="collapse form-group" id="spoiler-filters">
			<button class="btn btn-primary col-xs-12" kind="filter" id="тщту"><?= \Yii::t('app', 'Без фильтра') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="wb"><?= \Yii::t('app', 'Чёрно-белое') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="sepia"><?= \Yii::t('app', 'Сепия') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="polaroid"><?= \Yii::t('app', 'Полароид') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="hipster"><?= \Yii::t('app', 'Синий') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="latte"><?= \Yii::t('app', 'Латте') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="vintage"><?= \Yii::t('app', 'Винтаж') ?></button>
			<button class="btn btn-primary col-xs-12" kind="filter" id="posterize"><?= \Yii::t('app', 'Постеризация') ?></button>
		</div>
		<a href="#spoiler-change" data-toggle="collapse" class="btn col-xs-12">
			<?= Yii::t('app', 'Изменение изображения') ?>
			<span class="caret"></span>
		</a>
		<div class="collapse" id="spoiler-change">
			<button class="btn btn-primary col-xs-12" kind="change" id="rotateleft"><?= \Yii::t('app' ,'Повернуть влево') ?></button>
			<button class="btn btn-primary col-xs-12" kind="change" id="rotateright"><?= \Yii::t('app', 'Повернуть вправо') ?></button>
			<button class="btn btn-primary col-xs-12" kind="change" id="flip"><?= \Yii::t('app', 'Отразить по вертикали') ?></button>
			<button class="btn btn-primary col-xs-12" kind="change" id="flop"><?= \Yii::t('app', 'Отразить по горизонтали') ?></button>
			<button class="btn btn-primary col-xs-12" kind="change" id="negative"><?= \Yii::t('app', 'Негатив') ?></button>
		</div>
		<a href="#spoiler-reducenoise" data-toggle="collapse" class="btn col-xs-12" >
			<?= \Yii::t('app', 'Уменьшить шум') ?>
			<span class="caret"></span>
		</a>
		<div class="collapse" id="spoiler-reducenoise">
			<p><?= \Yii::t('app', 'Уменьшить шум') ?></p>
			<div style="padding: 1%;">
				<input id="reducenoise" kind="reducenoise" type="range" min="0" max="10" step="1" value="0" />
			</div>
			<p class="alert alert-warning" role="alert"><?= \Yii::t('app', 'При больших значениях увеличивается время обработки.') ?></p>
		</div>
		<a href="#spoiler-blur" data-toggle="collapse" class="btn col-xs-12" >
			<?= \Yii::t('app', 'Размыть') ?>
			<span class="caret"></span>
		</a>
		<div class="collapse" id="spoiler-blur">
			<p><?= \Yii::t('app', 'Размыть') ?></p>
			<input id="blur" kind="blur" type="range" min="0" max="30" step="1" value="0" />
		</div>
		<a data-target="#spoiler-brightness" data-toggle="collapse" class="btn col-xs-12">
			<?= \Yii::t('app', 'Яркость') ?>
			<span class="caret"></span>
		</a>
		<div class="collapse" id="spoiler-brightness">
			<p><?= \Yii::t('app', 'Яркость') ?></p>
			<input id="brightness" kind="brightness" type="range" min="-100" max="100" step="1" value="0"/>
		</div>
		<button class="btn btn-primary col-xs-12" kind="change" id="crop"><?= \Yii::t('app', 'Обрезать') ?></button>
		<a href="<?= Url::to(['site/photo']) ?>" class="btn btn-primary col-xs-6" id="save"><?= \Yii::t('app', 'Сохранить') ?></a>
		<a href="<?= Url::to(['site/photo']) ?>" class="btn btn-primary col-xs-6" id="cancel"><?= \Yii::t('app', 'Отменить') ?></a>
	</div>
	<script>
		var crop = $('#photo').imgAreaSelect({
			instance: true,
			handles: true,
		});
		$('#photo').addClass('photo');
		$(':button').click(function () {
			var action = $(this).prop('id');
			if(action != 'crop'){
				var kind = $(this).attr('kind');
				$.ajax({
					url: '<?= Url::to(['ajax/get']) ?>',
					method: 'POST',
					data: {
						'id': <?= $photo['id'] ?>,
						'do': action,
						'kind': kind,
					}
				})
				.done(function (msg) {
					$('#photo').prop('src',msg);
				})
				.fail(function (msg) {
					console.log(msg);
				});
			}
			else{
				var kind = crop.getSelection();
				$.ajax({
					url: '<?= Url::to(['ajax/get']) ?>',
					method: 'POST',
					data: {
						'id': <?= $photo['id'] ?>,
						'do': action,
						'kind': kind,
					}
				})
				.done(function (msg) {
					$('#photo').prop('src',msg);
					crop.cancelSelection();
				})
				.fail(function (msg) {
					console.log(msg);
				});
			}
		});
		$('input[type=range]').mouseup(function () {
			var action=$(this).prop('id');
			var kind = $(this).val();
			$.ajax({
				url: '<?= Url::to(['ajax/get']) ?>',
				method: 'POST',
				data: {
					'id': <?= $photo['id'] ?>,
					'do': action,
					'kind': kind,
				}
			})
			.done(function (msg) {
				$('#photo').prop('src',msg);
			})
			.fail(function (msg) {
				console.log(msg);
			});
		});
		$('#save').click(function (event) {
			event.preventDefault();
			$.ajax({
				url: '<?= Url::to(['ajax/save']) ?>',
				method: 'POST',
				data: {
					'id': <?= $photo['id'] ?>,
				}
			})
			.done(function () {
				document.location = '<?= Url::to(['site/photo']) ?>';
			})
			.fail(function (msg) {
				console.log(msg);
				alert('Невозможно сохранить');
			});
		});
		$('#cancel').click(function (event) {
			event.preventDefault()
			$.ajax({
				url: '<?= Url::to(['ajax/cancel']) ?>',
				method: 'POST',
				data: {
					'id': <?= $photo['id'] ?>,
				}
			})
			.done(function () {
				document.location = '<?= Url::to(['site/photo']) ?>';
			})
			.fail(function (msg) {
				alert('Невозможно выйти');
				console.log(msg);
			});
		});
		$(window).unload(function (event) {
			$.ajax({
				url: '<?= Url::to(['ajax/cancel']) ?>',
				method: 'POST',
				data: {
					'id': <?= $photo['id'] ?>,
				}
			});
		});
	</script>
</div>
