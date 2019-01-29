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
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    protected function  finalCode(String $path, String $key)
    {

		KeyManager::updateTimeStamp($key);

		$request['path'] = $path;

		$courseConfig = KeyManager::getCourseConfig($key);
		$path = $courseConfig["files_on_host"] . $path ."/last"; //requesr->path = /py1/page1

		$files = FileManager::getFiles($path);

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

		if (!ContainerManager::getContainerState($key))
			ContainerManager::startContainer($key);

		KeyManager::updateTimeStamp($key);
    }
}