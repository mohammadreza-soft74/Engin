<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/21/18
 * Time: 3:27 PM
 */

namespace App\Clasess\Base\Managers\ContainerManager;

use App\Clasess\Base\Managers\FileManager\FileManager;
use Config;
use Docker\Docker;
use Docker\DockerClientFactory;
use Docker\API\Model\HostConfig;
use Docker\API\Model\RestartPolicy;
use Docker\API\Model\ContainersCreatePostBody;
use Docker\Stream\AttachWebsocketStream;
use Docker\API\Model\ContainersIdExecPostBody;
use Docker\API\Model\ExecIdStartPostBody;


class ContainerManager
{

    /**
     * @brief create docker instance.
     *
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
     * @brief make default setting.
     *
     * @detail make a default setting to set on container.
     *
     * @param ContainersCreatePostBody $containerConfig
     * @param $courseConfig
     * @throws \Exception
     */
    public static function setDefaultContainerConfig(ContainersCreatePostBody $containerConfig , $courseConfig, $key)
    {
		// Read default container configs and set them.
		$defaultContainerConfig = Config::get("docker.container_config");

		//set default config from config file.
		foreach ( $defaultContainerConfig as $function => $arg )
		{
			$containerConfig->{$function}($arg);

		}

		$hostConfig = new HostConfig();
		//
		// define restart policy to restart container on failure.
		//
		$restartPolicy = new RestartPolicy();
		$restartPolicy->setName("on-failure");
		$restartPolicy->setMaximumRetryCount(5);
		$hostConfig->setRestartPolicy($restartPolicy);
		//todo: set swap . its must be set
		$hostConfig->setMemorySwap(61457280);
		$hostConfig->setMemory(61457280);
		$hostConfig->setKernelMemory(76700160);


		$container_default_files = $courseConfig['container_default_files']; //language default files on server.
		$container_shared_files = $courseConfig['container_shared_files'].$key; // container files directory.
		$ContainerFiles = $courseConfig['ContainerFiles'];//bind directory on container to directory on server.
		//
		//copy default files to binded directory.
		//
		FileManager::recurse_copy($container_default_files, $container_shared_files);
		$hostConfig->setBinds(["$container_shared_files:$ContainerFiles"]);

		$containerConfig->setHostConfig($hostConfig);

    }



    /**
     * @brief creating container.
     *
     * @param ContainersCreatePostBody $containerConfig
     * @param $key
     * @return \Docker\API\Model\ContainersCreatePostResponse201|null|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public static function createContainer(ContainersCreatePostBody $containerConfig, $key )
    {

        try
        {
            $docker = self::makeDockerInstance();
            $creation = $docker->containerCreate($containerConfig,["name" => $key]);
        }
        catch ( \Exception $e )
        {
            throw new \Exception("Error: Couldn't create the container!\n".$e->getMessage().$e->getFile());
        }

        return $creation;
    }

    /**
     * @brief start container.
     *
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
	 *
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
     *
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
     * @brief write to container websocket.
     *
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
     * @brief read from container web socket.
     *
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

    /**
     * @brief get state of container.
     *
     * @detail get container state if its running state=1 else state=0
     * @param $containerId
     * @return bool|null
     * @throws \Exception
     */
    public static function getContainerState($key)
    {

        try {

            $docker = self::makeDockerInstance();
            $inspection = $docker->containerInspect($key);

            if ($inspection instanceof \Psr\Http\Message\ResponseInterface)
                throw new \Exception('Error : The container may not exist !');

            $result = $docker->containerInspect($key)->getState()->getRunning();

            return $result;

        }catch (\Exception $e){
           throw  $e;// throw new \Exception("Error: getContainerState() error\n".$e->getMessage()."\n".$e->getFile());
        }
    }

    /**
     * @brief execute given command in running container bash.
     *
     * @param $containerId
     * @param $command
     * @param string $workingDir
     * @throws \Exception
     */
    public static function dockerExecStart($key, $command, $workingDir='/home/violin')
    {


        try {

            $docker = self::makeDockerInstance();

            // SOURCE : https://github.com/docker-php/docker-php/pull/320/files?utf8=%E2%9C%93&diff=unified
            //this snippet of code execute our command on running container
            $execConfig = new ContainersIdExecPostBody();
            $execConfig->setTty(true);
            $execConfig->setAttachStdout(true);
            $execConfig->setAttachStderr(true);
            $execConfig->setCmd(["/bin/bash", "-c", $command]);
            $execConfig->setWorkingDir($workingDir);
            $execid = $docker->containerExec($key, $execConfig)->getId();
            $execStartConfig = new ExecIdStartPostBody();
            //$execStartConfig->setDetach(false);
            // Execute the command
            $docker->execStart($execid, $execStartConfig);

        }catch (\Exception $e){
            throw new \Exception("Error: ExecStart unSuccessful! \n".$e->getMessage()."\n".$e->getFile());
        }

    }

    /**
     * @brief copying file from host to container in *.tar format.
     *
     * @param $containerId
     * @param $tarString
     * @param $pathOnContainer
     * @throws \Exception
     */
    public static function copyFileToSpecifiedPathInContainer($containerId, $tarString, $pathOnContainer)
    {
        try {
            $docker = self::makeDockerInstance();
            $docker->putContainerArchive($containerId, $tarString, ["path" => $pathOnContainer]);
        }
        catch (\Exception $e){
            throw new \Exception("Error: an error occurred while copping files to containers\n".$e->getMessage()."\n".$e->getFile());
        }
    }


    /**
     * @brief create an exec instance
     *
     * @param $key
     * @param $command
     * @param string $workingDir
     * @return null|string
     * @throws \Exception
     */
    public static function exec($key, $command, $workingDir = "/home/violin")
    {

		try {

			$docker = self::makeDockerInstance();

			// SOURCE : https://github.com/docker-php/docker-php/pull/320/files?utf8=%E2%9C%93&diff=unified
			//this snippet of code execute our command on running container
			$execConfig = new ContainersIdExecPostBody();
			$execConfig->setTty(true);
			$execConfig->setAttachStdout(true);
			$execConfig->setAttachStdin(true);
			$execConfig->setAttachStderr(true);
			$execConfig->setCmd(["/bin/bash", "-c", $command]);
			$execConfig->setWorkingDir($workingDir);
			$execId = $docker->containerExec($key, $execConfig)->getId();

			return $execId;

		}catch (\Exception $e){
			throw new \Exception("Error: Exec unSuccessful! \n".$e->getMessage()."\n".$e->getFile());
		}
    }
    
    


}