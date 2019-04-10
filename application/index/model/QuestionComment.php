<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 15:24
 */

namespace app\index\model;


use app\index\common\BaseModel;
use think\Db;

class QuestionComment extends BaseModel
{
    protected $table = 'question_comment';
    protected $pk = 'question_comment_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function question()
    {
        return $this->belongsTo('Question');
    }

    public function saveComment($data,$prior)
    {
        // 启动事务
        Db::startTrans();
        try {
            $question_id = $data['question_id'];
            $user_id = session('user.user_id');
            $user = User::get($user_id);
            $result1 = $user->questionComments()->save([
                'content' =>$data['content'], 'prior' => $prior,'question_id'=>$data['question_id']
            ]);
            $question = Question::get($question_id);
            $question_user_id = $question->user_id;
            $content = '你有提问被回复了，快去看看吧';
            $result2 = Message::addContent($content,$question_user_id);

            // 提交事务
            if ($result1 and $result2) {
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

    static public function deleteWithPrior($question_comment_id)
    {
        // 启动事务
        Db::startTrans();
        try {
            self::destroy($question_comment_id);
            self::destroy(['prior' => $question_comment_id]);
            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
    }

}