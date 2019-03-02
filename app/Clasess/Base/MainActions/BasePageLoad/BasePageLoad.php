<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:35 PM
 */

namespace App\Clasess\Base\MainActions\BasePageLoad;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;


class BasePageLoad
{
	/**
	 * @brief on moodle page load this function just check container state
	 *
	 * @param $request
	 * @throws \Exception
	 */
    protected function PageLoad($request)
    {

		$key = $request['key'];
		$path = $request["path"];

		$courseConfig = KeyManager::getCourseConfig($key); //get language course config from config file

		//check existence of the given path on server . if its not valid throw an Exception
		$path = $courseConfig["container_default_files"] . $path;
		if (!is_dir($path))
			throw  new \Exception("Error: directory ($path) is not available !\nmay be this is not valid path!");

		if (!ContainerManager::getContainerState($key)) //check container state(stop/running)
			ContainerManager::startContainer($key);

		KeyManager::updateTimeStamp($key); //update time stamp to stop container on time

    }
}