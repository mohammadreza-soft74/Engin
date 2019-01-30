<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:28 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\CodeManage;


use App\Clasess\Base\MainActions\BaseCodeManager\BaseCodeManager;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\FileManager\FileManager;


class CodeManage extends BaseCodeManager
{
    /**
     * @param String $path
     * @param String $key
     * @return array|false|null|string
     * @throws \Exception
     */
    public function resetCode(String $path, String $key)
    {
		$courseConfig = KeyManager::getCourseConfig($key);
		$hashKey = hash('md5',$key);
		$src = $courseConfig['container_default_files'].$path;
		$dst = $courseConfig['container_shared_files'].DIRECTORY_SEPARATOR.$hashKey.$path;
		FileManager::recurse_copy($src, $dst);
		$files = FileManager::getFiles($src);
		return[
			'result' => $files
		];
    }

    /**
     * @param String $path
     * @param String $key
     * @return array
     * @throws \Exception
     */
    public function finalCode(String $path, String $key)
    {
		$courseConfig = KeyManager::getCourseConfig($key);
		$path = $courseConfig["files_on_host"] . $path ."/last"; //requesr->path = /py1/page1

		$files = FileManager::getFiles($path );

		return [
			'result'=> $files
		];
    }
}