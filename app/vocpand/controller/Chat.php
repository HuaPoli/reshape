<?php
declare (strict_types = 1);

namespace app\vocpand\controller;



use think\facade\App;
use think\Request;
use think\facade\Db;
class Chat extends CommonController
{
    /**
     * 根据用户id获取聊天双方的头像信息；
     */
    public function get_head(Request $request){

        $data = $request->post();
        if(isset($data['fromid']) && isset($data['toid'])){
            $frominfo = Db::name('user')->where('id',$data['fromid'])->value('uimg');
            $toinfo = Db::name('user')->where('id',$data['toid'])->value('uimg');
            return $this->resultJson(0,'ok', ['from_head'=>$frominfo, 'to_head'=>$toinfo]);
        }
    }

    /**
     * 页面加载返回聊天记录
     */
    public function load(Request $request){
        $data = $request->post();
        if(isset($data['fromid']) && isset($data['toid'])){
            $sql = "select count(*) as mcount from communication where (fromid=:fromid and toid=:toid) or (fromid=:toid1 and toid=:fromid1)";
            $count =  Db::query($sql, ['fromid' => $data['fromid'],'toid'=>$data['toid'],'toid1'=>$data['toid'],'fromid1'=>$data['fromid']])[0];
            if($count['mcount'] >=10){
                $start = $count['mcount'] - 10 ;
                $sql = "select * from communication where (fromid=:fromid and toid=:toid) or (fromid=:toid1 and toid=:fromid1) order by id limit $start,10 ";
                $message = Db::query($sql, ['fromid' => $data['fromid'],'toid'=>$data['toid'],'toid1'=>$data['toid'],'fromid1'=>$data['fromid']]);
            }else{
                $sql = "select * from communication where (fromid=:fromid and toid=:toid) or (fromid=:toid1 and toid=:fromid1) order by id";
                $message = Db::query($sql, ['fromid' => $data['fromid'],'toid'=>$data['toid'],'toid1'=>$data['toid'],'fromid1'=>$data['fromid']]);
            }
            return $this->resultJson(0, 'ok', $message);
        }
    }


    /**
     *文本消息的数据持久化
     */
    public function save_message(Request $request){
        if($request->isAjax()){
            $message = $request->post();
            $datas['fromid']=$message['fromid'];
            $datas['fromname']= $this->getName($datas['fromid']);
            $datas['toid']=$message['toid'];
            $datas['toname']= $this->getName($datas['toid']);
            $datas['content']=$message['data'];
            $datas['time']=$message['time'];
            //$datas['isread']=$message['isread'];
            $datas['isread']=0;
            $datas['type'] = 1;
            Db::name("communication")->insert($datas);
        }
    }
    /**
        改变已读状态
     */
    public function changeNoRead(Request $request){
        if($request->isAjax()){
            $fromid = $request->post('fromid');
            $toid = $request->post('toid');
            Db::name('communication')->where(['fromid'=>$fromid,"toid"=>$toid])->update(['isread'=>1]);
        }

    }
//    发送图片
    public function uploadimg(Request $request)
    {
        $file = $_FILES['file'];
        $fromid = input('fromid');
        $toid = input('toid');
        $online = input('online');

        $suffix =  strtolower(strrchr($file['name'],'.'));
        $type = ['.jpg','.jpeg','.gif','.png'];
        if(!in_array($suffix,$type)){
            return $this->resultJson(2,'图片格式不正确');
        }

        if($file['size']/1024>5120){
            return $this->resultJson(2,'文件太大');
        }

        $filename =  uniqid("chat_img_",false);
        $uploadpath = App::getRootPath(). 'public\\uploads\\';
        $file_up = $uploadpath.$filename.$suffix;
        $re = move_uploaded_file($file['tmp_name'],$file_up);

        if($re){
            $name = $filename.$suffix;
            $data['content'] = $name;
            $data['fromid'] = $fromid;
            $data['toid'] = $toid;
            $data['fromname'] = $this->getName($data['fromid']);
            $data['toname'] = $this->getName($data['toid']);
            $data['time'] = time();
            // $data['isread'] = $online;
            $data['isread'] = 0;
            $data['type'] = 2;
            $message_id = Db::name('communication')->insertGetId($data);
            if($message_id){
                return $this->resultJson(0,'ok',['img_name'=>$name]);
            }else{
                return $this->resultJson(199,'ok');
            }

        }
    }


    public function get_name(Request $request)
    {
        $uid = input('uid');
        $uname = Db::name('user')->where('id',$uid)->value('uname');
        return $this->resultJson(0,'ok',['uname'=>$uname]);
    }

    private function getName($id)
    {
        return Db::name('user')->where('id',$id)->value('uname');
    }



    /**
     * 根据fromid来获取当前用户聊天列表
     */
    public function get_list(Request $request){

        $fromid = input('id');
        $info = Db::name('communication')->field(['fromid', 'toid', 'fromname'])->where('toid', $fromid)
            ->group('fromid')->select()->toArray();
        $rows = array_map(function($res){
            return [
                'head_url'=>$this->get_head_one($res['fromid']),
                'username'=>$res['fromname'],
                'countNoread'=>$this->getCountNoread($res['fromid'],$res['toid']),
                'last_message'=>$this->getLastMessage($res['fromid'],$res['toid']),
                'fromid'=>$res['toid'],
                'toid' =>$res['fromid']
            ];

        },$info);

        return $this->resultJson(0,'ok',$rows);

    }

    /**
     *
     *  根据uid获取用户头像
    */
    public function get_head_one($uid){

       return  Db::name('user')->where('id',$uid)->value('uimg');

    }

    /**
     * @param $fromid
     * @param $toid
     *  根据fromid来获取fromid 发送个 toid 的未读消息
     */
    public function getCountNoread($fromid,$toid){

        return Db::name('communication')->where(['fromid'=>$fromid,'toid'=>$toid,'isread'=>0])->count('id');

    }

    /**
     * @param $fromid
     * @param $toid
     * 根据fromid和toid来获取他们聊天的最后一条数据
     */
    public function getLastMessage($fromid,$toid){

        $info = Db::name('communication')
            ->where([['fromid','=',$fromid], ['toid','=',$toid]])
            ->whereOr([['fromid','=',$toid],['toid','=',$fromid]])->order('id','DESC')
            ->limit(1)->select()->toArray();
        return $info[0];
    }


}
