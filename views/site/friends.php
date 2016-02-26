<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Друзья');
?>
<div class="container">
	<?php if($new){ ?>
	<h1><?= \Yii::t('app', 'Новые') ?></h1>
	<?php foreach($new as $people){ ?>
		<div class="container">
			<a href="<?= Url::to(['site/photo', 'id' => $people['id']]) ?>">
				<div class="col-sm-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
						'class' => 'thumbnail',
					]) ?>
				</div>
				<p class="col-sm-4"><?= $people['fio'] ?></p>
			</a>
			<a href="<?= Url::to(['site/friends', 'id' => $people['id']]) ?>" data-method="post" class="col-sm-5">
				<?= Yii::t('app', 'Добавить') ?>
			</a>
		</div>
		<br/>
	<?php } 
	}?>
	<h1><?= \Yii::t('app', 'Друзья') ?></h1>
	<?php foreach($friends as $people){ ?>
		<div class="container">
			<a href="<?= Url::to(['site/photo', 'id' => $people['id']]) ?>">
				<div class="col-sm-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
						'class' => 'thumbnail',
					]) ?>
				</div>
				<p class="col-sm-9"><?= $people['fio'] ?></p>
			</a>
		</div>
		<br/>
	<?php } ?>
	<h1><?= \Yii::t('app', 'Заявки') ?></h1>
	<?php Yii::trace($add) ?>
	<?php foreach($add as $people){ ?>
		<div class="container">
			<a href="<?= Url::to(['site/photo', 'id' => $people['id']]) ?>">
				<div class="col-sm-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
						'class' => 'thumbnail',
					]) ?>
				</div>
				<p class="col-sm-3"><?= $people['fio'] ?></p>
			</a>
		</div>
		<br/>
	<?php } ?>
</div>
