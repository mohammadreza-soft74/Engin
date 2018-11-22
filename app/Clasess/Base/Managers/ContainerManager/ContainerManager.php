<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:27 PM
 */

namespace App\Clasess\Base\Managers\ContainerManager;

use Config;
use Docker\Docker;
use Docker\DockerClientFactory;
use Docker\API\Model\PortBinding;
use Docker\API\Model\HostConfig;
use Docker\API\Model\RestartPolicy;
use Docker\API\Model\ContainersCreatePostBody;
use Docker\Stream\AttachWebsocketStream;


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

    /**
     * @brief make default setting
     * @detail make a default setting to set on container
     * @param ContainersCreatePostBody $containerConfig
     * @param $courseConfig
     */
    public static function setDefaultContainerConfig(ContainersCreatePostBody $containerConfig , $courseConfig)
    {
        // Read default container configs and set them
        $defaultContainerConfig = Config::get("docker.container_config");

        foreach ( $defaultContainerConfig as $function => $arg )
        {
            $containerConfig->{$function}($arg);

        }

        $portMap = new \ArrayObject();  //store mapped port in array object

        //if file watcher (/config/file.php) was true port 7682 mapped to a port on host
        if ($courseConfig["file_watcher"]){

            $fwPort = (new ContainerHelper())->setPort();
            $fwportBinding = new PortBinding();
            $fwportBinding->setHostPort($fwPort);
            $fwportBinding->setHostIp('0.0.0.0');
            $portMap['7682/tcp']=[$fwportBinding];

        }

        /**
         * if language is web based language expose port 80 on container
         * if language is not web based language expose port 7681 to xterm
         * */
        switch ($courseConfig["type"])
        {
            case ("local"):    //local app (normal app)

                $xtermPort = (new ContainerHelper())->setPort();
                $xtermPortBinding = new PortBinding();
                $xtermPortBinding->setHostPort($xtermPort);
                $xtermPortBinding->setHostIp('0.0.0.0');
                $portMap['7681/tcp'] = [$xtermPortBinding];

                break;


            case ("web"):   //web based app

                $WebPort = (new ContainerHelper())->setPort();
                $webPortBinding = new PortBinding();
                $webPortBinding->setHostPort($WebPort);
                $webPortBinding->setHostIp('0.0.0.0');
                $portMap['80/tcp'] = [$webPortBinding];

                break;
        }


        $hostConfig = new HostConfig();
        $hostConfig->setPortBindings($portMap);

        $restartPolicy = new RestartPolicy();
        $restartPolicy->setName("on-failure");
        $restartPolicy->setMaximumRetryCount(5);
        $hostConfig->setRestartPolicy($restartPolicy);
        //todo: set swap . its must be set
        //$hostConfig->setMemorySwap(30);
        $hostConfig->setMemory(61457280);
        $hostConfig->setKernelMemory(76700160);

        $containerConfig->setHostConfig($hostConfig);

    }

    /**
     * @brief creating container.
     * 
     * @uses self::makeDockerInstance 
     * @uses containerCreate
     * @param ContainersCreatePostBody $containerConfig
     * @param $key
     * @return \Docker\API\Model\ContainersCreatePostResponse201|null|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public static function createContainer(ContainersCreatePostBody $containerConfig, $key )
    {
        // Create ContainerHelper
        try
        {
            $docker = self::makeDockerInstance();
            $creation = $docker->containerCreate($containerConfig,["name" => $key]);
        }
        catch ( \Exception $e )
        {
            throw new \Exception("Error: Couldn't create the container!\n".$e->getMessage().$e->getFile());
        }

        /*
         * Check if it was successful.
         * create method of containerManager returns one of these two cases :
         *      failed  : \Psr\Http\Message\ResponseInterface
         *      succeed : \Docker\API\Model\ContainerCreateResult
         */
        if ( $creation instanceof \Psr\Http\Message\ResponseInterface )
        {
            throw new \Exception("Error: Couldn't create the container!\n{$creation->getBody()}");
        }

        // If the creation was successful, we have an instance of the ContainerCreateResult class
        return $creation;
    }

    /**
     * @brief start container.
     * @param $containerId
     * @throws \Exception
     */
    public static function startContainer($containerId)
    {
        try {
            $docker = self::makeDockerInstance();
            $docker->containerStart($containerId);
        } catch (\Exception $e) {
            throw new \Exception("Error: Couldn't start the container!\n" . $e->getMessage() . "\n" . $e->getFile());
        }
    }

    /**
     * @brief stop running container.
     * @uses self::makeDockerInstance
     * @uses containerStop
     * @param $containerId
     * @throws \Exception
     */
    public static function stopContainer($containerId)
    {
        try
        {
            $docker = self::makeDockerInstance();
            $docker->containerStop($containerId);

        }catch (\Exception $e){

            throw new \Exception("Error: couldn,t stop container! \n".$e->getMessage()."\n".$e->getFile());
        }
    }

    /**
     * @brief attach to web socket of container.
     * @uses containerAttachWebsocket
     * @uses self::makeDockerInstance
     * @param $containerId
     * @return null|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public static function containerAttachWebSocket($containerId)
    {
        $docker = self::makeDockerInstance();
        try {

            $webSocketStream = $docker->containerAttachWebsocket($containerId, [
                'stream' => true,
                'stdin' => true,
                'stdout' => true,
                'stderr' => true
            ]);

        }catch (\Exception $e){
            throw new \Exception("Error: Couldn't attach the websocket!\n".$e->getMessage()."\n".$e->getFile());
        }

        if ( $webSocketStream instanceof \Psr\Http\Message\ResponseInterface )
        {
            throw new \Exception("Error: Couldn't attach the websocket!\n{$webSocketStream->getBody()}");
        }

        return $webSocketStream;
    }

    /**
     * @brief write to container websocket
     * @param AttachWebsocketStream $webSocketStream
     * @param $data
     * @throws \Exception
     */
    public static function writeToWebSocket(AttachWebsocketStream $webSocketStream, $data)
    {
        try
        {
            $webSocketStream->write($data);
        }
        catch (\Exception $e)
        {
            throw new \Exception("Error: Couldn't write data to the websocket!\n".$e->getMessage()."\n".$e->getFile());
        }
    }

    /**
     * @brief read from container web socket
     * @param AttachWebsocketStream $webSocketStream
     * @param int $wait
     * @return array|false|null|string
     * @throws \Exception
     */
    public static  function readFromWebSocket(AttachWebsocketStream $webSocketStream,$wait=5)
    {
        try
        {
            $response = $webSocketStream->read($wait);
        }
        catch (\Exception $e)
        {
            throw new \Exception("Error: Couldn't read data from websocket!\n".$e->getMessage()."\n".$e->getFile());
        }

        return $response;
    }


}