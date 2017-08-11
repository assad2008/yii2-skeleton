<?php

namespace app\modules\backend\controllers;

use app\components\BaseController as BController;
use app\modules\backend\Module;

class BbController extends BController
{
    public $view;

    public function init()
    {
        parent::init();
        $this->_view();
    }

    private function _view()
    {
        $view_dir = $this->module->params["view"]["view_dir"];
        $options = [
            "cache" => $this->module->params["view"]["view_cache"],
            "debug" => true,
            "charset" => "UTF-8",
        ];
        $this->view = new \app\components\BaseView($view_dir, $options);
    }

    public function beforeAction($action)
    {
        return true;
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $controller = $this->id;
        $action = $this->action->id;
        $this->view->display("{$controller}/{$action}.html");
        return;
    }
}
