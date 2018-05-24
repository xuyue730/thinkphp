<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/15 14:40
 *
 * 加密解密
 * 需要 php_openssl扩展
 * 需要 date_default_timezone_set("PRC");
 *
 * @usage
 * $jm = new Password();
 * echo $jm->encrypt("您好");
 * echo $jm->decrypt($jm->encrypt("您好"));
 * echo $jm->encrypt("您好",'base64');
 * echo $jm->decrypt($jm->encrypt("您好",'base64'),'base64');
 * @usage
 * use app\facade\Password;
 * Password::md5("字符");
 */

namespace app\common\tool;

/**
 * 应用类库 密码、加密解密、哈希相关
 * @package app\common\tool
 */
class Password
{
    /**
     * @var string 秘钥 整个网站公用的秘钥，用于各种加密解密或hash盐
     */
    private $key;

    /**
     * @var string 向量 加密解密用 其实不懂
     */
    private $iv = '1234567812345678';

    /**
     * @var string 默认双向加密算法
     */
    private $method = "aes-256-cbc";

    /**
     * @var
     */
    const options = OPENSSL_RAW_DATA;

    public function __construct()
    {
        $this->key = config("password.key")?:"12345678";
    }

    /**
     * aes 加密字符串 默认aes-256-cbc
     * @param $data
     * @param string $type
     * @return string
     */
    function encrypt($data, $type = 'hex')
    {
        $encrypted = openssl_encrypt($data, $this->method, $this->key, self::options, $this->iv);
        if ($type == 'hex') {
            return bin2hex($encrypted);
        }
        if ($type == 'base64') {
            return base64_encode($encrypted);
        }
    }

    /**
     * aes 解密字符串 默认aes-256-cbc
     * @param $data
     * @param string $type
     * @return string
     */
    function decrypt($data, $type = 'hex')
    {
        if ($type == 'hex') {
            $data = hex2bin($data);
        }
        if ($type == 'base64') {
            $data = base64_decode($data);
        }
        $decrypted = openssl_decrypt($data, $this->method, $this->key, self::options, $this->iv);
        return $decrypted;
    }

    /**
     * 用网站秘钥作为盐 生成md5 hash值
     * @deprecated 密码专家建议用sha2代替sha1，因此不建议作为密码加密用途
     * @param $val
     * @param bool $raw
     * @return string
     */
    function md5($val,$raw=false){
        return md5($val.config("password.key")?:"",$raw);
    }

    /**
     *  生成sha1 hash值
     * @deprecated 密码专家建议用sha2代替sha1，因此不建议作为密码加密用途
     * @param $val
     * @param bool $raw
     * @return bool|string
     */
    function sha1($val,$raw=false){
        if(!$val){
            return false;
        }
        return sha1($val,$raw);
    }

    /**
     * 生成hash值
     * @param $algo
     * @param $val
     * @param bool $raw
     * @return string
     */
    function hash($algo,$val,$raw=false){
        return hash($algo,$val,$raw);
    }

    /**
     * 建议使用 生成sha256 hash值
     * @param $val
     * @param bool $raw
     * @return string
     */
    function sha256($val,$raw=false){
        return hash("sha256",$val,$raw);
    }

    /**
     * 创建$password hash值
     * Creates a password hash.
     * @param $password
     * @return bool|string
     */
    function password_hash($password){

        return password_hash($this->sha256($password),PASSWORD_DEFAULT);
    }

    /**
     * 检查$password是否正确匹配$hash值
     * Checks if the given hash matches the given options.
     * @param $password
     * @param $hash
     * @return bool
     */
    function password_verify($password,$hash){
        return password_verify($this->sha256($password),$hash);
    }

}