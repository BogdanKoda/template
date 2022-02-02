<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$path  = __DIR__;
$path = strtr($path, ["\\tests" => ""]);
echo $path;

require_once $path.'/vendor/yiisoft/yii2/Yii.php';
require $path.'/vendor/autoload.php';

$config = require $path.'/config/test.php';
new yii\web\Application($config);

Yii::setAlias('@tests', __DIR__);