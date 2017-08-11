<?php
/**
 * @File Name: BaseController.php
 * @Author: assad
 * @Date:   2017-08-11 11:44:22
 * @Last Modified by:   assad
 * @Last Modified time: 2017-08-11 11:47:39
 * @Email: rlk002@gmail.com
 */

namespace app\components;

use Yii;
use yii\web\Response;

class BaseController extends \yii\web\Controller
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
    }

    private function _io()
    {
        $this->input = $this->app->request;
        $this->output = $this->app->response;
        $this->session = $this->app->session;
        $this->cookie = $this->app->request->cookies;
    }
}
