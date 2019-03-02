<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/22/18
 * Time: 12:38 PM
 */

namespace App\Clasess\Base\MainActions\BaseRun;

use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\ContainerManager\ContainerManager;

class BaseRun
{
    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    protected function run($data)
    {

		try {

			$key = $data["key"];

			//courseName(ex: python , java , ...) is an array that contain some configs stored in config/files.php
			//return each language config with course id.
			$courseConfig = KeyManager::getCourseConfig($key);

			//check existence of the given path on host . if its not valid throw an Exeption
			$path = $courseConfig["container_default_files"] . $data["path"];
			if (!is_dir($path))
				throw  new \Exception("Error: directory ($path) is not available !\nmay be this is not valid path!");



			if (!ContainerManager::getContainerState($key)) //check container state (Running(1)/Existed(0)).
				ContainerManager::startContainer($key);

			KeyManager::updateTimeStamp($key); // update time stamp to stop container on time

		}catch (\Exception $e){
			throw $e;
		}
    }
}