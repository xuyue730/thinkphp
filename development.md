## 推荐环境
- centos7.x
- php7.2.x,mysql5.7.x,thinkphp5.1.x,redis4.0.x,nginx最新长期稳定版并支持http2
- mysql启用二进制日志、不要用root账户，每个网站独立用户。
- docker部署
- php扩展:
    - php-fileinfo
    - php-openssl
    - php-mbstring
## 框架

#### 系统
- [已有] 写系统日志和业务日志到文件，便于日志收集和分析
- [未完] 保留redis session支持，便于以后扩展，如果能用加密cookie替代session更好
- [已有] 启用.env 配置文件，并在.gitignore文件中忽略.env 。 
	
#### 安全
- [已有] pdo-mysql连接mysql，防注入
- [已有] 过滤或转义用户提交的<script>单引号双引号<style>等，防跨站
- [已有] 登录密码用加盐的哈希值存储，为了更安全可以先hash再password_hash
- [已有] 身份证号等敏感信息，如果业务需求只是验证身份证号而不是显示身份证号，也要用加盐的哈希算法加密存储，否则用双向加密存储。
- [已有] 身份证、营业执照等敏感信息图片，其路径不允许直接通过互联网访问

## 规范
- 自定义规范 ssh://git@192.168.1.101:7999/development/standard.git
- 注释规范: phpDocument 
    - https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md
    - https://www.cnblogs.com/hellohell/p/5733712.html
- php规范: psr-2
    - https://www.kancloud.cn/thinkphp/php-fig-psr/3141
       
## git
- 潞盈金融项目: https://gitee.com/food_group/finance

## 生成文档
```
php phpDocumentor.phar run -d "application/" -d "extend/" -t document/
```
## 生成静态页
```php
protected function buildHtml($htmlfile = '', $htmlpath = '', $templateFile = '')
{
    $content = $this->fetch($templateFile);
    $htmlpath = !empty($htmlpath) ? $htmlpath : './appTemplate/';
    $htmlfile = $htmlpath . $htmlfile . '.'.config('url_html_suffix');
    //$File = new \think\template\driver\File();
    //$File->write($htmlfile, $content);
    file_put_content($htmlfile,$content);
    return $content;
}
```