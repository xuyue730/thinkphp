<?php
/**
 * demo controller
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/14 10:45
 */

namespace app\www\controller;

use app\common\service\UserFileService;
use app\common\service\UserService;

/**
 * 控制器类 演示
 */
class Index extends Base
{

    /**
     * 演示 首页
     * @param string $name
     * @return string
     */
    public function index($name = "tp5.1"){
        session("uid",26);
        session("usn","2");
        echo session("uid");
        dump(config("app_debug"));
        return 'hello，,' . $name;
    }

}