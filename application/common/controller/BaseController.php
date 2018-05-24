<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/14 16:31
 */

namespace app\common\controller;


use app\common\service\ResponseService;
use think\Controller;
use think\Db;

/**
 * 所有模块 控制器基类的基类
 * @package app\common\controller
 */
class BaseController extends Controller
{

    /**
     * 控制器前置执行
     * 放在基类，那么所有继承控制器都会运行
     */
    public function initialize()
    {
        parent::initialize();
        if (config("app.app_debug")) {
            Db::listen(function ($sql, $time, $explain) {
                ResponseService::$sql_logs[] = $sql . ' [' . $time . 's]';
//            dump($explain);
            });
        }
    }

    /**
     * response json类型的正常值
     * @param string $message
     * @param array $info
     * @return \think\response\Json
     */
    protected function msg($message = 'ok', $info = [])
    {
        return ResponseService::json_msg($message, $info);
    }


    /**
     * response json类型的报错
     * @param int $code
     * @param string $message
     * @param array $info
     * @return \think\response\Json
     */
    protected function err($code = 400, $message = "err", $info = [])
    {
        return ResponseService::json_err($code, $message, $info);
    }
}