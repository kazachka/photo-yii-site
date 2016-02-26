<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'assets/css/imgareaselect-animated.css',
    ];
    public $js = [
		'assets/js/jquery.imgareaselect.js',
		'assets/js/jquery.imgareaselect.min.js',
		'assets/js/jquery.imgareaselect.pack.js',
		'assets/js/ckeditor.js',
		'assets/js/ckeditor.js',
    ];
    public $jsOptions = [
		'position' => \yii\web\View::POS_HEAD,
	];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
