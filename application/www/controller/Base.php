<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/14 10:46
 */

namespace app\www\controller;


use app\common\controller\BaseController;
use app\common\exception\Cake;
use app\common\service\UserService;

/**
 * 控制器类 www模块基类
 * @package app\www\controller
 */
class Base extends BaseController
{
    /**
     * @var int 存储于基类控制器的 uid
     */
    public $uid;

    /**
     * 获取用户uid，如未登录返回null
     * 想获取userInfo 用session获取
     * @return null
     */
    public function get_uid()
    {
        if (!$user = UserService::doSessionCheck()) {
            return null;
        }
        return $user['uid'];
    }

    /**
     * 检查用户登录状态，如未登录则抛出异常
     * 设置$this->uid即可绕过身份验证
     *
     * @throws Cake
     */
    public function authentication()
    {
        if (!$this->uid) {
            if (!$user = UserService::doSessionCheck()) {
                throw new Cake("需要登录", 400);
            }
            $this->uid = $user['uid'];
        }
    }
}