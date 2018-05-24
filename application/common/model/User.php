<?php
/**
 *
 * User: dylan<2140509722@qq.com>
 * Date: 2018/5/14 13:15
 */

namespace app\common\model;


/**
 * 前端用户模型 user表
 * @package app\common\model
 */
class User extends Base
{
    /** @var int 正常状态 */
    const STATUS_NORMAL = 1;

    /** @var int 已删除状态 */
    const STATUS_DELETED = 0;

    /**
     * @var string 自动时间戳字段为datetime类型
     */
    protected $autoWriteTimestamp = 'datetime';


}