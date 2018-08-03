<?php

namespace app\modules\backend\controllers;

use app\modules\backend\controllers\BbController;

class DefaultController extends BbController
{

    public function actionIndex()
    {
        $this->view->assign("hello", "welcome backend index");
    }

}
