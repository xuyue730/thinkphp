<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/17 14:24
 */

namespace app\api\controller;


use app\common\exception\Cake;

/**
 * 演示 控制器
 * @package app\api\controller
 */
class Index extends Base
{
    /**
     * @throws Cake
     */
    public function config(){
        throw  new Cake("test",400);
        dump(config());
    }
}