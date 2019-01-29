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
     * @return Client
     */
    public static function redis()
    {

        $ip = Config::get('redis.redis_conf.ip');
        $port = Config::get('redis.redis_conf.port');
        $password = Config::get('redis.redis_conf.password');



            return new Client(array(
                'scheme'   => 'tcp',
                'host'     => $ip,
                'port'     => $port,
                'database' => 1,
                'password' => $password,
            ));


    }
}