<?php
declare (strict_types = 1);

namespace app\vocpand\controller;
use app\vocpand\model\Answer;
use app\vocpand\validate\AnswerCommentValidate;
use app\vocpand\validate\JobCommentValidate;
use app\vocpand\model\Comment as CommentModel;
use app\vocpand\validate\OpusCommentValidate;
use think\Request;
class Comment extends CommonController
{
    /**
     *评论工作
     *
     */
    public function job(Request $request)
    {

        $data = $request->post();
        (new JobCommentValidate())->checkParam($data);
        CommentModel::saveComment($data);
        return $this->resultJson(0,'ok');
    }
    /**
     *评论才艺
     *
     */
    public function opus(Request $request)
    {

        $data = $request->post();
        (new OpusCommentValidate())->checkParam($data);
        CommentModel::saveComment($data);
        return $this->resultJson(0,'ok');
    }

//    回复评论
    public function answer(Request $request)
    {
        $data = $request->post();
        (new AnswerCommentValidate())->checkParam($data);
        Answer::saveAnswer($data);
        return $this->resultJson(0,'ok');
    }


}
