<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:53
 */

namespace app\index\model;


use app\index\common\BaseModel;

class User extends BaseModel
{
    protected $table = 'user';
    protected $pk = 'user_id';

    public function labels()
    {
        return $this->hasMany('Label','user_id','user_id');
    }

    public function helps()
    {
        return $this->hasMany('Help','user_id','user_id');
    }

    public function applys()
    {
        return $this->hasMany('Apply','user_id','user_id');
    }

    public function questions()
    {
        return $this->hasMany('Question','user_id','user_id');
    }

    public function helpComments()
    {
        return $this->hasMany('HelpComment','user_id','user_id');
    }

    public function questionComments()
    {
        return $this->hasMany('QuestionComment','user_id','user_id');
    }

    public function messages()
    {
        return $this->hasMany('Message','user_id','user_id');
    }

    public function isHadLabelType($type)
    {
        $result = $this->labels()->where('type',$type)->find();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //是否已经报名此报名
    public function is_apply($help_id)
    {
        $user_id = session('user.user_id');
        $user = User::get($user_id);
        $apply = $user->applys()->where('help_id',$help_id)->find();
        if ($apply) {
            return true;
        } else {
            return false;
        }
    }

    //是否有此求助
    public function has_help($help_id)
    {
        $user_id = session('user.user_id');
        $user = User::get($user_id);
        $help = $user->helps()->where('help_id',$help_id)->find();
        if ($help) {
            return true;
        } else {
            return false;
        }
    }

    //用户有新消息
    static public function hasNewMessage($user_id)
    {
        $user = User::get($user_id);
        if ($user->new_message == 0) {
            $user->new_message = 1;
            $result = $user->save();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}