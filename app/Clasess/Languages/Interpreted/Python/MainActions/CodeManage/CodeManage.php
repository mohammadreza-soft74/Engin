<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/24/18
 * Time: 10:42 AM
 */

namespace App\Clasess\Languages\Interpreted\Python\MainActions\CodeManage;


use App\Clasess\Base\MainActions\BaseCodeManager\BaseCodeManager;
use App\Clasess\Base\Managers\FileManager\FileManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;

class CodeManage extends BaseCodeManager
{
    /**
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    public function finalCode(String $path, String $key)
    {
        return parent::finalCode($path, $key);
    }

    /**
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    public function resetCode(String $path, String $key)
    {

		parent::resetCode($path, $key);

		$courseConfig = KeyManager::getCourseConfig($key);
		$container_default_files = $courseConfig['container_default_files'].$path;
		$container_shared_files = $courseConfig['container_shared_files'].$key.$path;

		FileManager::deleteFilesInDirectory($container_shared_files);
		FileManager::recurse_copy($container_default_files,  $container_shared_files);

		return[
			"error" => false,
			"result" => "Files were successfully resetted!",
		];

    }
}