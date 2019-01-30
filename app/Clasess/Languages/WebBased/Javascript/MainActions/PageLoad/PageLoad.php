<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\PageLoad;


use App\Clasess\Base\MainActions\BasePageLoad\BasePageLoad;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\FileManager\FileManager;


class PageLoad extends BasePageLoad
{

    public function PageLoad($request)
    {
		//todo check service state
		$hashKey = hash('md5', $request['key']);

		$key = $request['key'];

		$courseConfig = KeyManager::getCourseConfig($key);

		$path = $courseConfig['container_shared_files'].DIRECTORY_SEPARATOR.$hashKey.DIRECTORY_SEPARATOR.$request['path'];

		$files = FileManager::getFiles($path);

		return [
			'files'=>$files
		];
    }

}