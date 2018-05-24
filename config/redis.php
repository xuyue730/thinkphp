<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/14 16:11
 */

use think\facade\Env;

return [
    'host'       => Env::get('redis.host','localhost'),
    'port'       => Env::get('redis.port',6379),
    'password'   => Env::get('redis.password',''),
];