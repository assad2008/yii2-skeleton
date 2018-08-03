<?php
/**
 * @File Name: Module.php
 * @Author: assad
 * @Date:   2017-08-11 10:01:20
 * @Last Modified by:   assad
 * @Last Modified time: 2017-08-11 11:21:49
 * @Email: rlk002@gmail.com
 */

namespace app\modules\backend;

use Yii;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\modules\backend\controllers';
    public function init()
    {
        parent::init();
        \Yii::configure($this, require (__DIR__ . '/config.php'));
    }

}
