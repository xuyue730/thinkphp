<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/17 12:56
 */

namespace app\common\service;


use app\common\exception\Cake;
use app\common\model\User;
use app\facade\Password;

/**
 * 服务类 用户 提供前端用户相关的底层操作
 *
 * @package app\common\service
 */
class UserService extends BaseService
{

    /** var int 身份认证方式为token */
    const AUTHENTICATION_TOKEN = 1;
    /** var int 身份认证方式为cookie */
    const AUTHENTICATION_COOKIE = 2;
    /** var int 身份认证方式为session */
    const AUTHENTICATION_SESSION =3;

    /**
     * 检查token 返回uid
     * @todo 性能测试 1000次解析需要多久
     * @param $token
     * @return mixed
     * @throws Cake
     */
    public static function checkToken($token){
        if(!$token = Password::decrypt($token)){
            throw new Cake("token无效",40102);
        }
        if(!$json = json_decode($token,true)){
            throw new Cake("token无效",40102);
        }
        if($json['t0']+$json['du']<time()){
            throw new Cake("token过期",40103);
        }
        if($json['v'] != config("api.version")?:""){
            throw new Cake("版本升级,token过期",40103);
        }

        return $json;
    }

    /**
     * 创建uid对应的token
     * @todo 性能测试, 1000次创建需要多久
     * @param $uid
     * @param float|int $timeout 多久过期 单位秒
     * @return mixed
     */
    public static function createToken($uid,$timeout=3600*24){
        $token = [
            "uid"=>$uid,
            "du" => $timeout, // duration
            "t0" => time(), // create_time
            "v"  => config("api.version")?:""
        ];
        return Password::encrypt(json_encode($token));
    }

    /**
     * 检查登录 用户名密码是否正确
     * @param $param
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws Cake
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function checkLogin($param){
        if(!isset($param['username']) || !isset($param['password']) || !$param['username'] || !$param['password']){
            throw new Cake("缺少参数",400);
        }
        $user = db("user")->where(function ($query) use($param){
                $query->where('username',"=",$param['username'])->whereOr('email',"=" ,$param['username'])->whereOr("mobile","=",$param['username']);
            })
            ->find();
        if(!$user){
            throw new Cake("找不到用户",400);
        }
        if($user['status'] == 0){
            throw new Cake("用户已被禁用",400);
        }

        if(!Password::password_verify($param['password'],$user['password'])){
            throw new Cake("密码错误",400);
        }
        return $user;
    }

    /**
     * token方式 检查登录状态，返回带uid的数组
     * @return mixed
     * @throws Cake
     */
    public static function doTokenCheck(){
        $token = request()->header(config("cookie.prefix")."token");
        return static::checkToken($token);
    }

    /**
     * cookie方式 检查登录状态，返回带uid的数组
     * @return mixed
     * @throws Cake
     */
    public static function doCookieCheck(){
        $token = cookie("token");
        return static::checkToken($token);
    }

    /**
     * cookie方式 写入登录状态
     * @param $user
     * @param int $expire
     */
    public static function doCookieLogin($user,$expire=3600){
        $token = static::createToken($user['id']);
        cookie("token",$token,["httponly"=>true,"expire"=>$expire]);
    }

    /**
     * cookie方式 登出
     */
    public static function doCookieLogout(){
        cookie("token",null);
    }

    /**
     * session方式 检查登录状态，返回带uid的数组
     * @return bool|mixed
     */
    public static function doSessionCheck(){
        if(session("uid")){
            return ["uid"=>session("uid"),"usn"=>session('usn')];
        }
        return false;
    }

    /**
     * session方式 写入登录状态
     * @param $user
     */
    public static function doSessionLogin($user){
        session("uid",$user['id']);
        session("usn",$user['sn']);
    }

    /**
     * session方式 登出
     */
    public static function doSessionLogout(){
        session("uid",null);
        session("usn",null);
        session(null);
    }


    /**
     * 把uid转换为sn 参看user表
     * @param $uid
     * @param $create_time
     * @return string
     */
    public static function createSn($uid,$create_time){
        return md5($uid."_".$create_time);
    }

    /**
     * 创建用户，依赖数据库的索引防止重复数据
     * 使用时，请把代码放入事物中执行
     * @param $user
     * @return User
     */
    public static function create($user){
        $model = new User();
        $now  =time();
        echo $now;
        $model->password = Password::password_hash(isset($user['password'])?$user['password']:"123456");
        $model->username = isset($user['username'])?$user['username']:"";
        $model->email = isset($user['email'])?$user['email']:"";
        $model->mobile = isset($user['mobile'])?$user['mobile']:"";
        $model->save();
        $model->token = static::createToken($model->id);
        $model->sn = static::createSn($model->id,$model->create_time);
        $model->save();
        return $model;
    }

}