<?php
/**
 * Created by PhpStorm.
 * User: mohammadreza
 * Date: 11/26/18
 * Time: 11:29 AM
 */

namespace App\Clasess\Languages\WebBased\Javascript\MainActions\Run;


use App\Clasess\Base\MainActions\BaseRun\BaseRun;
use App\Clasess\Base\Managers\KeyManager\KeyManager;
use App\Clasess\Base\Managers\FileManager\FileManager;


class Run extends BaseRun
{
    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function run($data)
    {
		$courseConfig = KeyManager::getCourseConfig($data['key']);
		$path = $data['path'];
		$key = $data['key'];
		$files = $data['files'];
		$hashKey = hash('md5',$key);

		$baseDir = $courseConfig['container_shared_files'].DIRECTORY_SEPARATOR.$hashKey.$path;
		$status = FileManager::createFiles($files, $baseDir);


		if ($status) {
			$src = $courseConfig['container_shared_files'] . DIRECTORY_SEPARATOR . $hashKey . $path;
			$dst = $courseConfig['container_shared_files'] . DIRECTORY_SEPARATOR . $hashKey . DIRECTORY_SEPARATOR . 'run';
			FileManager::recurse_copy($src, $dst);
		}
		return[
			'path'=>  $hashKey .DIRECTORY_SEPARATOR.'run'
		];
    }
}