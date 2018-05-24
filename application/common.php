<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * 应用公共文件
 */

use think\Facade;

// 定义全局错误级别
error_reporting(E_ALL);

// 全局绑定facade
Facade::bind([
    'app\facade\Password' => 'app\common\tool\Password',
]);


/**
 * 返回一个相对路径的临时文件名
 * @param string $path
 * @return string
 */
function temp_file_path($path = ".." . DIRECTORY_SEPARATOR . "runtime")
{
    return $path . DIRECTORY_SEPARATOR . m_time() . "_" . rand(1, 99999);
}

/**
 * 生成毫秒时间戳
 *
 * @return float
 */
function m_time()
{
    list($msec, $sec) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}

/**
 * 生成随机字符串
 *
 * @param int $length
 * @return string
 */
function str_random($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}
