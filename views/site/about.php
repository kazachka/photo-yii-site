<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'О нас');
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::t('app', 'Сделано в 2015') ?>
    </p>

</div>
