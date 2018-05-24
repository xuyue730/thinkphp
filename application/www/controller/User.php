<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/17 14:01
 */

namespace app\www\controller;


use app\common\exception\Cake;
use app\common\service\UserService;

/**
 * 控制器类 前端用户
 * @package app\www\controller
 */
class User extends Base
{
    /**
     * 演示
     */
    public function index(){
        $uid = $this->get_uid();

    }

    /**
     * 注册 保存
     * @return \think\response\Json
     */
    public function register_save(){
        //@todo 数据库 用户名、手机、email重复提示
        $param = request()->param();
        try{
            $user = UserService::create($param);
            UserService::doSessionLogin($user);
            return $this->msg("ok");
        }catch (Cake $e){
            return $this->err($e);
        }

    }

    /**
     * 登录 表单检查
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function check_login(){
        $param = request()->param();
        try{
            $user = UserService::checkLogin($param);
            UserService::doSessionLogin($user);
            return $this->msg("ok");
        }catch (Cake $e){
            return $this->err($e);
        }
    }

    /**
     * 注销
     */
    public function logout(){
        UserService::doSessionLogout();
        // @todo: 注销业务代码
    }



}