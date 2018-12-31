<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 10:59 AM
 */

namespace App\Clasess\Base\Memory;

use Predis\Client;
use Config;

class RedisClientFactory
{
    /**
     * @brief make an instance of Redis client.
     *
     * @param $type
     * @return Client
     */
    public static function redis($type)
    {

        $ip = Config::get('redis.redis_conf.ip');
        $port = Config::get('redis.redis_conf.port');
        $password = Config::get('redis.redis_conf.password');

        switch ($type)
        {

            case ("key"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => $ip,
                'port'     => $port,
                'database' => 1,
                'password' => $password,
            ));
                break;

            case ("path"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => $ip,
                'port'     => $port,
                'database' => 2,
                'password' => $password,
            ));

            case ("config"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => $ip,
                'port'     => $port,
                'database' => 3,
                'password' => $password,
            ));
        }


    }

}