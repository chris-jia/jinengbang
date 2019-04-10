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
use think\Db;

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

    public function suggestions()
    {
        return $this->hasMany('Suggestion','user_id','user_id');
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

    // 确认 增加标签积分，改变报名状态，求助状态，消除其他报名状态
    public function applyConfirm($help_id,$apply_id,$score)
    {
        // 启动事务
        Db::startTrans();
        try {
            $user_id = session('user.user_id');
            $user = User::get($user_id);
            $help = Help::get($help_id);
            $apply = Apply::get($apply_id);
            $apply_user_id = $apply->user_id;
            $label_type = $help->askfor_type;
            //改变报名状态,消除其他报名状态
            $apply->status = 1;
            $apply->score = $score;
            $result1 = $apply->save();
            $apply = $help->applys()->where('apply_id','neq',$apply_id)
                ->where('status',0)->find();
            $err = false;
            while ($apply) {
                $apply->status = 2;
                $result = $apply->save();
                if ($result) {
                    $apply = $help->applys()->where('apply_id','neq',$apply_id)
                        ->where('status',0)->find();
                } else {
                    $err = true;
                    break;
                }
            }
            if ($err) {
                $result2 = false;
            } else {
                $result2 = true;
            }

            //改变求助状态
            $help->has_finshed = 1;
            $result3 = $help->save();

            //增加标签积分
            $label = $user->labels()->where('type',$label_type)->find();
            $label->score = $label->score + $score;
            $result4 = $label->save();

            if ($result1 and $result2 and $result3 and $result4) {
                // 提交事务
                Db::commit();
                return true;
            } else {
                // 回滚事务
                Db::rollback();
                return false;
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
    }

}