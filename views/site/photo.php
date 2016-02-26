<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $user['fio'];
?>
<div class="container">
	<div class="row">
		<div class="col-md-2 col-sm-3">
			<?= Html::img($user['photo']?'data:image/jpeg;base64,'.base64_encode($user['photo']):'./web/img/user.png',[
					'alt' => $user['fio'],
					'class' => 'thumbnail',
					'style' => 'height: 160px;',
				]); ?>
		</div>
		<p class="lead">
			<a href="<?= Yii::$app->user->isGuest?'':Url::to(['site/profile', 'id' => $user['id']]) ?>">
				<?= $user['fio'] ?>
			</a>
		</p>
		<?php if(!Yii::$app->user->isGuest){
			if(Yii::$app->user->identity->id != $user['id']){ 
				if($isFriend == null){ ?>
					<p class="friends" id="addto"><a><?= Yii::t('app', 'Добавить в друзья') ?></a></p>
				<?php }
				elseif($isFriend['to'] != null && $isFriend['from'] == null){ ?>
					<p class="friends" id="delete"><a><?= Yii::t('app', 'Отозвать заявку') ?></a></p>
				<?php 
				} 
				else{ ?>
					<p class="friends" id="delete"><a><?= Yii::t('app', 'Удалить из друзей') ?></a></p>
				<?php } ?>
				<a href="<?= Url::to(['site/getconference', 'id' => $user['id']]) ?>"><?= Yii::t('app', 'Написать сообщение') ?></a>
			<?php }
			if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
				<p><a id="deleteUser" href=""><?= Yii::t('app', 'Удалить пользователя') ?></a></p>
			<?php }
		} ?>
	</div>
</div>
<div class="container">
	<div class="row">
	<?php for($i = 0 ; $i < count($photo) ; $i++){ ?>
		<?php if($i % 3 == 0 && $i != 0){ ?>
			</div>
			<div class="row">
		<?php } ?>
		<div class="col-md-4 text-center form-group">
			<a href="<?= isset($photo[$i]['posted'])?Url::to(['site/image', 'id' => $photo[$i]['id']]):Url::to(['site/photo', 'id' => $photo[$i]['id']]) ?>" class="thumbnail">
				<?= Html::img(
						$photo[$i]['thumbnail']?'data:image/jpeg;base64,'.base64_encode($photo[$i]['thumbnail']):'./web/img/user.png',[
						'id' => 'photo'.$photo[$i]['id'],
						'alt' => $photo[$i]['name'],
					]) ?>
			</a>
			<p><?= $photo[$i]['name'] ?></p>
			<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->id == $user['id']){ ?>
			<p class="form-group pull-right" for="photo<?= $photo[$i]['id'] ?>">
				<a href="<?= Url::to(['site/edit', 'id' => $photo[$i]['id']]) ?>">
					<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
				</a>
				<a id=<?= $photo[$i]['id'] ?> class="deletePhoto" href="">
					<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
				</a>
			</p>
			<br/>
			<?php } ?>
		</div>
	<?php } ?>
	</div>
</div>
<script>
	$('#deleteUser').click(function () {
		$.ajax({
			url: '<?= Url::to(['ajax/deleteuser']) ?>',
			method: 'POST',
			data: {
				'id': <?= $user['id'] ?>,
			},
		})
		.done(function (msg) {
			if(msg == true){
				document.location('<?= Url::to(['site/index.php']) ?>');
			}
		})
		.fail(function (msg) {
			console.log(msg);
		});
	});
	$('.deletePhoto').click(function () {
		$.ajax({
			url: '<?= Url::to(['ajax/deleteimage']) ?>',
			method: 'POST',
			data: {
				'id': $(this).attr('id'),
			}
		})
		.fail(function (msg) {
			console.log(msg);
		});
	});
	$('.friends').click(function () {
		var p = $(this);
		$.ajax({
			url: '<?= Url::to(['ajax/friends']) ?>',
			method: 'POST',
			data: {
				'id': <?= Yii::$app->request->get('id') ?>,
				'action': $(this).attr('id'),
			}
		})
		.done(function (msg) {
			if(p.attr('id') == 'addto'){
				p.attr('id', 'delete');
			}
			else{
				p.attr('id', 'addto');
			}
			p.html(msg);
		})
		.fail(function (msg) {
			console.log(msg);
		});
	});
</script>
