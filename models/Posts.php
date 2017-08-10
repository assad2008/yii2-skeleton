<?php
/**
 * @File Name: Posts.php
 * @Author: assad
 * @Date:   2017-08-10 17:02:28
 * @Last Modified by:   assad
 * @Last Modified time: 2017-08-10 17:06:25
 * @Email: rlk002@gmail.com
 */

namespace app\models;

use yii\db\ActiveRecord;

class Posts extends ActiveRecord
{
    public static function tableName()
    {
        return 'wp_posts';
    }
}
