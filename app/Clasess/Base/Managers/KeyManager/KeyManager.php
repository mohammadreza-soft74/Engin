<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:09 PM
 */

namespace App\Clasess\Base\Managers\KeyManager;

use App\Clasess\Base\Memory\RedisClientFactory;


class KeyManager
{
    /**
     * @brief check container existence.
     *
     * @detail get user key and check container existence , if its available returns containerId.
     * @fn static checkContainerIdWithKey
     * @see KeyManager:
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public static function checkContainerIdWithKey($key)
    {
        //if containerId is available or exist it return container id else return false to create and start corresponding container
        $containerId = self::searchContainerIdInMemory($key);

        if ($containerId)
            return $containerId;
        else
            return false;

    }

    /**
     * @brief search for containerId in memory
     * @detail if container id is available returns container id else returns false
     * @param string $key
     * @return bool
     * @throws \Exception
     */
    private static function searchContainerIdInMemory($key)
    {

        $courseConfig = self::getCourseConfig($key);
        try {

            $redis = RedisClientFactory::redis("key");
            $containerId = $redis->hget($courseConfig["keysCacheName"].":".$key,"id");
            $redis->disconnect();

            if ($containerId)
                return $containerId;

            return false;

        }
        catch (\Exception $e)
        {
            throw new \Exception("Error: There was a problem searching id in the database ! . \n" .$e->getMessage()."\n".$e->getFile());
        }

    }

    /**
     * @brief update user action time on redis.
     * @detail update user main actions time on redis, in each main actions such as page load or run this time update in redis to help stop user container.
     * @param $key
     * @throws \Exception
     */
    public static function updateTimeStamp($key)
    {
        $courseConfig = self::getCourseConfig($key);
        try {

            // for store key an container id in Redis
            $redis = RedisClientFactory::redis("key");

            $redis->hset($courseConfig["keysCacheName"].":".$key,"timeStamp",time());

            $redis->disconnect();


        }catch (\Exception $e){

            throw new \Exception("Error: There was a problem getting timeStamp from database ! . \n" .$e->getMessage()."\n".$e->getFile());
        }
    }




}