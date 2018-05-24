<?php
/**
 * 用户文件上传Service，仅用来提供给前端用户使用，而不是后台管理员使用
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/17 17:25
 */

/*
 *
 * - 上传进度获取条件：
 *   1. ini 开启 session.upload_progress.enabled
 *   2. 需要post multipart/form-data方式上传
 *   3. 页面有一个隐藏input <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="test" />
 *   4. 上传过程中，服务器读取$_SESSION[ini_get("session.upload_progress.prefix") . "test"]

 * - php.ini 配置项 说明
 *  session.upload_progress.enabled	是否启用上传进度报告(默认开启) 1为开启，0为关闭
 *  session.upload_progress.cleanup	是否在上传完成后及时删除进度数据(默认开启, 推荐开启)
 *  session.upload_progress.prefix[=upload_progress_]	进度数据将存储在_SESSION[session.upload_progress.prefix . _POST[session.upload_progress.name]]
 *  session.upload_progress.name[=PHP_SESSION_UPLOAD_PROGRESS]	如果_POST[session.upload_progress.name]没有被设置, 则不会报告进度.
 *  session.upload_progress.freq[=1%]	更新进度的频率(已经处理的字节数), 也支持百分比表示’%’.
 *  session.upload_progress.min_freq[=1.0]	更新进度的时间间隔(秒级)
 *
 *
 *
 */

namespace app\common\service;


use app\common\exception\Cake;
use app\common\model\UserFile;
use think\File;

/**
 * 服务类 用户文件
 * @package app\common\service
 */
class UserFileService extends BaseService
{
    /** @var string 文件保存文件夹，必须放在外网无法访问目录,不要改变 */
    const SAVE_PATH = "..".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
    /** @var string  允许上传的最大尺寸 */
    const MAX_SIZE = 1024*1024*2;
    /** @var string 允许上传的扩展名 */
    const ALLOW_EXT = 'jpeg,jpg,png,gif,xxls,doc,xdoc,xls';

    /**
     * 用户文件上传
     * 应该返回包含id,hash,存储路径文件名的数组
     *
     * @param $file \think\File
     * @param $uid
     * @param $config  array  ["MAX_SIZE","ALLOW_EXT","SAVE_PATH"] SAVE_PATH 是相对index.php的文件夹路径
     * @return array
     * @throws Cake
     */
    public static function upload($file,$uid,$config=[]){
        if(!$uid){
            throw new Cake("缺少参数uid",400);
        }
        $check = [];
        if(isset($config['ALLOW_EXT'])){
            $check['ext'] = $config['ALLOW_EXT'];
        }
        if(isset($config['MAX_SIZE'])){
            $check['size'] = $config['MAX_SIZE']?:static::MAX_SIZE;
        }
        $file->validate($check);
        $info = $file->move( isset($config['SAVE_PATH'])?:static::SAVE_PATH);
        if(!$info){
            throw new Cake($file->getError(),400);
        }
        $model = new UserFile();

        $model->uid =$uid;
        $model->path = $info->getSaveName();
        $model->hash = $info->hash('md5');
        $model->mime = $info->getMime();
        $model->save();
        return $model->toArray();
    }

    /**
     * base64格式图片上传
     * @todo 检查文件合法性
     *
     * @param $base64
     * @param $uid
     * @return array
     * @throws Cake
     */
    public static function base64Upload($base64,$uid){

        $fileName = date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
        $filePath = self::SAVE_PATH.$fileName;
        if(!file_exists( self::SAVE_PATH.date('Ymd'))){
            if (!mkdir(self::SAVE_PATH.date('Ymd'), 0755, true)) {
                throw new Cake("创建文件失败",500);
            }
        }
        file_put_contents($filePath,base64_decode($base64));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime =  finfo_file($finfo, $filePath);
        $model = new UserFile();
        $model->uid =$uid;
        $model->path = $fileName;
        $model->hash = hash_file("md5", $filePath);
        $model->mime = $mime;
        $model->save();
        return $model->toArray();
    }

    /**
     * 获取上传进度
     * @todo 多服务器环境需要redis session方案
     * @param $key
     * @return float|int
     */
    public static function progress($key){
        $key = ini_get("session.upload_progress.prefix") . $key;
        //判断 SESSION 中是否有上传文件的信息
        if (!empty($_SESSION[$key])) {
            //已上传大小
            $current = $_SESSION[$key]["bytes_processed"];
            //文件总大小
            $total = $_SESSION[$key]["content_length"];
            //向 ajax 返回当前的上传进度百分比。
            return $current < $total ? ceil($current / $total * 100) : 100;
        }else{
            return 100;
        }
    }

    /**
     * 读取文件信息
     * @param $id
     * @param int $uid
     * @return array
     * @throws Cake
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function fileInfo($id,$uid){
        $where = null;
        if($uid){
            $where["uid"] = ["=",$uid];
        }else{
            throw new Cake("用户未登录",400);
        }
        $fileInfo = UserFile::where($where)->find($id);
        if(!$fileInfo){
            throw new Cake("数据库找不到这个文件",400);
        }
        return $fileInfo->toArray();
    }

    /**
     * 读取文件内容
     * @param $path
     * @return bool|string
     * @throws Cake
     */
    public static function fileContent($path){
        $path = static::SAVE_PATH.DIRECTORY_SEPARATOR.$path;
        if(!file_exists($path)){
            throw new Cake("目录找不到这个文件",400);
        }
        return file_get_contents($path);
    }

    /**
     * 输出图片
     * @param $id
     * @param $uid
     * @param $maxAge int
     * @return \think\Response
     * @throws Cake
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function output($id,$uid,$maxAge=86400){
        $info =  static::fileInfo($id,$uid);
        $content = static::fileContent($info['path']);
        return response()->content($content)
            ->cacheControl("private,max-age=".($maxAge))
            ->expires(gmdate("D, d M Y H:i:s", time() + $maxAge) . "  GMT")
            ->contentType($info['mime']);
    }

}