<?php
/**
 * Description:redis单例模式
 * User: sxr
 * Date: 2019年2月13日11:15:53
 * QQ:1320833575
 */

class RedisFactory
{
    /**redis句柄
     * @var null
     */
    private static $handler = null;

    /**redis默认配置
     * @var array
     */
    private static $config = [
        'REDIS_HOST' => 'localhost',
        'REDIS_PORT' => '6379',
        'REDIS_AUTH' => '',
        'REDIS_OUT' => '600',
        'REDIS_PREFIX' => '',
        'REDIS_SELECT' => '0'
    ];


    /**私有构造方法，防止外部直接实例化
     * RedisFactory constructor.
     */
    private  function __construct($config)
    {
        if (!empty($config)) {
            self::$config = array_merge(self::$config, $config);
        }
        try {
            self::$handler = new \Redis();
            self::$handler->connect(self::$config['REDIS_HOST'], self::$config['REDIS_PORT'],self::$config['REDIS_OUT']);
            if (self::$config['REDIS_AUTH']) {
                self::$handler->auth(self::$config['REDIS_AUTH']);
            }

            if (self::$config['REDIS_SELECT']) {
                self::$handler->select(self::$config['REDIS_SELECT']);
            }
        } catch (\Exception $e) {
            throw new \Exception('Redis：'.$e->getMessage());
        }
    }

    /**
     * Description:私有化克隆函数，防止外界克隆对象
     */
    private function __clone()
    {

    }

    /**
     * Description:静态方法，单例访问统一入口
     * @param array $config 配置
     * @return null|\redis返回应用中的唯一对象实例
     */
    public static function getRedisHandler($config = [])
    {
        if(!self::$handler){
            new self($config) ;
        }
        return self::$handler ;
    }


}