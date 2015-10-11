<?php

use yii\helpers\Html;

$this->title = $user['fio'];
?>
<p class="lead"><a href="index.php?r=site/profile&id=1"><?= $user['fio'] ?></a></p>
<div class="row">
	<?php if(Yii::$app->user->identity->id != $user['id']){ ?>
	<p><a href="" data-method="post">Добавить в друзья</a></p>
	<?php }
	if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
	<p><a href="index.php?r=site/deleteuser&id=<?= $user['id'] ?>">Удалить пользователя</a></p>
	<?php } ?>
</div>
<div class="row">
	<?php foreach($photo as $image){ ?>
		<div class="col-md-4 text-center">
			<a href="index.php?r=site/image&id=<?= $image['id'] ?>" class="thumbnail">
				<?= Html::img('data:image/jpeg;base64,'.base64_encode($image['photo']),[
						'alt' => $image['name'],
					]); ?>
			</a>
			<p><?= $image['name'] ?></p>
			<?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'admin'){ ?>
			<p><a href="index.php?r=site/deleteimage&id=<?= $image['id'] ?>">Удалить фото</p>
			<?php } ?>
		</div>
	<?php } ?>
</div>
