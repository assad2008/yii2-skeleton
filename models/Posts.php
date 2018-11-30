<?php
/**
 * @File Name: Posts.php
 * @Author: assad
 * @Date:   2017-08-10 17:02:28
 * @Last Modified by:   assad
 * @Last Modified time: 2018-09-12 17:35:33
 * @Email: rlk002@gmail.com
 */

namespace app\models;

use yii\db\ActiveRecord as AR;

class Posts extends AR
{
    public static function tableName()
    {
        return 'wp_posts';
    }
}
