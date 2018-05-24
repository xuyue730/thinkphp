# 坑
- .env的app_debug 不影响 config
- 请求缓存配置在www/config/app.php
.env 设置无缓存
```
[APP]
    request_cache=  false | __URL__
    request_cache_expire=30
 
```
www/config/app.php 设置__URL__缓存
```
'request_cache'          => Env::get('app.request_cache',false),
'request_cache_expire'   => Env::get('app.request_cache_expire',null),
'request_cache_except' => [
    '/www/test/config', //以 控制器/方法 开头的url
],
```
- 

# tip
- service 层与uid无关，因此uid获取是在controller层
- 一个资源php，一般不会api端和web端控制器共享，因此没必要在一个文件里做两套认证
- 检查token会抛出异常，但是获取uid判断用户是否登录不需要抛出异常，check_status get_uid 这一般是两种客户端情况，如果只是web，检查uid即可，app端才需要


- 金融项目如果栏目多，内容少不需要静态页
