<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:27 PM
 */

namespace App\Clasess\Base\Managers\ContainerManager;


class ContainerManager
{

    /**
     * @brief create docker instance.
     * @detail create instance of docker object with our config.
     * @return Docker
     */
    public static function makeDockerInstance()
    {
        //Get Docker Connection Config
        $remoteSocket = Config::get('docker.connection.remote_socket');
        $ssl = Config::get('docker.connection.ssl');

        // Connect to Docker
        $client = DockerClientFactory::create([
            'remote_socket' => $remoteSocket,
            'ssl' => $ssl
        ]);
        $docker = Docker::create($client);

        return $docker;
    }


}