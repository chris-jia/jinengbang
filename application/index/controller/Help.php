<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/26
 * Time: 21:39
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;
use app\index\model\Help as HelpModel;

class Help extends BaseController
{
    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //存储
            $result = $user->helps()->save([
                'title' => $data['title'],
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type'],
                'type' => $data['type'],
                // todo 图片待存储
            ]);
            if ($result) {
                return success();
            } else {
                return error('新增失败');
            }

        } else {
            return error($checkResult);
        }

    }

    public function update($help_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //查找
            $help = $user->helps()->where('help_id',$help_id)->find();
            //验证
            if (empty($help)){
                return error('你没有此求助');
            }

            $result = $help->save([
                'title' => $data['title'],
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type'],
                'type' => $data['type'],
                // todo 图片待存储
            ]);
            if ($result) {
                return success();
            } else {
                return error('更新失败');
            }
        } else {
            return error($checkResult);
        }

    }

    public function delete($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help= $user->helps()->where('help_id',$help_id)->find();
        if (empty($help)) {
            return error('你没有此求助');
        } else {
            $result = $help->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }
    }

    public function getOne($help_id)
    {
        $help = HelpModel::get($help_id);
        if ($help) {
            $help->browse = $help->browse + 1;
            $result = $help->save();
            if ($result) {
                return success($help);
            } else {
                return error('获取失败');
            }
        } else {
            return error('你没有此求助');
        }
        

    }

    public function getAll()
    {
        //todo 分页
        $type = input('has_finished');
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        if ($type == 0) {
            $helps = $user->helps()->where('has_finished',0)->select();
        } else if ($type == 1) {
            $helps = $user->helps()->where('has_finished',1)->select();
        } else {
            return error('参数错误');
        }
        if (count($helps)) {
            return success($helps);
        } else {
            return error('你没有求助');
        }

    }

    public function getAllHelps()
    {
        $is_free = input('is_free');
        $askfor_type = input('askfor_type');
        $publisher = input('publisher');
        $help = new HelpModel();
        $help = $help->where(true);
        if ($askfor_type != 'all') {
            $help = $help->where('askfor_type',$askfor_type);
        }
        if ($is_free != 'all') {
            $help = $help->where('is_free',$is_free);
        }
        if ($publisher != 'all') {
            $help = $help->where('publisher',$publisher)->select();
        } else {
            $help = $help->where(true)->select();
        }
        if (count($help)) {
            return success($help);
        } else {
            return error('没有内容');
        }
    }

    public function getHelpsByWord()
    {
        $word = input('word');
        $help = new HelpModel();
        $help = $help->where('title|content','like','%'.$word.'%')->select();
        if (count($help)) {
            return success($help);
        } else {
            return error('没有内容');
        }
    }

    public function helpLike($help_id)
    {
        $help = HelpModel::get($help_id);
        if (empty($help)) {
            return error('此求助不存在');
        }
        $help->like = $help->like + 1;
        $result = $help->save();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function complaintHelp($help_id)
    {
        $help = HelpModel::get($help_id);
        //验证
        if (empty($help)) {
            return error('该求助不存在');
        }
        if ($help->is_complaint == 1) {
            return error('该求助已被投诉，请等待管理员处理');
        }
        // 操作
        $help->is_complaint = 1;
        $result = $help->save();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function adminGet()
    {
        $mode = input('mode');
        // 验证
        if (!is_admin())
        {
            return error('没有权限');
        }
        $help = new HelpModel();
        if ($mode == 'all') {
            $help = $help->where(true)->select();
        } elseif ($mode == 1) {
            $help = $help->where('is_complaint',1)->select();
        } else {
            return error('参数错误');
        }
        $total = count($help);
        if (!$total) {
            return error('没有内容');
        } else {
            $data = array('total'=>$total,'data'=>$help);
            return success($data);
        }
    }

    public function adminDelete($help_id)
    {
        $help = HelpModel::get($help_id);
        //验证
        if (empty($help)) {
            return error('该求助不存在');
        }
        if (!is_admin()) {
            return error('没有权限');
        }
        // 操作
        $result = $help->delete();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }
}