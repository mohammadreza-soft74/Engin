<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 10:59 AM
 */

namespace App\Clasess\Base\Memory;


class RedisClientFactory
{
    /**
     * @brief make an instance of Redis client
     * @param $type
     * @return Client
     */
    public static function redis($type)
    {


        switch ($type)
        {

            case ("key"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => '127.0.0.1',
                'port'     => 6379,
                'database' => 1
            ));
                break;

            case ("path"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => '127.0.0.1',
                'port'     => 6379,
                'database' => 2
            ));

            case ("config"): return new Client(array(
                'scheme'   => 'tcp',
                'host'     => '127.0.0.1',
                'port'     => 6379,
                'database' => 3
            ));
        }


    }

}