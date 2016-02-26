<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<div class="navbar-static-top navbar-inverse">
		<div class="panel-heading">
			<style>
				.head{
					font-size: 18px;
					font-weight: bold;
					font-family: "Century Schoolbook L";
					color: white;
					text-align: center;
				}
				
				.head:hover, .head:focus{
					color: white;
					text-decoration: none;
				}
			</style>
			<a href="<?= Url::to(['site/index']) ?>" class="head" ><?= Yii::t('app', 'Весёлые картинки') ?></a>
		</div>
	</div>
    <div class="navbar-collapse" style="padding: 2%; margin: 0 10% 0 10%">
		<?php if(Yii::$app->user->isGuest) {?>
			<p class="pull-right">
				<?= Yii::t('app', 'Вы не авторизованы') ?>
				<a href="<?= Url::to(['site/login']) ?>"><?= Yii::t('app', 'Войти') ?></a>
				<a href="<?= Url::to(['site/register']) ?>"><?= Yii::t('app', 'Зарегистрироваться') ?></a>
			</p>
		<?php }
		else { ?>
			<p class="pull-left">
				<?= Yii::t('app', 'Пользователь:') ?>
				<a href="<?= Url::to(['site/profile']) ?>">
					<?= Html::encode(Yii::$app->user->identity->fio) ?>
				</a>
			</p>
			<p class="pull-right">
				<?php if(Yii::$app->user->identity->role == 'admin'){ ?>
					<a href="<?= Url::to(['site/search']) ?>" style="color: green;">
						<?= Yii::t('app', 'Страница администратора') ?>
					</a>
				<?php } ?>
				<a href="<?= Url::to(['site/logout']) ?>" data-method="post"><?= Yii::t('app', 'Выйти') ?></a>
			</p>
		<?php } ?>
    </div>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container" style="margin-top: 5%; margin-bottom: 5%">
		<div class="pull-left">
			<a href="<?= Url::to(['site/lang', 'lang' => 'ru']) ?>"><?= Yii::t('app', 'Русский') ?></a>
			<a href="<?= Url::to(['site/lang', 'lang' => 'en']) ?>"><?= Yii::t('app', 'Английский') ?></a>
			<a href="<?= Url::to(['site/lang', 'lang' => 'uk']) ?>"><?= Yii::t('app', 'Украинский') ?></a>
        </div>
        <div class="pull-right">
			<a href="<?= Url::to(['site/contact']) ?>"><?= Yii::t('app', 'Контакты') ?></a>
			<a href="<?= Url::to(['site/about']) ?>"><?= Yii::t('app', 'О нас') ?></a>
        </div>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
