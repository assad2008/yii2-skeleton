<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\Posts;

class SiteController extends BaseController
{

    public function actionIndex()
    {
        $post = Posts::findOne(2);
        $hello = "Welcome YeeYii2";
        $this->view->assign("post", $post);
        $this->view->assign("hello", $hello);
        $this->view->display("index.html");
    }
}
