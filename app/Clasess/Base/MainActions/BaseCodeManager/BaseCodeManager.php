<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:47 PM
 */

namespace App\Clasess\Base\MainActions\BaseCodeManager;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\FileManager\FileManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;


class BaseCodeManager
{
    /**
     * @brief return final code from host.
	 *
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    protected function  finalCode(String $path, String $key)
    {

		KeyManager::updateTimeStamp($key); // update time stamp to stop container on time

		$request['path'] = $path;

		$courseConfig = KeyManager::getCourseConfig($key); // get language config from config file
		$path = $courseConfig["files_on_host"] . $path ."/last"; //requesr->path = /py1/page1

		$files = FileManager::getFiles($path); // get files in json format from given path

		$result =  [
			'error' => false,
			'result'=> $files

		];
		return $result;

    }

    /**
     * reset codes in specified path
     * replace Default codes with user code in container
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    protected function resetCode(String $path, String $key)
    {

		if (!ContainerManager::getContainerState($key)) //get container state(stop/runnig)
			ContainerManager::startContainer($key);

		KeyManager::updateTimeStamp($key); //update time stamp to stop container on time
    }
}