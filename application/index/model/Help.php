<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/26
 * Time: 21:39
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Help extends BaseModel
{
    protected $table = 'help';
    protected $pk = 'help_id';
    public function user()
    {
        return $this->belongsTo('User');
    }

}