<?php
/**
 * @File Name: BaseController.php
 * @Author: assad
 * @Date:   2017-08-10 15:25:39
 * @Last Modified by:   assad
 * @Last Modified time: 2017-08-11 12:00:23
 * @Email: rlk002@gmail.com
 */

namespace app\controllers;

use app\components\BaseController as BController;

class BaseController extends BController
{
    public $view;

    public function init()
    {
        parent::init();
        $this->_view();
    }

    private function _view()
    {
        $view_dir = $this->app->params["view"]["view_dir"];
        $options = [
            "cache" => $this->app->params["view"]["view_cache"],
            "debug" => true,
            "charset" => "UTF-8",
        ];
        $this->view = new \app\components\BaseView($view_dir, $options);
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
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
