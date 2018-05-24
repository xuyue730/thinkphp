<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/15 11:51
 */
return [
    // 默认过滤用户提交数据 用逗号分隔多个
    'default_filter'         => 'strip_tags',

    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => Env::get('app.request_cache')?:false,
    // 请求缓存有效期
    'request_cache_expire'   => Env::get('app.request_cache_expire')?:null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [
        // /www/test开头的url都不缓存
        "/www/test/config"
    ],

    // 异常处理
    'exception_handle'       => '\\app\\common\\exception\\Json',
];