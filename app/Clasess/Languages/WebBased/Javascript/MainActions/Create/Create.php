<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\Create;


use App\Clasess\Base\MainActions\BaseCreate\BaseCreate;
use App\Clasess\Base\Managers\FileManager\FileManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;

class Create extends BaseCreate
{
    /**
     * create container
     * start apache
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function createContainer($request)
    {
		//todo test container state
		$courseConfig = KeyManager::getCourseConfig($request['key']);
		$hashKey = hash('md5',$request['key']);

		if (!is_dir($courseConfig['container_shared_files'].DIRECTORY_SEPARATOR.$hashKey))
			FileManager::recurse_copy($courseConfig['container_default_files'],$courseConfig['container_shared_files'].DIRECTORY_SEPARATOR.$hashKey);

		return [
			"result"=>true
		];
    }
}