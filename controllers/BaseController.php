<?php
/**
 * @File Name: BaseController.php
 * @Author: assad
 * @Date:   2017-08-10 15:25:39
 * @Last Modified by:   assad
 * @Last Modified time: 2017-08-10 16:59:15
 * @Email: rlk002@gmail.com
 */

namespace app\controllers;

use Yii;
use yii\web\Controller as Yii2_Controller;
use yii\web\Response;

class BaseController extends Yii2_Controller
{

    public $input;
    public $output;
    public $session;
    public $cookie;
    public $app;
    public $view;

    public function init()
    {
        parent::init();
        $this->app = Yii::$app;
        $this->_io();
        $this->_view();
    }

    private function _io()
    {
        $this->input = $this->app->request;
        $this->output = $this->app->response;
        $this->session = $this->app->session;
        $this->cookie = $this->app->request->cookies;
    }

    private function _view()
    {
        $view_dir = $this->app->params["view"]["view_dir"];
        $options = [
            "cache" => $this->app->params["view"]["view_cache"],
            "debug" => true,
            "charset" => "UTF-8",
        ];
        $this->view = new \ViewBase($view_dir, $options);
    }
}
