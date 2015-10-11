<?php

use yii\helpers\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<?php foreach($messages as $message){ ?>
		<div class="row">
			<a href="index.php?r=site/conference&id=<?= $message['id'] ?>">
				<?= $message['user'] ?>
			</a>
		</div>
	<?php } ?>
</div>
