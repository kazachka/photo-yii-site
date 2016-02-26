<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Диалоги');
?>
<div class="row" style="margin: 0 10% 0 10%;">
	<?php foreach($conferences as $conference){ ?>
		<a href="<?= Url::to(['site/messages', 'id' => $conference['id']]) ?>">
			<div class="well">
				<?= $conference['name'] ?>
			</div>
		</a>
	<?php } ?>
</div>
