<?php

/**
 * @Author: assad
 * @Date:   2019-06-12 12:15:37
 * @Last Modified by:   assad
 * @Last Modified time: 2019-06-12 12:15:55
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
