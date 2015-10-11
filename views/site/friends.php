<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '';
?>
<div class="row">
	<?php if($new){ ?>
	<h1>Новые</h1>
	<?php foreach($new as $people){ ?>
		<div class="row">
			<a href="index.php?r=site/photo&id=<?= $people['id'] ?>">
				<div class="col-md-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
					]) ?>
				</div>
				<p class="col-md-9"><?= $people['fio'] ?></p>
			</a>
		</div>
	<?php } 
	}?>
	<h1>Друзья</h1>
	<?php foreach($friends as $people){ ?>
		<div class="row">
			<a href="index.php?r=site/photo&id=<?= $people['id'] ?>">
				<div class="col-md-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
					]) ?>
				</div>
				<p class="col-md-9"><?= $people['fio'] ?></p>
			</a>
		</div>
	<?php } ?>
	<h1>Заявки</h1>
	<?php foreach($add as $people){ ?>
		<div class="row">
			<a href="index.php?r=site/photo&id=<?= $people['id'] ?>">
				<div class="col-md-3">
					<?= Html::img($people['photo']?'data:image/jpeg;base64,'.base64_encode($people['photo']):'./web/img/user.png', [
						'alt' => $people['fio'],
						'height' => '100',
					]) ?>
				</div>
				<p class="col-md-9"><?= $people['fio'] ?></p>
			</a>
		</div>
	<?php } ?>
</div>
