<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once '../../vendor/yiisoft/yii2/Yii.php';
require '../../vendor/autoload.php';

$config = require '../../config/test.php';
new yii\web\Application($config);

Yii::setAlias('@tests', __DIR__);