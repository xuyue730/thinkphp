<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/15 12:53
 */

namespace app\www\widget;


use think\Controller;

/**
 * 碎片类 演示
 * @package app\www\widget
 */
class Index extends Controller
{
    /**
     * 演示 某个碎片
     * @param $data
     * @return mixed
     */
    public function test($data){
//        return "字符串型";
        // 渲染模板后返回字符
        $widget = $this->fetch("widget/index_test",["data"=>$data]);
        return $widget;
    }
}