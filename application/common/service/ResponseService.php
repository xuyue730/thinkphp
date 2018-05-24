<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/21 17:32
 */

namespace app\common\service;

/**
 * 服务类 响应 http response
 * @package app\common\service
 */
class ResponseService
{
    /**
     * @var array 存储于ResponseService的sql日志
     */
    public static $sql_logs = [];

    /**
     * response json格式的正常返回值
     * @param string $message
     * @param array $info
     * @return \think\response\Json
     */
    public static function json_msg($message = 'ok', $info = [])
    {
        if ($info) {
            $data['data'] = $info;
        }
        $data['message'] = $message;
        $data['code'] = 200;
        if (config("app_debug")) {
            $data['sql'] = self::$sql_logs;
        }
        return json($data, 200);
    }

    /*
     * [
        "200"=>["ok","成功"],

        // 请求错误
        "400"=>["BadRequest","请求参数有误，或者以下任何4xx 4xxxx错误"],
        "401"=>["Unauthorized","请求需要身份验证，或者任何401xx错误"],
        "403"=>["Forbidden","身份验证通过，但无权访问"],
        "404"=>["NotFound","找不到资源"],
        "408"=>["RequestTimeout","请求超时"],
        "421"=>["TooManyConnections","链接过多"],
        "423"=>["Locked","当前资源被锁定"],

        "40101"=>["TokenRequire","token缺失"],
        "40102"=>["TokenInvalid","token无效"],
        "40103"=>["TokenTimeout","token过期"],
        "40104"=>["SignatureInvalid","签名缺失或错误"],

        // 服务器错误
        "500"=>["InternalServerError","服务器端错误，或者任何5xx错误"],
    ]
     */
    /**
     * response json格式的报错
     * @param int $code
     * @param string $message
     * @param array $info
     * @return \think\response\Json
     */
    public static function json_err($code = 400, $message = "err", $info = [])
    {
        if ($code instanceof \Exception) {
            $data = [
                "message" => $code->getMessage(),
                "code" => $code->getCode(),
            ];
            if (config("app_debug")) {
                $data["line"] = $code->getLine();
                $data["file"] = $code->getFile();
            }
        } else {
            $data['code'] = $code;
            $data['message'] = $message;
        }
        if ($info) {
            $data['info'] = $info;
        }
        if (config("app_debug")) {
            $data['sql'] = self::$sql_logs;
        }
        return json($data, 200);
    }
}