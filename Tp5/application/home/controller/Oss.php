<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/3
 * Time: 22:04
 */

namespace app\home\controller;
use OSS\OssClient;
class Oss {
    new OSS\OssClient;
    const accessKeyId = \oss_config::OSS_ACCESS_ID;
    const accessKeySecret = \oss_config::OSS_ACCESS_KEY;
    const endpoint = \oss_config::OSS_ENDPOINT;
    const bucket = \oss_config::OSS_BUCKET;
    private static $_instance;
    /**
     * 构造函数
     * Oss constructor.
     */
    private function __construct() {
    }
    /**
     * 克隆
     */
    private function __clone() {
    }
    /**
     * 获取一个OssClient实例
     * @return null|OssClient
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof OssClient)) {
            try {
                self::$_instance = new OssClient(self::accessKeyId, self::accessKeySecret, self::endpoint, false);
            } catch (OssException $e) {
                printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
                printf($e->getMessage() . "\n");
                return null;
            }
        }
        return self::$_instance;
    }
    /**
     * 获取bucket
     * @return string
     */
    public static function getBucketName()
    {
        return self::bucket;
    }
}